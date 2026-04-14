<aside :class="sidebarToggle ? 'translate-x-0 lg:w-[90px]' : '-translate-x-full'"
    class="sidebar fixed left-0 top-0 z-9999 flex h-screen w-[290px] flex-col overflow-y-hidden border-r border-gray-200 bg-white px-5 dark:border-gray-800 dark:bg-black lg:static lg:translate-x-0">
    <!-- SIDEBAR HEADER -->
    <div :class="sidebarToggle ? 'justify-center' : 'justify-between'"
        class="flex items-center gap-2 pt-8 sidebar-header pb-7">
        <a href="{{ route('admin.dashboard') }}">
            <span class="logo" :class="sidebarToggle ? 'hidden' : ''">
                <img class="dark:hidden" src="{{ asset('assets') }}/images/logo/logo.svg" alt="Logo" />
                <img class="hidden dark:block" src="{{ asset('assets') }}/images/logo/logo-dark.svg" alt="Logo" />
            </span>

            <img class="logo-icon" :class="sidebarToggle ? 'lg:block' : 'hidden'"
                src="{{ asset('assets') }}/images/logo/logo-icon.svg" alt="Logo" />
        </a>
    </div>
    <!-- SIDEBAR HEADER -->

    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
        <!-- Sidebar Menu -->
        <nav x-data="{ selected: $persist('Dashboard') }">
            <!-- Menu Group -->
            <div>
                <h3 class="mb-4 text-xs uppercase leading-[20px] text-gray-400">
                    <!-- Menu Group Icon (visible when sidebar collapsed) -->
                    <i :class="sidebarToggle ? 'lg:block hidden' : 'hidden'" data-lucide="grid-3x3"
                        class="mx-auto w-6 h-6 stroke-gray-400"></i>
                </h3>

                <ul class="flex flex-col gap-4 mb-6">
                    <!-- Menu Item Dashboard -->
                    <li>
                        <a href="{{ route('admin.dashboard') }}"
                            @click="selected = (selected === 'Dashboard' ? '':'Dashboard')"
                            class="menu-item group flex items-center gap-3 px-4 py-2 rounded-lg transition-colors"
                            :class="(selected === 'Dashboard') ?
                            'menu-item-active bg-primary-50 text-primary-600 dark:bg-primary-900/20' :
                            'menu-item-inactive text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800'">
                            <i data-lucide="layout-dashboard" class="w-5 h-5 transition-colors"
                                :class="(selected === 'Dashboard') ? 'stroke-primary-600' : 'stroke-current'"></i>

                            <span class="menu-item-text text-sm font-medium" :class="sidebarToggle ? 'lg:hidden' : ''">
                                Dashboard
                            </span>
                        </a>
                    </li>

                    <li>
                        <a href="#"
                            @click.prevent="selected = (selected === 'HRM' ? '':'HRM')"
                            class="menu-item group flex items-center justify-between px-4 py-2 rounded-lg transition-colors"
                            :class="(selected === 'HRM') ?
                            'menu-item-active bg-primary-50 text-primary-600 dark:bg-primary-900/20' :
                            'menu-item-inactive text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800'">
                            <div class="flex items-center gap-3">
                                <i data-lucide="users" class="w-5 h-5 transition-colors"
                                    :class="(selected === 'HRM') ? 'stroke-primary-600' : 'stroke-current'"></i>

                                <span class="menu-item-text text-sm font-medium"
                                    :class="sidebarToggle ? 'lg:hidden' : ''">
                                    HRM
                                </span>
                            </div>

                            <i data-lucide="chevron-down"
                                class="menu-item-arrow w-4 h-4 transition-transform duration-200"
                                :class="[(selected === 'HRM') ? 'rotate-180' : '', sidebarToggle ? 'lg:hidden' : '']"></i>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="overflow-hidden transition-all duration-300"
                            :class="(selected === 'HRM') ? 'max-h-40' : 'max-h-0'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : ''" class="flex flex-col gap-1 mt-2 ml-9">
                                <li>
                                    <a href="{{ route('admin.hrm.admins.index') }}"
                                        class="menu-dropdown-item flex items-center gap-3 px-4 py-2 text-sm rounded-lg transition-colors text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800"
                                        :class="page === 'admin' ?
                                            'menu-dropdown-item-active bg-primary-50 text-primary-600' : ''">
                                        <i data-lucide="user-cog" class="w-4 h-4"></i>
                                        Admins
                                    </a>
                                </li>
                                 <li>
                                    <a href="{{ route('admin.hrm.users.index') }}"
                                        class="menu-dropdown-item flex items-center gap-3 px-4 py-2 text-sm rounded-lg transition-colors text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800"
                                        :class="page === 'user' ?
                                            'menu-dropdown-item-active bg-primary-50 text-primary-600' : ''">
                                        <i data-lucide="user" class="w-4 h-4"></i>
                                        Users
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>

                    <!-- Menu Item Configuration with Dropdown -->
                    <li>
                        <a href="#"
                            @click.prevent="selected = (selected === 'Configuration' ? '':'Configuration')"
                            class="menu-item group flex items-center justify-between px-4 py-2 rounded-lg transition-colors"
                            :class="(selected === 'Configuration') ?
                            'menu-item-active bg-primary-50 text-primary-600 dark:bg-primary-900/20' :
                            'menu-item-inactive text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800'">
                            <div class="flex items-center gap-3">
                                <i data-lucide="settings" class="w-5 h-5 transition-colors"
                                    :class="(selected === 'Configuration') ? 'stroke-primary-600' : 'stroke-current'"></i>

                                <span class="menu-item-text text-sm font-medium"
                                    :class="sidebarToggle ? 'lg:hidden' : ''">
                                    Configuration
                                </span>
                            </div>

                            <i data-lucide="chevron-down"
                                class="menu-item-arrow w-4 h-4 transition-transform duration-200"
                                :class="[(selected === 'Configuration') ? 'rotate-180' : '', sidebarToggle ? 'lg:hidden' : '']"></i>
                        </a>

                        <!-- Dropdown Menu Start -->
                        <div class="overflow-hidden transition-all duration-300"
                            :class="(selected === 'Configuration') ? 'max-h-40' : 'max-h-0'">
                            <ul :class="sidebarToggle ? 'lg:hidden' : ''" class="flex flex-col gap-1 mt-2 ml-9">
                                <li>
                                    <a href="{{ route('admin.role-permissions.index') }}"
                                        class="menu-dropdown-item flex items-center gap-3 px-4 py-2 text-sm rounded-lg transition-colors text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800"
                                        :class="page === 'ecommerce' ?
                                            'menu-dropdown-item-active bg-primary-50 text-primary-600' : ''">
                                        <i data-lucide="shield" class="w-4 h-4"></i>
                                        Role Permissions
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <!-- Dropdown Menu End -->
                    </li>
                </ul>
            </div>
        </nav>
        <!-- Sidebar Menu -->

        <!-- Promo Box -->
    </div>
</aside>

<!-- Initialize Lucide Icons -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
