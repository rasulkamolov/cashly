<?php
$current_page = basename($_SERVER['PHP_SELF'], ".php");
$nav_items = [
    'dashboard' => ['icon' => 'layout-dashboard', 'label' => 'Dashboard'],
    'transactions' => ['icon' => 'list', 'label' => 'Transactions'],
    'wallet' => ['icon' => 'wallet', 'label' => 'Wallet'],
    'goals' => ['icon' => 'target', 'label' => 'Goals'],
    'analytics' => ['icon' => 'bar-chart-2', 'label' => 'Analytics'],
    'reports' => ['icon' => 'file-text', 'label' => 'Reports'],
];
?>

<aside class="w-64 bg-white dark:bg-jm-navy border-r border-gray-200 dark:border-gray-700 hidden md:flex flex-col fixed h-full transition-colors duration-200 z-10">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center gap-3">
        <div class="w-8 h-8 bg-jm-primary rounded flex items-center justify-center text-white font-bold text-lg">J</div>
        <span class="font-bold text-xl text-jm-primary dark:text-white tracking-tight">JM Solutionss</span>
    </div>

    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1 px-3">
            <?php foreach ($nav_items as $page => $item): ?>
            <li>
                <a href="<?php echo $page == 'dashboard' ? '/' : '/' . $page . '.php'; ?>"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors duration-200 <?php echo ($current_page == $page || ($current_page == 'index' && $page == 'dashboard')) ? 'bg-jm-primary text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'; ?>">
                    <i data-lucide="<?php echo $item['icon']; ?>" class="w-5 h-5"></i>
                    <span class="font-medium"><?php echo $item['label']; ?></span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-3 px-4 py-2 text-gray-600 dark:text-gray-300 hover:text-jm-primary cursor-pointer transition-colors">
            <i data-lucide="log-out" class="w-5 h-5"></i>
            <span class="font-medium">Logout</span>
        </div>
    </div>
</aside>

<!-- Mobile Header / Toggle (Simple version) -->
<div class="md:hidden fixed top-0 w-full bg-white dark:bg-jm-navy border-b border-gray-200 dark:border-gray-700 p-4 z-20 flex justify-between items-center">
    <span class="font-bold text-xl text-jm-primary dark:text-white">JM Solutionss</span>
    <button id="mobile-menu-btn" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
        <i data-lucide="menu" class="w-6 h-6"></i>
    </button>
</div>

<!-- Mobile Menu Overlay -->
<div id="mobile-menu-overlay" class="fixed inset-0 bg-black/50 z-30 hidden md:hidden transition-opacity duration-300 opacity-0"></div>

<!-- Mobile Sidebar -->
<aside id="mobile-sidebar" class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-jm-navy border-r border-gray-200 dark:border-gray-700 z-40 transform -translate-x-full transition-transform duration-300 md:hidden flex flex-col h-full">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-jm-primary rounded flex items-center justify-center text-white font-bold text-lg">J</div>
            <span class="font-bold text-xl text-jm-primary dark:text-white tracking-tight">JM Solutionss</span>
        </div>
        <button id="close-mobile-menu-btn" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>

    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1 px-3">
            <?php foreach ($nav_items as $page => $item): ?>
            <li>
                <a href="<?php echo $page == 'dashboard' ? 'dashboard.php' : $page . '.php'; ?>"
                   class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors duration-200 <?php echo ($current_page == $page || ($current_page == 'index' && $page == 'dashboard')) ? 'bg-jm-primary text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800'; ?>">
                    <i data-lucide="<?php echo $item['icon']; ?>" class="w-5 h-5"></i>
                    <span class="font-medium"><?php echo $item['label']; ?></span>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>

    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-3 px-4 py-2 text-gray-600 dark:text-gray-300 hover:text-jm-primary cursor-pointer transition-colors">
            <i data-lucide="log-out" class="w-5 h-5"></i>
            <span class="font-medium">Logout</span>
        </div>
    </div>
</aside>

<!-- Main Content Wrapper Start -->
<div class="flex-1 md:ml-64 flex flex-col min-h-screen transition-all duration-200">

    <!-- Top Header -->
    <header class="h-16 bg-white dark:bg-jm-navy border-b border-gray-200 dark:border-gray-700 flex items-center justify-between px-6 sticky top-0 z-10 transition-colors duration-200">
        <!-- Search -->
        <div class="relative w-64 hidden sm:block">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
            </span>
            <input type="text" placeholder="Search..."
                   class="w-full py-2 pl-10 pr-4 text-sm border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 focus:outline-none focus:border-jm-primary focus:ring-1 focus:ring-jm-primary transition-colors">
        </div>

        <!-- Right Side Actions -->
        <div class="flex items-center gap-4">
            <!-- Theme Toggle -->
            <button id="theme-toggle" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 transition-colors" title="Toggle Theme">
                <i data-lucide="moon" class="w-5 h-5 dark:hidden"></i>
                <i data-lucide="sun" class="w-5 h-5 hidden dark:block"></i>
            </button>

            <!-- Notifications -->
            <button class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 relative transition-colors">
                <i data-lucide="bell" class="w-5 h-5"></i>
                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
            </button>

            <!-- Settings -->
            <button class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-300 transition-colors">
                <i data-lucide="settings" class="w-5 h-5"></i>
            </button>

            <!-- Profile -->
            <div class="flex items-center gap-3 pl-4 border-l border-gray-200 dark:border-gray-700">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-jm-black dark:text-white">John Doe</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Premium User</p>
                </div>
                <div class="w-9 h-9 bg-jm-secondary rounded-full flex items-center justify-center text-white font-bold text-sm">
                    JD
                </div>
            </div>
        </div>
    </header>

    <!-- Page Content Container -->
    <main class="flex-1 p-6 overflow-y-auto bg-jm-light-gray dark:bg-jm-navy/50">
