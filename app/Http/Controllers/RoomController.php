<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Order;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\RoomChatUpdated; 

class RoomController extends Controller
{
    /**
     * Display a specific group chat room.
     */
    public function show($id)
    {
        $user = Auth::user();
        $room = Room::with(['users', 'messages.user'])->findOrFail($id);

        if (!$room->users->contains($user->id)) {
            abort(403, 'Unauthorized access.');
        }

        // 1. Mark this room as read for the user right now
        $room->users()->updateExistingPivot($user->id, [
            'last_read_at' => now()
        ]);

        // 2. Fetch sidebar data with the fixed unread logic
        $userRooms = $this->getUserRoomsWithUnreadCount($user);

        $userOrders = Order::where(function($query) use ($user) {
            $query->where('buyer_id', $user->id)
                  ->orWhere('seller_id', $user->id)
                  ->orWhereHas('users', function($q) use ($user) {
                      $q->where('user_id', $user->id);
                  });
        })->latest()->get();

        $messages = $room->messages()->with('user')->oldest()->get();
        $availableUsers = User::where('id', '!=', $user->id)->get();

        return view('chat.chat', compact('room', 'messages', 'userRooms', 'userOrders', 'availableUsers'));
    }

    /**
     * NEW: Direct Chat Logic for Storefront
     * This finds or creates a room between the buyer and the vendor.
     */
  public function storeDirect(Request $request)
{
    // 1. Debugging: If this fails, it will show an error instead of refreshing silently
    $request->validate([
        'user_id' => 'required|exists:users,id'
    ]);

    $buyerId = Auth::id();
    $targetUserId = $request->user_id;

    if (!$buyerId) {
        return redirect()->route('login')->with('error', 'Please login to chat.');
    }

    // 2. Find existing room
    $room = Room::whereHas('users', function($q) use ($buyerId) {
        $q->where('user_id', $buyerId);
    })->whereHas('users', function($q) use ($targetUserId) {
        $q->where('user_id', $targetUserId);
    })->first();

    // 3. Create if not exists
    if (!$room) {
        $room = Room::create([
            'name' => 'Chat with ' . User::findOrFail($targetUserId)->name,
            'creator_id' => $buyerId,
        ]);
        
        $room->users()->attach([$buyerId, $targetUserId], ['last_read_at' => now()]);
    }

    return redirect()->route('rooms.show', $room->id);
}
    /**
     * Create a new group chat room.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'members' => 'nullable|array',
            'members.*' => 'exists:users,id'
        ]);

        $room = Room::create([
            'name' => $request->name,
            'creator_id' => Auth::id(),
        ]);

        $room->users()->attach(Auth::id(), ['last_read_at' => now()]);
        if ($request->members) {
            $room->users()->attach($request->members, ['last_read_at' => null]);
        }

        return redirect()->route('rooms.show', $room->id);
    }

    /**
     * Send a message.
     */
    public function sendMessage(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);

        $message = Message::create([
            'room_id'  => $id,          
            'user_id'  => Auth::id(),
            'message'  => $request->message,
        ]);

        Room::findOrFail($id)->users()->updateExistingPivot(Auth::id(), [
            'last_read_at' => now()
        ]);

        event(new RoomChatUpdated($message)); 

        return back()->with('success', 'Message sent!');
    }

    private function getUserRoomsWithUnreadCount($user)
    {
        return $user->rooms()
            ->withCount(['messages as unread_messages_count' => function ($query) use ($user) {
                $query->where('messages.user_id', '!=', $user->id)
                      ->where('messages.created_at', '>', function ($subQuery) use ($user) {
                          $subQuery->selectRaw("COALESCE(last_read_at, '1970-01-01 00:00:00')")
                                    ->from('room_user')
                                    ->whereColumn('room_user.room_id', 'messages.room_id')
                                    ->where('room_user.user_id', $user->id)
                                    ->limit(1);
                      });
            }])
            ->withCount('users')
            ->latest()
            ->get();
    }
}