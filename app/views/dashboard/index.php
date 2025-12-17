<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
<?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Halo, <?= htmlspecialchars($data['user_name']); ?>! 👋</h2>
        <p class="text-gray-500 mt-1">Ringkasan produktivitasmu hari ini.</p>
    </div>
    <button onclick="openTaskModal()" class="w-full sm:w-auto bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition shadow-sm flex items-center justify-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        <span>Buat Tugas Baru</span>
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-500 text-sm font-medium">Tugas Tertunda</h3>
            <div class="p-2 bg-yellow-100 rounded-lg text-yellow-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800"><?= $data['stats']['pending']; ?></p>
    </div>
    
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-500 text-sm font-medium">Tugas Selesai</h3>
            <div class="p-2 bg-green-100 rounded-lg text-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800"><?= $data['stats']['done']; ?></p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-500 text-sm font-medium">Total Catatan</h3>
            <div class="p-2 bg-purple-100 rounded-lg text-purple-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800"><?= $data['stats']['notes']; ?></p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden h-full flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-bold text-gray-800">🔥 Deadline Terdekat</h3>
                <a href="<?= base_url('todos') ?>" class="text-blue-600 text-sm font-medium hover:underline">Lihat Semua</a>
            </div>
            
            <?php if (empty($data['upcoming_todos'])): ?>
                <div class="flex flex-col items-center justify-center py-12 text-center flex-1">
                    <div class="text-4xl mb-2 grayscale opacity-50">🎉</div>
                    <p class="text-gray-500 text-sm">Semua aman! Tidak ada deadline dekat.</p>
                </div>
            <?php else: ?>
                <ul class="divide-y divide-gray-100">
                    <?php foreach ($data['upcoming_todos'] as $todo): ?>
                        <?php
                            $dueDate = new DateTime($todo['due_date']);
                            $today = new DateTime();
                            $diff = $today->diff($dueDate)->format("%r%a"); 
                            $daysLeft = intval($diff);

                            if ($daysLeft < 3) {
                                $badgeClass = "bg-red-100 text-red-600";
                                $badgeText = "Mendesak (" . date('d M', strtotime($todo['due_date'])) . ")";
                            } elseif ($daysLeft <= 7) {
                                $badgeClass = "bg-yellow-100 text-yellow-700";
                                $badgeText = "Minggu Ini (" . date('d M', strtotime($todo['due_date'])) . ")";
                            } else {
                                $badgeClass = "bg-green-100 text-green-600";
                                $badgeText = "Aman (" . date('d M', strtotime($todo['due_date'])) . ")";
                            }
                        ?>
                        <li class="px-6 py-4 hover:bg-blue-50/50 transition flex items-center cursor-pointer group"
                            onclick='openTaskModal(<?= json_encode($todo) ?>)'>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2">
                                    <p class="font-semibold text-gray-800 truncate group-hover:text-blue-600 transition"><?= htmlspecialchars($todo['title']); ?></p>
                                    <?php if($todo['tags_info']): ?>
                                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-100 text-gray-500">🏷️</span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-1">
                                    <?= htmlspecialchars($todo['description'] ?? 'Tidak ada deskripsi tambahan.') ?>
                                </p>
                            </div>
                            <span class="text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wide whitespace-nowrap ml-3 <?= $badgeClass ?>">
                                <?= $badgeText ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    </div>

    <div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 h-full flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h3 class="font-bold text-gray-800">📌 Catatan Penting</h3>
            </div>
            <div class="p-4 space-y-3 flex-1">
                <?php if (empty($data['pinned_notes'])): ?>
                    <div class="text-center py-8 text-gray-400 text-sm italic">
                        Belum ada catatan yang di-pin.
                    </div>
                <?php else: ?>
                    <?php foreach ($data['pinned_notes'] as $note): ?>
                        <div onclick='openViewNoteModal(<?= json_encode($note) ?>)' class="bg-yellow-50 p-4 rounded-lg border border-yellow-100 relative group hover:shadow-md hover:-translate-y-0.5 transition cursor-pointer">
                            <h4 class="font-bold text-gray-800 text-sm mb-1 pr-4 truncate"><?= htmlspecialchars($note['title']); ?></h4>
                            <p class="text-xs text-gray-600 line-clamp-3 leading-relaxed">
                                <?= htmlspecialchars(strip_tags($note['content'])); ?>
                            </p>
                            <div class="mt-2 flex justify-end">
                                <span class="text-[10px] text-yellow-600 font-medium bg-yellow-100 px-2 py-0.5 rounded-full">Baca</span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="p-4 border-t border-gray-100">
                <a href="<?= base_url('notes') ?>" class="block text-center text-sm text-blue-600 font-medium hover:bg-blue-50 py-2 rounded-lg transition border border-dashed border-blue-200">
                    Buka Semua Catatan
                </a>
            </div>
        </div>
    </div>
</div>

<div class="mt-8">
    <button onclick="toggleActivityLog()" id="btnToggleLog" class="flex items-center gap-2 text-gray-500 hover:text-blue-600 transition text-sm font-medium mx-auto mb-4 bg-white px-4 py-2 rounded-full border border-gray-200 shadow-sm hover:shadow-md">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        <span>Lihat Riwayat Aktivitas</span>
    </button>

    <div id="activityLogSection" class="hidden bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden transition-all duration-500 ease-in-out">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="font-bold text-gray-800">⚡ Aktivitas Terakhir</h3>
            <button onclick="toggleActivityLog()" class="text-xs text-gray-400 hover:text-red-500">Tutup</button>
        </div>
        
        <?php if (empty($data['recent_activities'])): ?>
            <div class="p-6 text-center text-gray-400 text-sm italic">
                Belum ada aktivitas tercatat.
            </div>
        <?php else: ?>
            <ul class="divide-y divide-gray-100">
                <?php foreach ($data['recent_activities'] as $log): ?>
                    <li class="px-6 py-3 hover:bg-gray-50 transition flex items-start gap-3">
                        <div class="mt-1">
                            <?php 
                                if (strpos($log['action_type'], 'create') !== false) echo '✨';
                                elseif (strpos($log['action_type'], 'delete') !== false) echo '🗑️';
                                elseif (strpos($log['action_type'], 'complete') !== false || strpos($log['action_type'], 'checkin') !== false || strpos($log['action_type'], 'toggle') !== false) echo '✅';
                                else echo '📝';
                            ?>
                        </div>
                        <div>
                            <p class="text-sm text-gray-800 font-medium"><?= htmlspecialchars($log['description']) ?></p>
                            <p class="text-xs text-gray-400">
                                <?php 
                                    $time = strtotime($log['created_at']);
                                    echo date('d M H:i', $time);
                                ?>
                            </p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>

<div id="viewNoteModal" class="hidden fixed inset-0 z-[60] overflow-y-auto">
    <div class="fixed inset-0 bg-black/40 backdrop-blur-md transition-opacity" onclick="closeViewNoteModal()"></div>
    
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative w-full max-w-2xl bg-white/90 backdrop-blur-xl rounded-2xl shadow-2xl border border-white/20 transform transition-all scale-100">
            
            <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-start">
                <div>
                    <h3 id="viewNoteTitle" class="text-2xl font-bold text-gray-800 tracking-tight">Judul Catatan</h3>
                    <p class="text-xs text-gray-500 mt-1 uppercase tracking-wider font-semibold">Mode Baca</p>
                </div>
                <button onclick="closeViewNoteModal()" class="bg-gray-100 hover:bg-gray-200 text-gray-500 rounded-full p-2 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="px-8 py-8 overflow-y-auto max-h-[60vh]">
                <div id="viewNoteContent" class="prose prose-blue max-w-none text-gray-700 leading-relaxed whitespace-pre-line">
                    Isi catatan akan muncul di sini...
                </div>
            </div>

            <div class="px-8 py-5 bg-gray-50/50 border-t border-gray-100 rounded-b-2xl flex justify-between items-center">
                <span class="text-xs text-gray-400 italic">Klik tombol edit untuk mengubah isi.</span>
                <a href="<?= base_url('notes') ?>" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow-lg shadow-blue-200 transition">
                    Buka Editor Penuh
                </a>
            </div>
        </div>
    </div>
</div>

<div id="taskModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-black/30 backdrop-blur-md transition-all duration-300" onclick="closeTaskModal()"></div>
    <div class="flex min-h-full items-center justify-center p-2 sm:p-4">
        <div class="relative bg-white w-full max-w-2xl rounded-2xl shadow-2xl flex flex-col max-h-[90vh]">
            <form id="taskForm" action="<?= base_url('todos/store') ?>" method="POST" class="flex flex-col h-full">
                <input type="hidden" name="id" id="task_id">
                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start gap-4">
                    <div class="flex-1">
                        <input type="text" name="title" id="task_title" required class="w-full text-xl font-bold text-gray-800 border-none p-0 focus:ring-0 placeholder-gray-300 transition-colors rounded-md" placeholder="Apa yang ingin dikerjakan?">
                    </div>
                    <button type="button" onclick="closeTaskModal()" class="text-gray-400 hover:text-gray-600 p-1 bg-gray-50 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                     <div class="flex items-center gap-4 text-sm">
                        <div class="w-1/3 sm:w-1/4 text-gray-500 font-medium flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Tenggat Waktu
                        </div>
                        <input type="date" name="due_date" id="task_due_date" required class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 disabled:bg-gray-50 disabled:text-gray-500">
                    </div>
                    <div class="space-y-2">
                        <div class="text-sm font-medium text-gray-500 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                            Deskripsi
                        </div>
                        <textarea name="description" id="task_description" rows="3" class="w-full rounded-xl border-gray-200 bg-gray-50 p-4 text-sm focus:bg-white focus:ring-blue-500 focus:border-blue-500 transition resize-none" placeholder="Tambahkan detail keterangan..."></textarea>
                    </div>
                    <div id="checklistSection" class="hidden space-y-3 pt-4 border-t border-gray-100">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">Checklist Langkah</h3>
                            <span id="checklistProgress" class="text-xs font-bold text-gray-400">0%</span>
                        </div>
                        <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                            <div id="checklistBar" class="h-full bg-blue-500 w-0 transition-all duration-300"></div>
                        </div>
                        <div id="subtaskList" class="space-y-1"></div>
                        <div class="flex gap-2 mt-2">
                            <input type="text" id="newSubtaskInput" class="flex-1 border-gray-200 rounded-lg text-sm bg-gray-50 focus:bg-white focus:ring-blue-500" placeholder="Tambah langkah...">
                            <button type="button" onclick="addSubtask()" class="bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg text-sm font-semibold hover:bg-blue-200">Tambah</button>
                        </div>
                    </div>
                    <div id="createModeWarning" class="hidden p-4 bg-blue-50 text-blue-700 text-sm rounded-lg flex items-center gap-2">
                        Simpan tugas terlebih dahulu untuk menambahkan checklist.
                    </div>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-2xl flex justify-between items-center">
                    <div id="deleteBtnContainer"></div>
                    <div class="flex gap-3">
                        <button type="button" id="btnCancel" onclick="closeTaskModal()" class="px-4 py-2 text-gray-600 font-medium hover:bg-gray-200 rounded-lg transition">Tutup</button>
                        <button type="submit" id="btnSubmit" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-200 transition transform hover:-translate-y-0.5">Simpan Tugas</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const BASE_URL = '<?= base_url() ?>';
    
    function toggleActivityLog() {
        const section = document.getElementById('activityLogSection');
        const btn = document.getElementById('btnToggleLog');
        
        if (section.classList.contains('hidden')) {
            section.classList.remove('hidden');
            btn.classList.add('hidden'); 
        } else {
            section.classList.add('hidden');
            btn.classList.remove('hidden'); 
        }
    }

    function openViewNoteModal(note) {
        document.getElementById('viewNoteTitle').innerText = note.title;
        document.getElementById('viewNoteContent').innerText = note.content;
        
        const modal = document.getElementById('viewNoteModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeViewNoteModal() {
        const modal = document.getElementById('viewNoteModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    const modal = document.getElementById('taskModal');
    const form = document.getElementById('taskForm');
    let isEditMode = false;

    function openTaskModal(todo = null) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        if (todo) {

            isEditMode = false; 
            form.action = `${BASE_URL}todos/update`; 
            populateTodoData(todo);
            showViewMode();
        } else {
            isEditMode = true; 
            form.reset();
            form.action = `${BASE_URL}todos/store`; 
            document.getElementById('task_id').value = '';
            document.getElementById('task_due_date').valueAsDate = new Date();
            document.getElementById('checklistSection').classList.add('hidden');
            document.getElementById('createModeWarning').classList.remove('hidden');
            document.getElementById('deleteBtnContainer').innerHTML = '';
            showEditMode(); 
        }
    }

    function populateTodoData(todo) {
        document.getElementById('task_id').value = todo.id;
        document.getElementById('task_title').value = todo.title;
        document.getElementById('task_description').value = todo.description || '';
        document.getElementById('task_due_date').value = todo.due_date;
        document.getElementById('checklistSection').classList.remove('hidden');
        document.getElementById('createModeWarning').classList.add('hidden');

        document.getElementById('deleteBtnContainer').innerHTML = `
        <button type="button" onclick="deleteTask(${todo.id})" class="text-red-500 hover:text-red-700 font-medium text-sm flex items-center gap-1">Hapus</button>`;
        loadSubtasks(todo.id);
    }

    function showViewMode() {
        isEditMode = false;
        document.getElementById('task_title').setAttribute('readonly', true);
        document.getElementById('task_description').setAttribute('readonly', true);
        document.getElementById('task_due_date').setAttribute('disabled', true);
        document.getElementById('task_title').classList.add('bg-transparent', 'cursor-default');
        document.getElementById('task_description').classList.add('bg-transparent', 'cursor-default');
        document.getElementById('newSubtaskInput').parentElement.classList.add('hidden');
        
        const btnCancel = document.getElementById('btnCancel');
        btnCancel.innerHTML = '💾 Simpan'; 
        btnCancel.className = 'px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-200 transition';
        btnCancel.onclick = function() { location.reload(); }; 

        const btnSubmit = document.getElementById('btnSubmit');
        if (btnSubmit) {
            btnSubmit.outerHTML = `<button type="button" id="btnSubmit" onclick="switchToEditMode()" class="px-4 py-2 bg-white text-blue-600 font-bold border border-blue-200 rounded-lg hover:bg-blue-50 transition">✏️ Edit</button>`;
        }
    }

    function showEditMode() {
        isEditMode = true;
        document.getElementById('task_title').removeAttribute('readonly');
        document.getElementById('task_description').removeAttribute('readonly');
        document.getElementById('task_due_date').removeAttribute('disabled');
        document.getElementById('task_title').classList.remove('bg-transparent', 'cursor-default');
        document.getElementById('task_description').classList.remove('bg-transparent', 'cursor-default');
        document.getElementById('newSubtaskInput').parentElement.classList.remove('hidden');

        const btnCancel = document.getElementById('btnCancel');
        btnCancel.innerHTML = 'Batal';
        btnCancel.className = 'px-4 py-2 text-gray-600 font-medium hover:bg-gray-200 rounded-lg transition';
        btnCancel.onclick = closeTaskModal; 

        const btnSubmit = document.getElementById('btnSubmit');
        if (btnSubmit) {
            btnSubmit.outerHTML = `<button type="submit" id="btnSubmit" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-200 transition transform hover:-translate-y-0.5">💾 Simpan Tugas</button>`;
        }
    }

    function switchToEditMode() { showEditMode(); }
    function closeTaskModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // --- SUBTASK AJAX ---
    function deleteTask(id) {
        if (!confirm('Hapus tugas ini?')) return;
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `${BASE_URL}todos/delete`;
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id';
        input.value = id;
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }

    function loadSubtasks(todoId) {
        const list = document.getElementById('subtaskList');
        list.innerHTML = '<div class="text-gray-400 text-sm animate-pulse">Memuat langkah...</div>';
        fetch(`${BASE_URL}subtasks/list?todo_id=${todoId}`)
            .then(res => res.json())
            .then(res => { if (res.status === 'success') renderSubtasks(res.data); });
    }

    function renderSubtasks(data) {
        const list = document.getElementById('subtaskList');
        list.innerHTML = '';
        let completed = 0;
        data.forEach(item => {
            if (item.is_done == 1) completed++;
            const isDone = item.is_done == 1;
            list.innerHTML += `
                <div class="flex items-center gap-3 group">
                    <div onclick="toggleSubtask(${item.id})" class="cursor-pointer w-5 h-5 rounded border ${isDone ? 'bg-blue-500 border-blue-500 text-white' : 'border-gray-300 bg-white'} flex items-center justify-center transition">
                        ${isDone ? '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>' : ''}
                    </div>
                    <span class="flex-1 text-sm ${isDone ? 'text-gray-400 line-through' : 'text-gray-700'}">${item.title}</span>
                    <button type="button" onclick="deleteSubtask(${item.id})" class="text-gray-300 hover:text-red-500 opacity-0 group-hover:opacity-100 transition">&times;</button>
                </div>`;
        });
        const percent = data.length > 0 ? Math.round((completed / data.length) * 100) : 0;
        document.getElementById('checklistBar').style.width = `${percent}%`;
        document.getElementById('checklistProgress').innerText = `${percent}%`;
    }

    function addSubtask() {
        const todoId = document.getElementById('task_id').value;
        const input = document.getElementById('newSubtaskInput');
        const title = input.value.trim();
        if (!title) return;
        const formData = new FormData();
        formData.append('todo_id', todoId);
        formData.append('title', title);
        fetch(`${BASE_URL}subtasks/store`, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(res => { if (res.status === 'success') { input.value = ''; loadSubtasks(todoId); } });
    }

    function toggleSubtask(id) {
        const formData = new FormData();
        formData.append('id', id);
        fetch(`${BASE_URL}subtasks/toggle`, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(res => { if (res.status === 'success') loadSubtasks(document.getElementById('task_id').value); });
    }

    function deleteSubtask(id) {
        const formData = new FormData();
        formData.append('id', id);
        fetch(`${BASE_URL}subtasks/delete`, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(res => { if (res.status === 'success') loadSubtasks(document.getElementById('task_id').value); });
    }

    document.getElementById('newSubtaskInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') { e.preventDefault(); addSubtask(); }
    });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>