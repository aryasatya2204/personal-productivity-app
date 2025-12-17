<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
<?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div class="flex items-center gap-3">
        <?php if ($data['current_folder']): ?>
            <a href="<?= base_url('notes') ?>" class="p-2 rounded-full bg-white border border-gray-200 text-gray-500 hover:bg-gray-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    📁 <?= htmlspecialchars($data['current_folder']['name']) ?>
                </h2>
                <p class="text-sm text-gray-500">Dalam folder</p>
            </div>
        <?php else: ?>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">File Manager</h2>
                <p class="text-sm text-gray-500">Kelola folder dan catatanmu.</p>
            </div>
        <?php endif; ?>
    </div>

    <div class="flex gap-2 w-full sm:w-auto">
        <?php if (!$data['current_folder']): ?>
            <button onclick="toggleModal('addFolderModal', true)" class="flex-1 sm:flex-none bg-white border border-gray-300 text-gray-700 px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 transition shadow-sm flex items-center justify-center gap-2">
                <span>+ Folder</span>
            </button>
        <?php endif; ?>

        <button onclick="toggleModal('addNoteModal', true)" class="flex-1 sm:flex-none bg-blue-600 text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition shadow-sm flex items-center justify-center gap-2">
            <span>+ Catatan</span>
        </button>
    </div>
</div>

<?php if (!$data['current_folder']): ?>
    <?php if (!empty($data['folders'])): ?>
        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-3">Folders</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mb-8">
            <?php foreach ($data['folders'] as $folder): ?>
                <div class="relative group">
                    <a href="<?= base_url('notes?folder=' . $folder['id']) ?>" class="block bg-white p-4 rounded-xl border border-gray-200 hover:border-blue-400 hover:shadow-md transition flex flex-col items-center text-center h-full">
                        <div class="text-4xl mb-2 text-blue-100 group-hover:text-blue-200 transition">📁</div>
                        <span class="font-medium text-gray-700 text-sm truncate w-full" title="<?= htmlspecialchars($folder['name']) ?>">
                            <?= htmlspecialchars($folder['name']) ?>
                        </span>
                    </a>

                    <form action="<?= base_url('notes/folders/delete') ?>" method="POST" onsubmit="confirmSubmit(event, 'Hapus folder ini? Catatan di dalamnya akan menjadi tanpa folder.')" class="absolute top-2 right-2 z-10">
    <input type="hidden" name="id" value="<?= $folder['id'] ?>">
    <button type="submit" class="w-7 h-7 flex items-center justify-center rounded-full bg-red-500/90 text-white hover:bg-red-600 dark:bg-red-600/90 dark:hover:bg-red-700 transition-all duration-200 shadow-lg backdrop-blur-sm" title="Hapus Folder">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
</form>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
<?php endif; ?>

<h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-3">
    <?= $data['current_folder'] ? 'Catatan di sini' : 'Catatan Tanpa Folder' ?>
</h3>

<?php if (empty($data['notes'])): ?>
    <div class="flex flex-col items-center justify-center bg-white rounded-xl border border-dashed border-gray-300 p-8 text-center">
        <div class="text-3xl mb-2 grayscale opacity-30">📝</div>
        <p class="text-gray-400 text-sm">Folder ini kosong / belum ada catatan.</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($data['notes'] as $note): ?>
            <div class="rounded-xl shadow-sm border p-5 flex flex-col justify-between h-[200px] transition hover:shadow-md relative group bg-white 
                <?= $note['is_pinned'] ? 'border-yellow-300 bg-yellow-50/30' : 'border-gray-200' ?>">

                <button onclick='openEditModal(<?= json_encode($note); ?>)' class="absolute top-3 right-10 text-gray-400 hover:text-blue-600 p-1 z-10 md:opacity-0 md:group-hover:opacity-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                </button>

                <form action="<?= base_url('notes/pin') ?>" method="POST" class="absolute top-3 right-3 z-10">
                    <input type="hidden" name="id" value="<?= $note['id'] ?>">
                    <button type="submit" class="text-gray-300 hover:text-yellow-500 transition">
                        <svg class="w-5 h-5 <?= $note['is_pinned'] ? 'text-yellow-500 fill-current' : '' ?>" stroke="currentColor" fill="<?= $note['is_pinned'] ? 'currentColor' : 'none' ?>" viewBox="0 0 24 24">
                            <?php if ($note['is_pinned']): ?>
                                <path d="M10 2a1 1 0 00-1 1v1.732L5.8 7.268A1 1 0 015 8v2h4v7l1 2 1-2v-7h4V8a1 1 0 01-.8-.732L11 4.732V3a1 1 0 00-1-1z" />
                            <?php else: ?>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                            <?php endif; ?>
                        </svg>
                    </button>
                </form>

                <div class="cursor-pointer" onclick='openEditModal(<?= json_encode($note); ?>)'>
                    <h3 class="font-bold text-lg text-gray-800 line-clamp-1 pr-16 mb-2">
                        <?= htmlspecialchars($note['title']) ?>
                    </h3>
                    <div class="text-gray-600 text-sm overflow-hidden line-clamp-4 leading-relaxed">
                        <?= nl2br(htmlspecialchars($note['content'])) ?>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-auto pt-3 border-t border-gray-100">
                    <span class="text-xs text-gray-400"><?= date('d M', strtotime($note['created_at'])) ?></span>
                    <form action="<?= base_url('notes/delete') ?>" method="POST" onsubmit="confirmSubmit(event, 'Catatan ini akan dihapus permanen.')">
                        <input type="hidden" name="id" value="<?= $note['id'] ?>">
                        <button type="submit" class="text-red-300 hover:text-red-600 text-xs">Hapus</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div id="addNoteModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-black/30 backdrop-blur-md transition-all duration-300" onclick="toggleModal('addNoteModal', false)"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative rounded-xl bg-white shadow-2xl w-full max-w-lg">
            <form action="<?= base_url('notes/store') ?>" method="POST">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Buat Catatan</h3>
                    <div class="space-y-4">
                        <input type="text" name="title" required placeholder="Judul Catatan" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2.5">

                        <select name="folder_id" class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 border p-2.5 bg-white">
                            <option value="">-- Tanpa Folder --</option>
                            <?php foreach ($data['folders'] as $f): ?>
                                <option value="<?= $f['id'] ?>" <?= ($data['current_folder'] && $data['current_folder']['id'] == $f['id']) ? 'selected' : '' ?>>
                                    📁 <?= htmlspecialchars($f['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <textarea name="content" rows="6" required placeholder="Tulis sesuatu..." class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-blue-500 border p-2.5 resize-none"></textarea>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3 flex justify-end gap-2 rounded-b-xl">
                    <button type="button" onclick="toggleModal('addNoteModal', false)" class="text-gray-600 px-4 py-2 hover:bg-gray-100 rounded-lg">Batal</button>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editNoteModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-black/30 backdrop-blur-md transition-all duration-300" onclick="toggleModal('editNoteModal', false)"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative rounded-xl bg-white shadow-2xl w-full max-w-lg">
            <form action="<?= base_url('notes/update') ?>" method="POST">
                <input type="hidden" name="id" id="edit_id">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Edit / Pindah Folder</h3>
                    <div class="space-y-4">
                        <input type="text" name="title" id="edit_title" required class="block w-full rounded-lg border-gray-300 border p-2.5 font-bold text-lg">

                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase">Lokasi Folder</label>
                            <select name="folder_id" id="edit_folder_id" class="mt-1 block w-full rounded-lg border-gray-300 border p-2.5 bg-gray-50">
                                <option value="">-- Tanpa Folder (Root) --</option>
                                <?php foreach ($data['folders'] as $f): ?>
                                    <option value="<?= $f['id'] ?>">📁 <?= htmlspecialchars($f['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <textarea name="content" id="edit_content" rows="8" required class="block w-full rounded-lg border-gray-300 border p-2.5 resize-none"></textarea>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-3 flex justify-end gap-2 rounded-b-xl">
                    <button type="button" onclick="toggleModal('editNoteModal', false)" class="text-gray-600 px-4 py-2 hover:bg-gray-100 rounded-lg">Batal</button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="addFolderModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-black/30 backdrop-blur-md transition-all duration-300" onclick="toggleModal('addFolderModal', false)"></div>
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative rounded-xl bg-white shadow-2xl w-full max-w-sm">
            <form action="<?= base_url('notes/folders/store') ?>" method="POST">
                <div class="p-6 text-center">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Folder Baru</h3>
                    <input type="text" name="name" required class="w-full border rounded-lg p-2.5 text-center focus:ring-blue-500" placeholder="Nama Folder...">
                </div>
                <div class="bg-gray-50 px-6 py-3 flex gap-2 rounded-b-xl">
                    <button type="button" onclick="toggleModal('addFolderModal', false)" class="flex-1 bg-white border text-gray-700 py-2 rounded-lg hover:bg-gray-50">Batal</button>
                    <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Buat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEditModal(note) {
        document.getElementById('edit_id').value = note.id;
        document.getElementById('edit_title').value = note.title;
        document.getElementById('edit_content').value = note.content;
        document.getElementById('edit_folder_id').value = note.folder_id || "";
        toggleModal('editNoteModal', true);
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>