<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
<?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

<div class="flex flex-col h-[calc(100vh-100px)]">
    
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Mode Fokus</h2>
            <p class="text-sm text-gray-500">Matikan gangguan, mulailah bekerja.</p>
        </div>
        <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg font-medium text-sm border border-blue-100 shadow-sm">
            Total Hari Ini: <span id="todayTotal" class="font-bold text-xl"><?= $data['today_minutes'] ?></span> menit
        </div>
    </div>

    <div class="flex-1 flex flex-col items-center justify-center bg-white rounded-2xl shadow-sm border border-gray-200 p-8 relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-1.5 bg-gray-100">
            <div id="progressBar" class="h-full bg-blue-500 transition-all duration-1000 ease-linear" style="width: 0%"></div>
        </div>

        <div class="text-center mb-10">
            <div id="timerDisplay" class="text-[120px] leading-none font-mono font-bold text-gray-800 tracking-tighter tabular-nums select-none transition-colors duration-300">
                25:00
            </div>
            <p id="timerStatus" class="text-gray-400 mt-4 font-medium tracking-wide uppercase text-sm">Siap untuk produktif?</p>
        </div>

        <div id="customInputSection" class="mb-8 flex items-center gap-2 transition-all duration-300">
            <span class="text-sm text-gray-500 font-medium">Atur Waktu:</span>
            <input type="number" id="customMinutes" value="25" min="1" max="180" 
                   class="w-20 text-center border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 font-bold text-gray-700"
                   onchange="setCustomTime()">
            <span class="text-sm text-gray-500">menit</span>
        </div>

        <div class="flex items-center gap-4">
            <button id="btnStart" onclick="startTimer()" class="bg-blue-600 text-white w-36 py-4 rounded-2xl font-bold text-lg hover:bg-blue-700 shadow-lg shadow-blue-200 transition transform hover:-translate-y-1 flex items-center justify-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Mulai
            </button>
            
            <button id="btnPause" onclick="pauseTimer()" class="hidden bg-yellow-500 text-white w-36 py-4 rounded-2xl font-bold text-lg hover:bg-yellow-600 shadow-lg shadow-yellow-200 transition flex items-center justify-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Jeda
            </button>
            
            <button id="btnStop" onclick="stopTimer()" class="hidden bg-red-50 text-red-600 border border-red-100 w-36 py-4 rounded-2xl font-bold text-lg hover:bg-red-100 transition flex items-center justify-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path></svg>
                Selesai
            </button>
        </div>

        <div class="mt-12 flex gap-3 opacity-100 transition-opacity duration-300" id="presetButtons">
            <button onclick="setPreset(15)" class="px-5 py-2 rounded-full bg-gray-50 border border-gray-200 text-gray-600 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 text-sm font-medium transition">15m (Pendek)</button>
            <button onclick="setPreset(25)" class="px-5 py-2 rounded-full bg-gray-50 border border-gray-200 text-gray-600 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 text-sm font-medium transition">25m (Pomodoro)</button>
            <button onclick="setPreset(50)" class="px-5 py-2 rounded-full bg-gray-50 border border-gray-200 text-gray-600 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 text-sm font-medium transition">50m (Panjang)</button>
        </div>

    </div>
</div>

<audio id="alarmSound" src="https://actions.google.com/sounds/v1/alarms/beep_short.ogg"></audio>

<script>
    const BASE_URL = '<?= base_url() ?>';
    
    let timerInterval;
    let initialTime = 25 * 60; 
    let timeLeft = initialTime;
    let isRunning = false;
    let isPaused = false;

    const display = document.getElementById('timerDisplay');
    const status = document.getElementById('timerStatus');
    const progressBar = document.getElementById('progressBar');
    const customInputSection = document.getElementById('customInputSection');
    const presetButtons = document.getElementById('presetButtons');
    const customMinutesInput = document.getElementById('customMinutes');
    
    const btnStart = document.getElementById('btnStart');
    const btnPause = document.getElementById('btnPause');
    const btnStop = document.getElementById('btnStop');

    function setCustomTime() {
        if (isRunning) return;
        const mins = parseInt(customMinutesInput.value);
        if(mins > 0) {
            initialTime = mins * 60;
            timeLeft = initialTime;
            updateDisplay();
        }
    }

    function setPreset(minutes) {
        if (isRunning) return;
        customMinutesInput.value = minutes;
        setCustomTime();
    }


    function updateDisplay() {
        const minutes = Math.floor(timeLeft / 60);
        const seconds = timeLeft % 60;
        display.innerText = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        document.title = isRunning ? `(${minutes}:${seconds.toString().padStart(2, '0')}) Fokus...` : 'Mode Fokus';

        const percent = ((initialTime - timeLeft) / initialTime) * 100;
        progressBar.style.width = `${percent}%`;
    }

    function startTimer() {
        if (isRunning && !isPaused) return;
        
        isRunning = true;
        isPaused = false;
        
        status.innerText = "Sesi Fokus Sedang Berjalan...";
        status.className = "text-blue-600 mt-4 font-bold tracking-wide uppercase text-sm animate-pulse";
        display.classList.add('text-blue-600');
        display.classList.remove('text-gray-800');
        
    
        customInputSection.classList.add('opacity-0', 'pointer-events-none');
        presetButtons.classList.add('opacity-0', 'pointer-events-none');

        btnStart.classList.add('hidden');
        btnPause.classList.remove('hidden');
        btnStop.classList.remove('hidden'); 

        timerInterval = setInterval(() => {
            if (timeLeft > 0) {
                timeLeft--;
                updateDisplay();
            } else {
                finishTimer(true); 
            }
        }, 1000);
    }

    function pauseTimer() {
        clearInterval(timerInterval);
        isPaused = true;
        status.innerText = "Timer Dijeda";
        status.className = "text-yellow-500 mt-4 font-bold tracking-wide uppercase text-sm";
        display.classList.remove('text-blue-600');
        display.classList.add('text-gray-800');
        
        btnStart.innerText = "Lanjut";
        btnStart.classList.remove('hidden');
        btnPause.classList.add('hidden');
    }

    function stopTimer() {
        clearInterval(timerInterval);
        
        const elapsedSeconds = initialTime - timeLeft;
        const elapsedMinutes = Math.floor(elapsedSeconds / 60);

        if (elapsedMinutes >= 1) {
            Swal.fire({
                title: 'Sesi Selesai',
                text: `Anda telah fokus selama ${elapsedMinutes} menit. Simpan sesi ini?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#2563EB',
                cancelButtonColor: '#9CA3AF',
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Buang Saja'
            }).then((result) => {
                if (result.isConfirmed) {
                    saveSession(elapsedMinutes);
                }
                resetUI();
            });
        } else {
            resetUI();
        }
    }

    function finishTimer(isComplete) {
        clearInterval(timerInterval);
        document.getElementById('alarmSound').play();
        
        const totalMinutes = initialTime / 60;
        saveSession(totalMinutes);

        Swal.fire({
            title: '🎉 Sesi Selesai!',
            text: `${totalMinutes} menit fokus tercatat. Istirahatlah sejenak.`,
            icon: 'success',
            confirmButtonText: 'Mantap!',
            confirmButtonColor: '#2563EB'
        });
        
        resetUI();
    }

    function resetUI() {
        isRunning = false;
        isPaused = false;
        timeLeft = initialTime;
        updateDisplay();

        status.innerText = "Siap untuk produktif?";
        status.className = "text-gray-400 mt-4 font-medium tracking-wide uppercase text-sm";
        display.classList.remove('text-blue-600');
        display.classList.add('text-gray-800');
        progressBar.style.width = '0%';

        customInputSection.classList.remove('opacity-0', 'pointer-events-none');
        presetButtons.classList.remove('opacity-0', 'pointer-events-none');

        btnStart.innerText = "Mulai";
        btnStart.classList.remove('hidden');
        btnPause.classList.add('hidden');
        btnStop.classList.add('hidden');
    }

    function saveSession(minutes) {
        const formData = new FormData();
        formData.append('minutes', minutes);

        fetch(`${BASE_URL}focus/store`, { method: 'POST', body: formData })
            .then(res => res.json())
            .then(res => {
                if(res.status === 'success') {
                    const currentTotal = parseInt(document.getElementById('todayTotal').innerText);
                    document.getElementById('todayTotal').innerText = currentTotal + minutes;
                }
            });
    }

    updateDisplay();
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>