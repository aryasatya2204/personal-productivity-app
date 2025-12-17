<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
<?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Pelacak Kebiasaan</h2>
        <p class="text-sm text-gray-500">Bangun konsistensi harianmu.</p>
    </div>
    <button onclick="toggleModal('addHabitModal', true)" class="w-full sm:w-auto bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition shadow-sm flex items-center justify-center gap-2">
        <span>+ Kebiasaan Baru</span>
    </button>
</div>

<?php if (empty($data['habits'])): ?>
    <div class="flex flex-col items-center justify-center bg-white rounded-xl shadow-sm border border-dashed border-gray-300 p-12 text-center h-64">
        <div class="text-5xl mb-4 grayscale opacity-50">🔥</div>
        <h3 class="text-lg font-medium text-gray-900">Mulai kebiasaan baik</h3>
        <p class="text-gray-500 text-sm mt-2">Belum ada kebiasaan yang dilacak.</p>
        <button onclick="toggleModal('addHabitModal', true)" class="mt-4 text-blue-600 font-medium hover:underline text-sm">+ Tambah Sekarang</button>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($data['habits'] as $habit): ?>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200 flex flex-col justify-between relative group hover:shadow-md transition">
                
                <form action="<?= base_url('habits/delete') ?>" method="POST" onsubmit="confirmSubmit(event, 'Hapus kebiasaan ini? Riwayat streak akan hilang.')" class="absolute top-3 right-3 z-10">
    <input type="hidden" name="id" value="<?= $habit['id'] ?>">
    <button type="submit" class="w-7 h-7 flex items-center justify-center rounded-full bg-red-500/90 text-white hover:bg-red-600 dark:bg-red-600/90 dark:hover:bg-red-700 transition-all duration-200 shadow-lg backdrop-blur-sm" title="Hapus Kebiasaan">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
</form>

                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <div class="flex items-center gap-1 bg-orange-50 text-orange-600 px-2 py-1 rounded-full text-xs font-bold border border-orange-100">
                            <span>🔥</span>
                            <span><?= $habit['current_streak'] ?> Hari</span>
                        </div>
                    </div>
                    <h3 class="font-bold text-lg text-gray-800"><?= htmlspecialchars($habit['title']) ?></h3>
                </div>

                <div class="mt-6">
                    <button onclick="toggleHabit(<?= $habit['id'] ?>, this)" 
                            class="w-full py-3 rounded-lg font-bold text-sm transition flex items-center justify-center gap-2 border 
                            <?= $habit['is_completed_today'] 
                                ? 'bg-green-50 text-green-600 border-green-200 hover:bg-green-100' 
                                : 'bg-gray-50 text-gray-500 border-gray-200 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200' ?>">
                        
                        <?php if ($habit['is_completed_today']): ?>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Selesai Hari Ini</span>
                        <?php else: ?>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            <span>Check-in</span>
                        <?php endif; ?>
                    </button>
                </div>

            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div id="addHabitModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-black/30 backdrop-blur-md transition-all duration-300" onclick="toggleModal('addHabitModal', false)"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white w-full max-w-sm rounded-2xl shadow-2xl">
            <form action="<?= base_url('habits/store') ?>" method="POST">
                <div class="p-6 text-center">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl">🔥</div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Kebiasaan Baru</h3>
                    <p class="text-gray-500 text-sm mb-4">Apa yang ingin kamu rutinkan?</p>
                    
                    <input type="text" name="title" required class="w-full border rounded-lg p-3 text-center focus:ring-blue-500 focus:border-blue-500 mb-2" placeholder="Contoh: Lari Pagi, Baca Buku...">
                </div>
                <div class="bg-gray-50 px-6 py-4 flex gap-2 rounded-b-2xl">
                    <button type="button" onclick="toggleModal('addHabitModal', false)" class="flex-1 bg-white border text-gray-700 py-2 rounded-lg font-medium hover:bg-gray-50">Batal</button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg font-medium hover:bg-blue-700">Mulai</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const BASE_URL = '<?= base_url() ?>';

    function toggleHabit(id, btn) {
        const originalContent = btn.innerHTML;
        const isCurrentlyDone = btn.classList.contains('bg-green-50');

        if (isCurrentlyDone) {
            Swal.fire({
                title: 'Batalkan Check-in?',
                text: "Status hari ini akan kembali kosong.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#9CA3AF',
                confirmButtonText: 'Ya, Batalkan',
                cancelButtonText: 'Tidak'
            }).then((result) => {
                if (result.isConfirmed) {
                    executeToggle(id, btn, originalContent);
                }
            });
            return;
        }
        
        executeToggle(id, btn, originalContent);
    }

    function executeToggle(id, btn, originalContent) {
        btn.innerHTML = '<span class="animate-pulse">Memproses...</span>';
        
        const formData = new FormData();
        formData.append('id', id);

        fetch(`${BASE_URL}habits/toggle`, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(res => {
                if(res.status === 'success') {
                    location.reload();
                } else {
                    btn.innerHTML = originalContent;
                    Swal.fire('Gagal', 'Gagal update status', 'error');
                }
            })
            .catch(() => {
                btn.innerHTML = originalContent;
                Swal.fire('Error', 'Terjadi kesalahan koneksi', 'error');
            });
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>