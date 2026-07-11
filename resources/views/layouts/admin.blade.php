<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oppasabuy Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,800&display=swap" rel="stylesheet" />
    <!-- Adding Bootstrap Icons for the analytics/chat visual cues -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-[#F8F9FA] font-[Figtree] antialiased">
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between h-20 items-center">
                
                <div class="flex items-center space-x-8">
                    <a href="{{ url('/') }}" class="transition-transform hover:scale-105 active:scale-95">
                        <img src="{{ asset('oppa.png') }}" alt="Oppasabuy" class="h-10">
                    </a>
                    
                    <div class="hidden md:flex space-x-6">
                        <!-- VERIFY USERS -->
                        <a href="{{ route('admin.verify') }}" 
                           class="text-sm font-bold {{ request()->routeIs('admin.verify') ? 'text-[#A31D1D] border-b-2 border-[#A31D1D]' : 'text-gray-400 hover:text-gray-600' }} pb-1 uppercase tracking-wider transition-all duration-200">
                            VERIFY USERS
                        </a>

                        <!-- SELLER ANALYTICS -->
                        <a href="{{ route('admin.analytics') }}" 
                           class="text-sm font-bold {{ request()->routeIs('admin.analytics*') ? 'text-[#A31D1D] border-b-2 border-[#A31D1D]' : 'text-gray-400 hover:text-gray-600' }} pb-1 uppercase tracking-wider transition-all duration-200">
                            INSIGHTS
                        </a>

                        <!-- ADMIN INVENTORY (Add Products) -->
                        <a href="{{ route('seller.dashboard') }}"
                           class="text-sm font-bold {{ request()->is('seller/dashboard*') ? 'text-[#A31D1D] border-b-2 border-[#A31D1D]' : 'text-gray-400 hover:text-gray-600' }} pb-1 uppercase tracking-wider transition-all duration-200">
                            INVENTORY
                        </a>
                        
                        <!-- Add this next to your other links like VERIFY USERS and INSIGHTS -->
<a href="{{ route('admin.users') }}" 
   class="text-sm font-bold {{ request()->routeIs('admin.users') ? 'text-[#A31D1D] border-b-2 border-[#A31D1D]' : 'text-gray-400 hover:text-gray-600' }} pb-1 uppercase tracking-wider transition-all">
    USER LIST
</a>
                        <!-- CHAT & MEDIA LOGS -->
                        <a href="{{ route('admin.chats') }}" 
                           class="text-sm font-bold {{ request()->routeIs('admin.chats') ? 'text-[#A31D1D] border-b-2 border-[#A31D1D]' : 'text-gray-400 hover:text-gray-600' }} pb-1 uppercase tracking-wider transition-all duration-200">
                            CHAT LOGS
                        </a>

                        <a href="{{ route('admin.inquiries') }}" 
   class="text-sm font-bold {{ request()->routeIs('admin.inquiries') ? 'text-[#A31D1D] border-b-2 border-[#A31D1D]' : 'text-gray-400 hover:text-gray-600' }} pb-1 uppercase tracking-wider transition-all duration-200">
    INQUIRIES
</a>
                    </div>
                </div>

                <div class="flex items-center space-x-6">
                    <div class="hidden sm:flex items-center space-x-4 text-sm font-semibold text-gray-700">
                        <a href="{{ route('home') }}" class="hover:text-[#A31D1D] transition-colors">View Site</a>
                        <span class="text-gray-200">|</span>
                        <div class="flex items-center space-x-2 bg-red-50 px-3 py-1.5 rounded-full border border-red-100">
                            <span class="bg-[#A31D1D] w-2 h-2 rounded-full animate-pulse"></span>
                            <span class="text-[#A31D1D] uppercase tracking-widest text-[9px] font-black">Admin Panel</span>
                        </div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" class="border-l pl-6 border-gray-100">
                        @csrf
                        <button type="submit" class="flex items-center group" title="Logout">
                            <div class="p-2.5 rounded-xl group-hover:bg-red-50 transition duration-200 text-gray-400 group-hover:text-[#A31D1D] border border-transparent group-hover:border-red-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </div>
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </nav>

    <main class="py-12 min-h-[calc(100vh-180px)]">
        <div class="max-w-7xl mx-auto px-6">
            @yield('content')
        </div>
    </main>

    <footer class="max-w-7xl mx-auto px-6 py-10 border-t border-gray-100">
        <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <p class="text-gray-400 text-[10px] font-bold uppercase tracking-[0.2em]">
                Oppasabuy &copy; {{ date('Y') }} — Internal Administrative System
            </p>
            <div class="flex items-center space-x-4">
                <span class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Version 1.2.0</span>
            </div>
        </div>
    </footer>
</body>
</html>