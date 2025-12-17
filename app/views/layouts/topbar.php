<div class="flex-1 flex flex-col overflow-hidden relative">
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 z-20 sticky top-0">
        
        <div class="flex items-center gap-3 md:hidden">
            <button onclick="toggleSidebar()" class="text-gray-500 hover:text-blue-600 p-2 -ml-2 rounded-md hover:bg-gray-100 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
        </div>

        <div class="flex-1 max-w-lg mx-4 relative group">
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" id="globalSearchInput" 
                    class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-full leading-5 bg-gray-50 text-gray-900 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition sm:text-sm" 
                    placeholder="Cari tugas atau catatan..."
                    autocomplete="off">
                
                <div id="searchSpinner" class="absolute inset-y-0 right-0 pr-3 flex items-center hidden">
                    <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>
            </div>

            <div id="searchResults" class="hidden absolute top-full left-0 w-full mt-2 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden z-50">
                </div>
        </div>

        <a href="<?= base_url('profile') ?>" class="flex items-center gap-3 group cursor-pointer pl-4">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-semibold text-gray-800 group-hover:text-blue-600 transition">
                    <?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?>
                </p>
                </div>
            
            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-md ring-2 ring-white group-hover:ring-blue-100 transition overflow-hidden relative">
                <?php if (!empty($_SESSION['user_avatar'])): ?>
                    <img src="<?= base_url('uploads/' . $_SESSION['user_avatar']) ?>" alt="Avatar" class="w-full h-full object-cover">
                <?php else: ?>
                    <?= substr($_SESSION['user_name'] ?? 'U', 0, 1) ?>
                <?php endif; ?>
            </div>
        </a>
    </header>

    <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-4 sm:p-6 pb-20 sm:pb-6">

<script>
    const searchInput = document.getElementById('globalSearchInput');
    const searchResults = document.getElementById('searchResults');
    const searchSpinner = document.getElementById('searchSpinner');
    let searchTimeout;

    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
        }
    });

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();

        if (query.length < 2) {
            searchResults.classList.add('hidden');
            return;
        }

        searchSpinner.classList.remove('hidden');

        searchTimeout = setTimeout(() => {
            fetchSearchResults(query);
        }, 300);
    });

    function fetchSearchResults(query) {
        fetch(`<?= base_url('search') ?>?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(res => {
                searchSpinner.classList.add('hidden');
                if (res.status === 'success') {
                    renderSearchResults(res.data);
                }
            })
            .catch(() => {
                searchSpinner.classList.add('hidden');
            });
    }

    function renderSearchResults(data) {
        searchResults.innerHTML = '';
        
        if (data.length === 0) {
            searchResults.innerHTML = `
                <div class="p-4 text-center text-gray-500 text-sm">
                    Tidak ditemukan hasil untuk "${searchInput.value}"
                </div>
            `;
        } else {
            searchResults.innerHTML += `<div class="bg-gray-50 px-4 py-2 text-xs font-bold text-gray-400 uppercase tracking-wider">Hasil Pencarian</div>`;
            
            data.forEach(item => {
                const isTodo = item.type === 'todo';
                const icon = isTodo ? '✅' : '📝';
                const link = isTodo ? '<?= base_url('todos') ?>' : '<?= base_url('notes') ?>';
                const meta = isTodo ? `Tenggat: ${item.meta}` : `Dibuat: ${item.meta}`;
                
                const html = `
                    <a href="${link}" class="block px-4 py-3 hover:bg-blue-50 transition border-b border-gray-50 last:border-0">
                        <div class="flex items-center gap-3">
                            <span class="text-lg">${icon}</span>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">${item.title}</p>
                                <p class="text-xs text-gray-500">${meta}</p>
                            </div>
                        </div>
                    </a>
                `;
                searchResults.innerHTML += html;
            });
        }
        
        searchResults.classList.remove('hidden');
    }

    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('hidden');
        }
    });
</script>