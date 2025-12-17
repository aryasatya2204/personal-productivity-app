</main>
</div>

<div id="tagManagerModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-black/30 backdrop-blur-md transition-all duration-300" onclick="toggleModal('tagManagerModal', false)"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white w-full max-w-md rounded-2xl shadow-2xl flex flex-col max-h-[85vh]">
            
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-2xl">
                <h3 class="text-lg font-bold text-gray-800">Kelola Label</h3>
                <button onclick="toggleModal('tagManagerModal', false)" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="p-6 overflow-y-auto flex-1 bg-white" id="tagListContainer">
                <div class="text-center py-4 text-gray-400 text-sm">Memuat label...</div>
            </div>

            <div class="p-4 bg-gray-50 border-t border-gray-100 rounded-b-2xl">
                <form id="addTagForm" class="flex gap-2 items-center">
                    <input type="color" id="newTagColor" value="#3B82F6" class="h-10 w-10 p-0 border-0 rounded-lg cursor-pointer bg-transparent" title="Pilih Warna">
                    <input type="text" id="newTagName" class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5" placeholder="Nama Label Baru..." required>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 text-sm font-medium shadow-sm whitespace-nowrap">
                        + Tambah
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>

    function confirmSubmit(event, message = 'Data yang dihapus tidak dapat dikembalikan!') {
        event.preventDefault(); 
        const form = event.target; 

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#2563EB', 
            cancelButtonColor: '#9CA3AF',  
            confirmButtonText: 'Ya, Lanjutkan!',
            cancelButtonText: 'Batal',
            background: document.documentElement.classList.contains('dark') ? '#1e293b' : '#fff',
            color: document.documentElement.classList.contains('dark') ? '#e2e8f0' : '#374151'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); 
            }
        });
    }
    
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const isClosed = sidebar.classList.contains('-translate-x-full');
        
        if (isClosed) {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
            setTimeout(() => overlay.classList.remove('opacity-0'), 10);
        } else {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('opacity-0');
            setTimeout(() => overlay.classList.add('hidden'), 300);
        }
    }

    function toggleModal(modalID, show) {
        const modal = document.getElementById(modalID);
        if(show) {
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; 
        } else {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto'; 
        }
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('bg-opacity-75')) {
            event.target.closest('div[id$="Modal"]').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }

    if (typeof BASE_URL === 'undefined') {
        window.BASE_URL = window.location.origin + window.location.pathname.substring(0, window.location.pathname.indexOf('public/') + 7);
    }

    function openTagManager() {
        toggleModal('tagManagerModal', true);
        loadTags();
    }

    function loadTags() {
        const container = document.getElementById('tagListContainer');
        
        const url = (typeof BASE_URL !== 'undefined' ? BASE_URL : window.BASE_URL);

        fetch(`${url}tags/list`)
            .then(res => res.json())
            .then(res => {
                if(res.status === 'success') {
                    renderTags(res.data);
                } else {
                    container.innerHTML = '<p class="text-red-500 text-center text-sm">Gagal memuat label.</p>';
                }
            })
            .catch(err => {
                console.error(err);
                container.innerHTML = '<p class="text-red-500 text-center text-sm">Terjadi kesalahan koneksi.</p>';
            });
    }

    function renderTags(tags) {
        const container = document.getElementById('tagListContainer');
        container.innerHTML = '';

        if(tags.length === 0) {
            container.innerHTML = `
                <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                    <span class="text-3xl mb-2">🏷️</span>
                    <p class="text-sm">Belum ada label.</p>
                </div>`;
            return;
        }

        tags.forEach(tag => {
            const html = `
                <div class="flex items-center justify-between group py-3 border-b border-gray-50 last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 rounded-full shadow-sm" style="background-color: ${tag.color_hex};"></div>
                        <span class="text-sm font-medium text-gray-700">${tag.name}</span>
                    </div>
                    <button onclick="deleteTag(${tag.id})" class="text-gray-300 hover:text-red-500 transition p-1 hover:bg-red-50 rounded">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            `;
            container.innerHTML += html;
        });
    }

    document.getElementById('addTagForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const nameInput = document.getElementById('newTagName');
        const colorInput = document.getElementById('newTagColor');
        const url = (typeof BASE_URL !== 'undefined' ? BASE_URL : window.BASE_URL);
        
        const formData = new FormData();
        formData.append('name', nameInput.value);
        formData.append('color', colorInput.value);

        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerText;
        submitBtn.innerText = '...';
        submitBtn.disabled = true;

        fetch(`${url}tags/store`, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(res => {
                if(res.status === 'success') {
                    nameInput.value = '';
                    loadTags(); 
                } else {
                    alert('Gagal menambah label');
                }
            })
            .finally(() => {
                submitBtn.innerText = originalText;
                submitBtn.disabled = false;
            });
    });

    function deleteTag(id) {
        Swal.fire({
            title: 'Hapus Label?',
            text: "Label akan terlepas dari semua tugas/catatan.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444', 
            cancelButtonColor: '#9CA3AF',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const url = (typeof BASE_URL !== 'undefined' ? BASE_URL : window.BASE_URL);
                const formData = new FormData();
                formData.append('id', id);

                fetch(`${url}tags/delete`, { method: 'POST', body: formData })
                    .then(res => res.json())
                    .then(res => {
                        if(res.status === 'success') {
                            loadTags();
                            const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 });
                            Toast.fire({ icon: 'success', title: 'Label dihapus' });
                        } else {
                            Swal.fire('Error', 'Gagal menghapus label', 'error');
                        }
                    });
            }
        });
    }
</script>
</body>
</html>