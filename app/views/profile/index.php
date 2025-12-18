<?php require_once __DIR__ . '/../layouts/header.php'; ?>
<?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>
<?php require_once __DIR__ . '/../layouts/topbar.php'; ?>

<div class="max-w-4xl mx-auto pb-10">
    
    <div class="flex items-center gap-3 mb-6">
        <a href="javascript:history.back()" class="p-2 rounded-lg bg-white border border-gray-200 text-gray-500 hover:text-blue-600 hover:bg-blue-50 transition shadow-sm group" title="Kembali">
            <svg class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Pengaturan Profil</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 text-center h-fit">
            <div class="relative w-32 h-32 mx-auto mb-4 group">
    <div class="w-full h-full rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold text-3xl shadow-lg ring-4 ring-white overflow-hidden">
        <?php 
        $avatar = $_SESSION['user_avatar'] ?? '';
        $showInitial = true;
        $avatarUrl = '';

        if (!empty($avatar)) {
            if (filter_var($avatar, FILTER_VALIDATE_URL)) {
                $avatarUrl = $avatar;
                $showInitial = false;
            } else if (file_exists('assets/uploads/avatars/' . $avatar)) {
                $avatarUrl = base_url('assets/uploads/avatars/' . $avatar);
                $showInitial = false;
            }
        }
        ?>

        <?php if (!$showInitial): ?>
            <img src="<?= $avatarUrl ?>" alt="Avatar" class="w-full h-full object-cover">
        <?php else: ?>
            <?php 
                $name = $_SESSION['user_name'] ?? 'User';
                $words = explode(" ", $name);
                $initials = (count($words) >= 2) 
                            ? strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1)) 
                            : strtoupper(substr($name, 0, 2));
                echo htmlspecialchars($initials);
            ?>
        <?php endif; ?>
    </div>
    <label for="avatarInput" class="absolute bottom-0 right-0 p-2 bg-white rounded-full shadow-md border border-gray-200 cursor-pointer hover:bg-gray-50 transition-colors">
        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
    </label>
</div>
            
            <h3 class="font-bold text-gray-800 text-lg"><?= htmlspecialchars($data['user']['name']) ?></h3>
            <p class="text-sm text-gray-500 mb-4"><?= htmlspecialchars($data['user']['email']) ?></p>
            <div class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">
                Akun Aktif
            </div>
        </div>

        <div class="md:col-span-2 space-y-6">
            
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Informasi Pribadi</h3>
                <form action="<?= base_url('profile/update') ?>" method="POST">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="name" value="<?= htmlspecialchars($data['user']['name']) ?>" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" value="<?= htmlspecialchars($data['user']['email']) ?>" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bio Singkat</label>
                            <textarea name="bio" rows="3" class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Ceritakan sedikit tentang dirimu..."><?= htmlspecialchars($data['user']['bio'] ?? '') ?></textarea>
                        </div>
                    </div>
                    <div class="mt-4 text-right">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition">Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Ganti Password</h3>
                <form action="<?= base_url('profile/password') ?>" method="POST" class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
        <input type="password" name="old_password" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">
    </div>
    
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
        <input type="password" name="new_password" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
        <input type="password" name="confirm_password" required class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500 shadow-sm transition">
    </div>

    <button type="submit" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow transition">
        Simpan Perubahan Password
    </button>
</form>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h3 class="font-bold text-gray-800 mb-4 border-b pb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    Tampilan Aplikasi
                </h3>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-800">Mode Gelap</p>
                        <p class="text-xs text-gray-500">Gunakan tema gelap untuk kenyamanan mata.</p>
                    </div>
                    
                    <button id="themeToggle" onclick="toggleTheme()" class="relative inline-flex h-6 w-11 items-center rounded-full bg-gray-200 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <span class="sr-only">Use setting</span>
                        <span id="toggleKnob" class="translate-x-1 inline-block h-4 w-4 transform rounded-full bg-white transition-transform"></span>
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    const toggleBtn = document.getElementById('themeToggle');
    const knob = document.getElementById('toggleKnob');
    const html = document.documentElement;

    function initTheme() {
        if (html.classList.contains('dark')) {
            toggleBtn.classList.remove('bg-gray-200');
            toggleBtn.classList.add('bg-blue-600');
            knob.classList.remove('translate-x-1');
            knob.classList.add('translate-x-6');
        }
    }

    function toggleTheme() {
        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            
            toggleBtn.classList.remove('bg-blue-600');
            toggleBtn.classList.add('bg-gray-200');
            knob.classList.remove('translate-x-6');
            knob.classList.add('translate-x-1');
        } else {
            html.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            
            toggleBtn.classList.remove('bg-gray-200');
            toggleBtn.classList.add('bg-blue-600');
            knob.classList.remove('translate-x-1');
            knob.classList.add('translate-x-6');
        }
    }

    initTheme();
</script>

<?php if (isset($_SESSION['swal_error'])): ?>
<script>
    Swal.fire({
        icon: 'error',
        title: '<?= $_SESSION['swal_error']['title'] ?>',
        html: '<?= $_SESSION['swal_error']['html'] ?>',
        confirmButtonColor: '#2563EB'
    });
</script>
<?php unset($_SESSION['swal_error']); endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>