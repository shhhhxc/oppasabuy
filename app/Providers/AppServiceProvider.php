<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Room;
use Illuminate\Routing\UrlGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(UrlGenerator $url): void
{
    if ($this->app->environment('production')) {
        $url->forceScheme('https');
    }

    // This View Composer ensures $globalUnreadCount is available in ALL views
    View::composer('*', function ($view) {
        if (Auth::check()) {
            $userId = Auth::id();

            $unread = Room::whereHas('users', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->withCount(['messages as unread_messages_count' => function ($query) use ($userId) {
                $query->where('messages.user_id', '!=', $userId)
                      ->where('messages.created_at', '>', function ($subQuery) use ($userId) {
                          $subQuery->selectRaw("COALESCE(last_read_at, '1970-01-01 00:00:00')")
                              ->from('room_user')
                              ->whereColumn('room_user.room_id', 'messages.room_id')
                              ->where('room_user.user_id', $userId)
                              ->limit(1);
                      });
            }])
            ->get()
            ->sum('unread_messages_count');

            $view->with('globalUnreadCount', $unread);
        } else {
            $view->with('globalUnreadCount', 0);
        }
    });
}
}