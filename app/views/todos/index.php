<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
<?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Daftar Tugas</h2>
        <p class="text-sm text-gray-500">Klik pada tugas untuk melihat detail & checklist.</p>
    </div>
    <button onclick="openTaskModal()" class="w-full sm:w-auto bg-blue-600 text-white px-5 py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition shadow-sm flex items-center justify-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        <span>Tambah Tugas</span>
    </button>
</div>

<?php if (empty($data['todos'])): ?>
    <div class="flex flex-col items-center justify-center bg-white rounded-xl shadow-sm border border-dashed border-gray-300 p-12 text-center h-64">
        <div class="text-5xl mb-4 grayscale opacity-50">📝</div>
        <h3 class="text-lg font-medium text-gray-900">Belum ada tugas</h3>
        <p class="text-gray-500 text-sm mt-2">Hari ini masih kosong. Yuk, mulai produktif!</p>
        <button onclick="openTaskModal()" class="mt-4 text-blue-600 font-medium hover:underline text-sm">+ Buat Tugas Sekarang</button>
    </div>
<?php else: ?>
    <div class="space-y-3 pb-20">
        <?php foreach ($data['todos'] as $todo): ?>
            <?php 
                // LOGIKA OVERDUE (TERLEWAT)
                // Cek jika tanggal tugas < hari ini DAN belum selesai
                $isOverdue = (strtotime($todo['due_date']) < strtotime(date('Y-m-d'))) && !$todo['is_done'];
                
                // Set Style Berdasarkan Status
                if ($isOverdue) {
                    // Merah muda jika telat
                    $cardClass = "bg-red-50 border-red-200 hover:border-red-400";
                    $dateBadgeClass = "bg-red-100 text-red-700 font-bold border border-red-200";
                    $statusText = "TERLEWAT!";
                } else {
                    // Putih biasa jika aman
                    $cardClass = "bg-white border-gray-200 hover:border-blue-400";
                    $dateBadgeClass = "bg-blue-50 text-blue-700";
                    $statusText = "";
                }
            ?>

            <div class="<?= $cardClass ?> p-5 rounded-xl shadow-sm border hover:shadow-md transition group cursor-pointer relative"
                onclick='openTaskModal(<?= json_encode($todo) ?>)'>

                <div class="flex items-start gap-4">
                    <div onclick="event.stopPropagation()">
                        <form action="<?= base_url('todos/toggle') ?>" method="POST" class="pt-1">
                            <input type="hidden" name="id" value="<?= $todo['id'] ?>">
                            <input type="checkbox" onchange="this.form.submit()"
                                class="w-6 h-6 rounded-md border-gray-300 focus:ring-blue-500 cursor-pointer transition-all <?= $isOverdue ? 'text-red-600 focus:ring-red-500' : 'text-blue-600' ?>"
                                <?= $todo['is_done'] ? 'checked' : '' ?>>
                        </form>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-1">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <h3 class="font-semibold text-lg text-gray-800 <?= $todo['is_done'] ? 'line-through text-gray-400 decoration-2' : '' ?>">
                                    <?= htmlspecialchars($todo['title']) ?>
                                </h3>
                                
                                <?php if(!empty($todo['tags_info'])): ?>
                                    <div class="flex flex-wrap gap-1">
                                        <?php 
                                        $tags = explode('||', $todo['tags_info']);
                                        foreach($tags as $t):
                                            list($tid, $tname, $tcolor) = explode('~', $t);
                                        ?>
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold text-white shadow-sm" style="background-color: <?= $tcolor ?>;">
                                                <?= htmlspecialchars($tname) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <span class="text-xs font-medium px-2.5 py-1 rounded-md whitespace-nowrap ml-2 flex items-center gap-1 <?= $dateBadgeClass ?>">
                                <?php if($isOverdue): ?>
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <?php endif; ?>
                                <?= date('d M', strtotime($todo['due_date'])) ?>
                            </span>
                        </div>

                        <?php if (!empty($todo['description'])): ?>
                            <p class="text-sm text-gray-600 line-clamp-2 mb-3">
                                <?= htmlspecialchars($todo['description']) ?>
                            </p>
                        <?php endif; ?>

                        <?php if ($todo['total_subtasks'] > 0):
                            $percent = ($todo['completed_subtasks'] / $todo['total_subtasks']) * 100;
                            $barColor = $isOverdue ? 'bg-red-500' : 'bg-blue-500';
                        ?>
                            <div class="flex items-center gap-3">
                                <div class="flex-1 h-2 bg-gray-200/50 rounded-full overflow-hidden">
                                    <div class="h-full <?= $barColor ?> rounded-full transition-all duration-500" style="width: <?= $percent ?>%"></div>
                                </div>
                                <span class="text-xs text-gray-500 font-medium">
                                    <?= $todo['completed_subtasks'] ?>/<?= $todo['total_subtasks'] ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div id="taskModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="fixed inset-0 bg-black/30 backdrop-blur-md transition-all duration-300" onclick="closeTaskModal()"></div>
    <div class="flex min-h-full items-center justify-center p-2 sm:p-4">

        <div class="relative bg-white w-full max-w-2xl rounded-2xl shadow-2xl flex flex-col max-h-[90vh]">

            <form id="taskForm" action="<?= base_url('todos/store') ?>" method="POST" class="flex flex-col h-full">
                <input type="hidden" name="id" id="task_id">

                <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start gap-4">
                    <div class="flex-1">
                        <input type="text" name="title" id="task_title" required
                            class="w-full text-xl font-bold text-gray-800 border-none p-0 focus:ring-0 placeholder-gray-300 transition-colors rounded-md"
                            placeholder="Apa yang ingin dikerjakan?">
                    </div>
                    <button type="button" onclick="closeTaskModal()" class="text-gray-400 hover:text-gray-600 p-1 bg-gray-50 rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-6 space-y-6">

                    <div>
                        <div class="text-sm font-medium text-gray-500 flex items-center gap-2 mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            Label (Tag)
                        </div>
                        <div class="flex flex-wrap gap-2" id="tagsSelectionContainer">
    <?php if(!empty($data['available_tags'])): foreach($data['available_tags'] as $tag): ?>
        <label class="cursor-pointer select-none group">
            <input type="checkbox" 
                   name="tags[]" 
                   value="<?= $tag['id'] ?>" 
                   class="tag-checkbox hidden peer"
                   onchange="updateTagVisual(this)">
            
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold border transition-all 
                         bg-gray-50 text-gray-600 border-gray-200 opacity-60
                         group-hover:opacity-100 peer-checked:opacity-100 peer-checked:text-white peer-checked:shadow-sm"
                  data-color="<?= $tag['color_hex'] ?>"
                  style="border-color: transparent;"> <?= htmlspecialchars($tag['name']) ?>
            </span>
        </label>
    <?php endforeach; else: ?>
        <p class="text-xs text-gray-400 italic">Belum ada label.</p>
    <?php endif; ?>
</div>
                    </div>

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
                        <textarea name="description" id="task_description" rows="3"
                            class="w-full rounded-xl border-gray-200 bg-gray-50 p-4 text-sm focus:bg-white focus:ring-blue-500 focus:border-blue-500 transition resize-none"
                            placeholder="Tambahkan detail keterangan..."></textarea>
                    </div>

                    <div id="checklistSection" class="hidden space-y-3 pt-4 border-t border-gray-100">
                        <div class="flex justify-between items-center">
                            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                Checklist Langkah
                            </h3>
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
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Simpan tugas terlebih dahulu untuk menambahkan checklist.
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-2xl flex justify-between items-center">
                    <div id="deleteBtnContainer"></div>
                    <div class="flex gap-3">
                        <button type="button" id="btnCancel" onclick="closeTaskModal()" class="px-4 py-2 text-gray-600 font-medium hover:bg-gray-200 rounded-lg transition">Tutup</button>
                        <button type="submit" id="btnSubmit" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-200 transition transform hover:-translate-y-0.5">
                            Simpan Tugas
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Copy seluruh script lama dari index.php Todo sebelumnya, tidak ada perubahan logic JS yang diperlukan untuk styling.
    // Namun untuk memastikan, saya tulis ulang intinya agar Anda tinggal Copy-Paste file ini sepenuhnya.
    
    const BASE_URL = '<?= base_url() ?>';
    const modal = document.getElementById('taskModal');
    const form = document.getElementById('taskForm');
    let isEditMode = false;

    function openTaskModal(todo = null) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        document.querySelectorAll('.tag-checkbox').forEach(cb => {
        cb.checked = false;
        updateTagVisual(cb); 
    });

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

        if(todo.tags_info) {
        const tags = todo.tags_info.split('||');
        tags.forEach(t => {
            const [id, name, color] = t.split('~'); 
            const checkbox = document.querySelector(`.tag-checkbox[value="${id}"]`);
            if(checkbox) {
                checkbox.checked = true;
                updateTagVisual(checkbox); 
            }
        });
    }

        document.getElementById('checklistSection').classList.remove('hidden');
        document.getElementById('createModeWarning').classList.add('hidden');

        document.getElementById('deleteBtnContainer').innerHTML = `
        <button type="button" onclick="deleteTask(${todo.id})" class="text-red-500 hover:text-red-700 font-medium text-sm flex items-center gap-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            Hapus
        </button>`;

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
        document.querySelectorAll('.tag-checkbox').forEach(cb => cb.disabled = true);

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
        document.querySelectorAll('.tag-checkbox').forEach(cb => cb.disabled = false);

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

    function deleteTask(id) {
        Swal.fire({
            title: 'Hapus Tugas?',
            text: "Tugas ini akan dihapus permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#9CA3AF',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
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
        });
    }
  
  function updateTagVisual(checkbox) {
    const span = checkbox.nextElementSibling;
    const color = span.getAttribute('data-color');

    if (checkbox.checked) {
        // Jika dicentang: Pasang warna background dan border
        span.style.backgroundColor = color;
        span.style.borderColor = color;
    } else {
        // Jika tidak dicentang: Hapus warna (kembali ke style default CSS)
        span.style.backgroundColor = '';
        span.style.borderColor = ''; 
    }
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