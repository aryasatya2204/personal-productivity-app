<div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-20 hidden md:hidden transition-all duration-300 opacity-0"></div>

<aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 bg-white border-r border-gray-200 transform -translate-x-full transition-transform duration-300 md:relative md:translate-x-0 md:flex flex-col h-full shadow-xl md:shadow-none">
    
    <div class="h-16 flex items-center px-6 border-b border-gray-100 bg-white">
        <span class="text-xl font-bold text-blue-600 tracking-tight">Productivity App</span>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
        <?php 
        $uri = $_SERVER['REQUEST_URI'];
        $isHome = strpos($uri, 'todos') === false && strpos($uri, 'notes') === false && strpos($uri, 'habits') === false && strpos($uri, 'focus') === false;
        ?>

        <a href="<?= base_url() ?>" class="<?= $isHome ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' ?> flex items-center px-4 py-3 rounded-lg font-medium transition-colors">
            <span class="mr-3">🏠</span> Dashboard
        </a>
        <a href="<?= base_url('todos') ?>" class="<?= strpos($uri, 'todos') !== false ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' ?> flex items-center px-4 py-3 rounded-lg font-medium transition-colors">
            <span class="mr-3">✅</span> Tugas Saya
        </a>
        <a href="<?= base_url('notes') ?>" class="<?= strpos($uri, 'notes') !== false ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' ?> flex items-center px-4 py-3 rounded-lg font-medium transition-colors">
            <span class="mr-3">📁</span> Catatan & File
        </a>
        <a href="<?= base_url('habits') ?>" class="<?= strpos($uri, 'habits') !== false ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' ?> flex items-center px-4 py-3 rounded-lg font-medium transition-colors">
            <span class="mr-3">🔥</span> Kebiasaan
        </a>
        <a href="<?= base_url('focus') ?>" class="<?= strpos($uri, 'focus') !== false ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-gray-50' ?> flex items-center px-4 py-3 rounded-lg font-medium transition-colors">
            <span class="mr-3">⏱️</span> Mode Fokus
        </a>
        
        <div class="pt-4 pb-2">
            <div class="border-t border-gray-100"></div>
        </div>

        <button onclick="openTagManager()" class="w-full flex items-center px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-lg font-medium transition-colors text-left">
            <span class="mr-3">🏷️</span> Kelola Label
        </button>
    </nav>

    <div class="p-4 border-t border-gray-100 bg-gray-50 md:bg-white">
        <a href="<?= base_url('logout') ?>" class="flex items-center px-4 py-2 text-red-600 hover:bg-red-50 rounded-lg font-medium w-full transition-colors">
            <span class="mr-3">🚪</span> Keluar
        </a>
    </div>
</aside>