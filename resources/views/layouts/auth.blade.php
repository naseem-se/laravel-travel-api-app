<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDM Solution - @yield('title')</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans overflow-hidden">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="fixed lg:relative w-[240px] bg-gradient-to-b from-[#2B4A99] via-[#2B4A99] to-[#1e3470] text-white flex flex-col shadow-2xl z-50 h-full -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out"
            id="sidebar">
            <!-- Close button for mobile -->
            <button class="lg:hidden absolute top-4 right-4 text-white hover:text-gray-300 transition-colors"
                id="close-sidebar">
                <i class="fas fa-times text-2xl"></i>
            </button>

            <!-- Logo/Header -->
            <div class="p-4 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white rounded-md flex items-center justify-center">
                        <span class="text-[#2B4A99] text-base font-bold">E</span>
                    </div>
                    <span class="text-white text-base font-medium">EDM Solutions</span>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 overflow-y-auto py-4 px-3" x-data="{ openShifts: false }">
                <div class="space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('pages.dashboard') }}"
                        class="flex items-center px-3 py-2.5 {{ Request::routeIs('pages.dashboard') ? 'bg-[#3d5a9e]' : 'hover:bg-white/5' }} text-white rounded-lg transition-all duration-200">
                        <i class="fas fa-chart-line w-4 text-sm mr-3"></i>
                        <span class="text-sm">Dashboard</span>
                    </a>

                    <!-- Users -->
                    <a href="{{ route('pages.user') }}"
                        class="flex items-center justify-between px-3 py-2.5 {{ Request::routeIs('pages.user') ? 'bg-[#3d5a9e]' : 'hover:bg-white/5' }} text-white rounded-lg transition-all duration-200 group">
                        <div class="flex items-center">
                            <i class="fas fa-users w-4 text-sm mr-3"></i>
                            <span class="text-sm">Users</span>
                        </div>
                        <i class="fas fa-chevron-right text-xs opacity-50"></i>
                    </a>

                    <!-- Facilities -->
                    <a href="{{ route('pages.facilities') }}"
                        class="flex items-center px-3 py-2.5 {{ Request::routeIs('pages.facilities') ? 'bg-[#3d5a9e]' : 'hover:bg-white/5' }} text-white rounded-lg transition-all duration-200">
                        <i class="fas fa-building w-4 text-sm mr-3"></i>
                        <span class="text-sm">Facilities</span>
                    </a>

                    <!-- Shifts with Dropdown -->
                    <div>
                        <button @click="openShifts = !openShifts"
                            class="flex items-center justify-between w-full px-3 py-2.5 {{ Request::routeIs('pages.shifts') ? 'bg-[#3d5a9e]' : 'hover:bg-white/5' }} text-white rounded-lg transition-all duration-200">
                            <div class="flex items-center">
                                <i class="fas fa-calendar-alt w-4 text-sm mr-3"></i>
                                <span class="text-sm">Shifts</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs opacity-50 transition-transform duration-200"
                                :class="{ 'rotate-180': openShifts }"></i>
                        </button>

                        <!-- Dropdown Items -->
                        <div x-show="openShifts" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0" class="ml-7 mt-1 space-y-1">
                            <a href="/shifts"
                                class="flex items-center px-3 py-2 text-gray-300 hover:text-white text-sm rounded-lg hover:bg-white/5 transition-all duration-200">
                                <i class="fas fa-plus text-xs mr-2 w-3"></i>
                                <span class="text-xs">Create Shift</span>
                            </a>
                            <a href="#"
                                class="flex items-center px-3 py-2 text-gray-300 hover:text-white text-sm rounded-lg hover:bg-white/5 transition-all duration-200">
                                <i class="fas fa-check text-xs mr-2 w-3"></i>
                                <span class="text-xs">Approve Shifts</span>
                            </a>
                            <a href="/calender-view"
                                class="flex items-center px-3 py-2 text-gray-300 hover:text-white text-sm rounded-lg hover:bg-white/5 transition-all duration-200">
                                <i class="fas fa-calendar text-xs mr-2 w-3"></i>
                                <span class="text-xs">Calendar View</span>
                            </a>
                            <a href="/open-shifts"
                                class="flex items-center px-3 py-2 text-gray-300 hover:text-white text-sm rounded-lg hover:bg-white/5 transition-all duration-200">
                                <i class="fas fa-folder-open text-xs mr-2 w-3"></i>
                                <span class="text-xs">Open Shifts</span>
                            </a>
                        </div>
                    </div>

                    <!-- Compliance -->
                    <a href="/compliance"
                        class="flex items-center px-3 py-2.5 text-white hover:bg-white/5 rounded-lg transition-all duration-200">
                        <i class="fas fa-clipboard-check w-4 text-sm mr-3"></i>
                        <span class="text-sm">Compliance</span>
                    </a>
                    <!-- shift orchestration -->
                    <a href="/shift-orchestration"
                        class="flex items-center px-3 py-2.5 text-white hover:bg-white/5 rounded-lg transition-all duration-200">
                        <i class="fas fa-clipboard-check w-4 text-sm mr-3"></i>
                        <span class="text-sm">Shift Orchestration</span>
                    </a>
                    <!-- time sheet -->
                    <a href="/time-sheet"
                        class="flex items-center px-3 py-2.5 text-white hover:bg-white/5 rounded-lg transition-all duration-200">
                        <i class="fas fa-clipboard-check w-4 text-sm mr-3"></i>
                        <span class="text-sm">Timesheet Exceptions</span>
                    </a>

                    <!-- Payroll & Billing -->
                    <a href="{{ route('pages.payments') }}"
                        class="flex items-center px-3 py-2.5 {{ Request::routeIs('pages.payments') ? 'bg-[#3d5a9e]' : 'hover:bg-white/5' }} text-white rounded-lg transition-all duration-200">
                        <i class="fas fa-dollar-sign w-4 text-sm mr-3"></i>
                        <span class="text-sm">Payroll & Billing</span>
                    </a>

                    <!-- Reports -->
                    <a href="{{ route('pages.reports') }}"
                        class="flex items-center px-3 py-2.5 {{ Request::routeIs('pages.reports') ? 'bg-[#3d5a9e]' : 'hover:bg-white/5' }} text-white rounded-lg transition-all duration-200">
                        <i class="fas fa-chart-bar w-4 text-sm mr-3"></i>
                        <span class="text-sm">Reports</span>
                    </a>

                    <!-- Documents -->
                    <a href="/document"
                        class="flex items-center px-3 py-2.5 text-white hover:bg-white/5 rounded-lg transition-all duration-200">
                        <i class="fas fa-folder w-4 text-sm mr-3"></i>
                        <span class="text-sm">Documents</span>
                    </a>

                    <!-- Support -->
                    <a href="{{ route('pages.support') }}"
                        class="flex items-center px-3 py-2.5 {{ Request::routeIs('pages.support') ? 'bg-[#3d5a9e]' : 'hover:bg-white/5' }} text-white rounded-lg transition-all duration-200">
                        <i class="fas fa-headset w-4 text-sm mr-3"></i>
                        <span class="text-sm">Support</span>
                    </a>

                    <!-- Communication -->
                    <a href="/notification"
                        class="flex items-center px-3 py-2.5 text-white hover:bg-white/5 rounded-lg transition-all duration-200">
                        <i class="fas fa-comments w-4 text-sm mr-3"></i>
                        <span class="text-sm">Communication</span>
                    </a>

                    <!-- Audit Logs -->
                    <a href="/audit-log"
                        class="flex items-center px-3 py-2.5 text-white hover:bg-white/5 rounded-lg transition-all duration-200">
                        <i class="fas fa-history w-4 text-sm mr-3"></i>
                        <span class="text-sm">Audit Logs</span>
                    </a>

                    <!-- Settings -->
                    <a href="/setting"
                        class="flex items-center px-3 py-2.5 text-white hover:bg-white/5 rounded-lg transition-all duration-200">
                        <i class="fas fa-cog w-4 text-sm mr-3"></i>
                        <span class="text-sm">Settings</span>
                    </a>
                </div>
            </nav>

            <!-- Version -->
            <div class="p-4 border-t border-white/10">
                <p class="text-gray-400 text-xs">v1.0.0</p>
            </div>
        </div>

        <!-- Mobile overlay -->
        <div class="fixed inset-0 bg-black/50 z-40 hidden" id="sidebar-overlay"></div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Navigation Bar -->
            <div class="bg-white shadow-sm border-b border-gray-200 px-6 py-3">
                <div class="flex items-center justify-between">
                    <!-- Left: Mobile Menu + Logo -->
                    <div class="flex items-center gap-4">
                        <!-- Mobile Menu Button -->
                        <button class="lg:hidden text-gray-600 hover:text-gray-900 transition-colors"
                            id="mobile-menu-button">
                            <i class="fas fa-bars text-xl"></i>
                        </button>

                        <!-- Logo & Brand -->
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-[#2B4A99] rounded-lg flex items-center justify-center">
                                <span class="text-white text-sm font-bold">E</span>
                            </div>
                            <span class="text-gray-900 text-base font-semibold hidden sm:block">EDM Solutions</span>
                        </div>
                    </div>

                    <!-- Center: Search Bar -->
                    <div class="hidden md:flex flex-1 max-w-xl mx-8">
                        <div class="relative w-full">
                            <input type="text" placeholder="Search users, facilities, shifts..."
                                class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" />
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Right: Notifications & User -->
                    <div class="flex items-center gap-4">
                        <!-- Notification Bell -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.away="open = false"
                                class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                                    </path>
                                </svg>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>

                            <!-- Notification Dropdown -->
                            <div x-show="open" x-transition
                                class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg border border-gray-200 z-50"
                                style="display: none;">
                                <div class="px-4 py-3 border-b border-gray-200">
                                    <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                </div>
                                <div class="max-h-96 overflow-y-auto">
                                    <a href="#"
                                        class="block px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100">
                                        <div class="flex gap-3">
                                            <div
                                                class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-user text-blue-600"></i>
                                            </div>
                                            <div class="flex-1">
                                                <p class="text-sm text-gray-900 font-medium">New user registered</p>
                                                <p class="text-xs text-gray-400 mt-1">2 minutes ago</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Messages -->
                        <button
                            class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                                </path>
                            </svg>
                            <span class="absolute top-1 right-1 w-2 h-2 bg-blue-500 rounded-full"></span>
                        </button>

                        <!-- User Profile -->
                        <div class="flex items-center gap-3 pl-3 border-l border-gray-200">
                            <div class="w-9 h-9 bg-[#2B4A99] rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-semibold">SK</span>
                            </div>
                            <div class="hidden lg:block">
                                <div class="text-sm font-semibold text-gray-900">Saddam Khoso</div>
                                <div class="text-xs text-gray-500">Super Admin</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Area -->
            @yield('content')
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>

    <script>
        // Mobile menu functionality
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const closeSidebarButton = document.getElementById('close-sidebar');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        function toggleMobileMenu() {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
        }

        if (mobileMenuButton) {
            mobileMenuButton.addEventListener('click', toggleMobileMenu);
        }

        if (closeSidebarButton) {
            closeSidebarButton.addEventListener('click', toggleMobileMenu);
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', toggleMobileMenu);
        }
    </script>

    @stack('scripts')
</body>

</html>
