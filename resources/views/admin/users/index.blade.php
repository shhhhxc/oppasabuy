@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-sm overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
        <div>
            <h3 class="text-xl font-black text-gray-900">User Management</h3>
            <p class="text-xs text-gray-400 font-bold uppercase tracking-wider">
                Monitor sellers, buyers, riders, and platform growth
            </p>
        </div>

        <div class="flex space-x-3">
            <span class="bg-blue-50 text-[#0d47a1] text-[10px] font-black px-3 py-1 rounded-lg uppercase">
                Total Users: {{ $users->count() }}
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="mx-8 mt-8 rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm font-bold text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mx-8 mt-8 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-sm font-bold text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] bg-gray-50/50">
                    <th class="px-8 py-4">User Info</th>
                    <th class="px-8 py-4">Role & Plan</th>
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4">Account</th>
                    <th class="px-8 py-4">Dates</th>
                    <th class="px-8 py-4 text-right">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-50">
                @foreach($users as $user)
                    @php
                        $displayName = $user->name ?? $user->full_name ?? 'Unknown User';
                        $role = strtolower($user->role ?? 'buyer');
                        $accountStatus = strtolower($user->account_status ?? 'active');

                        $roleLabel = match($role) {
                            'seller' => 'Seller',
                            'rider' => 'Rider',
                            'admin' => 'Admin',
                            default => 'Buyer',
                        };

                        $roleBadgeClass = match($role) {
                            'seller' => 'bg-blue-50 text-[#0d47a1]',
                            'rider' => 'bg-green-50 text-green-700',
                            'admin' => 'bg-red-50 text-red-700',
                            default => 'bg-gray-100 text-gray-500',
                        };

                        $verificationStatus = 'standard';
                        $approvedDate = null;
                        $secondaryLabel = null;

                        if ($role === 'seller') {
                            $verificationStatus = $user->sellerVerification->status ?? 'pending';
                            $approvedDate = $user->sellerVerification->verified_at
                                ?? $user->sellerVerification->approved_at
                                ?? null;

                            $secondaryLabel = 'Plan: ' . ($user->sellerVerification->plan ?? 'N/A');
                        } elseif ($role === 'rider') {
                            $verificationStatus = $user->rider->status ?? 'pending';
                            $approvedDate = $user->rider->verified_at ?? null;

                            $vehicleParts = array_filter([
                                $user->rider->vehicle_brand ?? null,
                                $user->rider->vehicle_model ?? null,
                            ]);

                            $vehicleName = !empty($vehicleParts)
                                ? implode(' ', $vehicleParts)
                                : ($user->rider->vehicle_type ?? 'N/A');

                            $secondaryLabel = 'Vehicle: ' . $vehicleName;
                        } elseif ($role === 'admin') {
                            $verificationStatus = 'administrator';
                            $secondaryLabel = 'Platform Administrator';
                        } else {
                            if (($user->verification_status ?? null) === 'approved' || $user->is_verified) {
                                $verificationStatus = 'approved';
                            } elseif (($user->verification_status ?? null) === 'rejected') {
                                $verificationStatus = 'rejected';
                            } else {
                                $verificationStatus = 'standard';
                            }
                        }

                        $statusLabel = match($verificationStatus) {
                            'approved' => 'Verified',
                            'rejected' => 'Rejected',
                            'pending' => 'Pending',
                            'administrator' => 'Administrator',
                            default => 'Standard',
                        };

                        $statusClass = match($verificationStatus) {
                            'approved' => 'text-green-600',
                            'rejected' => 'text-red-600',
                            'pending' => 'text-amber-500',
                            'administrator' => 'text-red-600',
                            default => 'text-gray-300',
                        };

                        $statusIcon = match($verificationStatus) {
                            'approved' => 'bi bi-patch-check-fill',
                            'rejected' => 'bi bi-x-circle-fill',
                            'pending' => 'bi bi-clock-history',
                            'administrator' => 'bi bi-shield-check',
                            default => null,
                        };

                        $accountStatusLabel = match($accountStatus) {
                            'locked' => 'Locked',
                            'disabled' => 'Disabled',
                            default => 'Active',
                        };

                        $accountStatusClass = match($accountStatus) {
                            'locked' => 'bg-amber-50 text-amber-700 border-amber-100',
                            'disabled' => 'bg-red-50 text-red-700 border-red-100',
                            default => 'bg-green-50 text-green-700 border-green-100',
                        };
                    @endphp

                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-8 py-6">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 font-bold">
                                    {{ strtoupper(substr($displayName, 0, 1)) }}
                                </div>

                                <div>
                                    <span class="block text-sm font-black text-gray-900">
                                        {{ $displayName }}
                                    </span>

                                    <span class="block text-[11px] text-gray-400 font-bold">
                                        {{ $user->email }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        <td class="px-8 py-6">
                            <span class="inline-block px-3 py-1 rounded-lg {{ $roleBadgeClass }} text-[10px] font-black uppercase mb-1">
                                {{ $roleLabel }}
                            </span>

                            @if($secondaryLabel)
                                <span class="block text-[11px] text-gray-500 font-bold">
                                    {{ $secondaryLabel }}
                                </span>
                            @endif
                        </td>

                        <td class="px-8 py-6">
                            <div class="flex items-center {{ $statusClass }} space-x-1">
                                @if($statusIcon)
                                    <i class="{{ $statusIcon }}"></i>
                                @endif

                                <span class="text-[10px] font-black uppercase">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                        </td>

                        <td class="px-8 py-6">
                            <span class="inline-flex rounded-lg border px-3 py-1 text-[10px] font-black uppercase {{ $accountStatusClass }}">
                                {{ $accountStatusLabel }}
                            </span>
                        </td>

                        <td class="px-8 py-6">
                            <div class="text-[11px] font-bold">
                                <span class="text-gray-400 uppercase text-[9px] block">
                                    Registered:
                                </span>

                                <span class="text-gray-700">
                                    {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}
                                </span>

                                @if($approvedDate)
                                    <span class="text-gray-400 uppercase text-[9px] block mt-1">
                                        Approved:
                                    </span>

                                    <span class="{{ $role === 'rider' ? 'text-green-700' : 'text-[#0d47a1]' }}">
                                        {{ \Carbon\Carbon::parse($approvedDate)->format('M d, Y') }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="px-8 py-6 text-right">
                            <div class="relative inline-block text-left user-action-wrapper">
                                <button
                                    type="button"
                                    onclick="toggleUserMenu({{ $user->id }}, event)"
                                    class="p-2 text-gray-400 hover:text-[#A31D1D] transition"
                                    aria-label="Open user actions"
                                >
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>

                                <div
                                    id="user-menu-{{ $user->id }}"
                                    class="hidden absolute right-0 z-40 mt-2 w-60 overflow-hidden rounded-2xl border border-gray-100 bg-white text-left shadow-2xl"
                                >
                                    <a
                                        href="{{ route('admin.users.show', $user->id) }}"
                                        class="flex items-center px-4 py-3 text-xs font-bold text-gray-700 hover:bg-gray-50"
                                    >
                                        <i class="bi bi-person-lines-fill mr-3 text-blue-600"></i>
                                        View Profile & Documents
                                    </a>

                                    @if($role !== 'admin')
                                        @if($accountStatus === 'active')
                                            <form action="{{ route('admin.users.lock', $user->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="flex w-full items-center px-4 py-3 text-left text-xs font-bold text-amber-600 hover:bg-amber-50"
                                                    onclick="return confirm('Are you sure you want to lock this account?')"
                                                >
                                                    <i class="bi bi-lock-fill mr-3"></i>
                                                    Lock Account
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.users.disable', $user->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="flex w-full items-center px-4 py-3 text-left text-xs font-bold text-red-600 hover:bg-red-50"
                                                    onclick="return confirm('Are you sure you want to disable this account?')"
                                                >
                                                    <i class="bi bi-person-x-fill mr-3"></i>
                                                    Disable Account
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.users.activate', $user->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')

                                                <button
                                                    type="submit"
                                                    class="flex w-full items-center px-4 py-3 text-left text-xs font-bold text-green-600 hover:bg-green-50"
                                                    onclick="return confirm('Are you sure you want to reactivate this account?')"
                                                >
                                                    <i class="bi bi-person-check-fill mr-3"></i>
                                                    Reactivate Account
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <div class="px-4 py-3 text-[11px] font-bold text-gray-400">
                                            Administrator accounts cannot be locked or disabled.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleUserMenu(userId, event) {
        event.stopPropagation();

        document.querySelectorAll('[id^="user-menu-"]').forEach(function (menu) {
            if (menu.id !== 'user-menu-' + userId) {
                menu.classList.add('hidden');
            }
        });

        const menu = document.getElementById('user-menu-' + userId);

        if (menu) {
            menu.classList.toggle('hidden');
        }
    }

    document.addEventListener('click', function (event) {
        if (!event.target.closest('.user-action-wrapper')) {
            document.querySelectorAll('[id^="user-menu-"]').forEach(function (menu) {
                menu.classList.add('hidden');
            });
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            document.querySelectorAll('[id^="user-menu-"]').forEach(function (menu) {
                menu.classList.add('hidden');
            });
        }
    });
</script>
@endsection
