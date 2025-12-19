<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Productivity App</title>
    <link href="<?= base_url('assets/css/output.css') ?>" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
    <link rel="apple-touch-icon" href="<?= base_url('assets/img/favicon.png') ?>">
    <style>
        .swal2-container { z-index: 99999 !important; }
        .swal2-popup.modern-alert { border-radius: 20px !important; padding: 1.5rem !important; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.1) !important; }
        .swal2-title.modern-title { font-size: 1.5rem !important; font-weight: 700 !important; color: #1f2937 !important; }
        .modern-confirm-btn { padding: 0.7rem 2rem !important; font-weight: 600 !important; border-radius: 10px !important; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">
    <div class="min-h-screen flex bg-white">
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24 w-full lg:w-1/2">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 text-blue-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Selamat Datang</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Belum punya akun? <a href="<?= base_url('register') ?>" class="font-medium text-blue-600 hover:text-blue-500">Daftar sekarang</a>
                    </p>
                </div>

                <div class="mt-8">
                    <form action="" method="POST" class="space-y-6">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input id="email" name="email" type="email" required 
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <div class="mt-1 relative">
                                <input id="password" name="password" type="password" required 
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <button type="button" onclick="togglePassword('password', 'toggleIcon')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <svg id="toggleIcon" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <label for="remember-me" class="ml-2 block text-sm text-gray-900">Ingat saya</label>
                            </div>
                            <a href="<?= base_url('auth/forgot-password') ?>" class="text-sm font-medium text-blue-600 hover:text-blue-500">Lupa password?</a>
                        </div>

                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Masuk
                        </button>
                    </form>

                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-300"></div></div>
                            <div class="relative flex justify-center text-sm"><span class="px-2 bg-white text-gray-500">Atau masuk dengan</span></div>
                        </div>
                        <div class="mt-6">
                            <a href="<?= base_url('auth/google') ?>" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <img class="h-5 w-5 mr-2" src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google">
                                Google
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden lg:block relative w-0 flex-1">
            <img class="absolute inset-0 h-full w-full object-cover" src="https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80" alt="Background">
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === "password") {
                input.type = "text";
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />';
            } else {
                input.type = "password";
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }
    </script>

    <?php if (!empty($data['email_error']) || !empty($data['password_error'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Gagal Masuk',
                html: `
                    <div class="space-y-2 mt-2">
                        <?php if (!empty($data['email_error'])): ?>
                            <div class="flex items-center gap-2 text-red-600 bg-red-50 px-3 py-2 rounded-lg border border-red-100 text-left">
                                <span class="text-xs font-medium"><?= htmlspecialchars($data['email_error']) ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($data['password_error'])): ?>
                            <div class="flex items-center gap-2 text-red-600 bg-red-50 px-3 py-2 rounded-lg border border-red-100 text-left">
                                <span class="text-xs font-medium"><?= htmlspecialchars($data['password_error']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                `,
                icon: 'error',
                iconColor: '#ef4444',
                buttonsStyling: false,
                customClass: {
                    popup: 'modern-alert animate__animated animate__shakeX',
                    title: 'modern-title',
                    confirmButton: 'modern-confirm-btn bg-blue-600 hover:bg-blue-700 text-white transition-all'
                },
                confirmButtonText: 'Coba Lagi'
            });
        });
    </script>
    <?php endif; ?>

    <?php if (isset($data['login_success']) || isset($_SESSION['login_success'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Selamat Datang!',
                text: 'Senang melihat Anda kembali.',
                icon: 'success',
                iconColor: '#2563EB',
                buttonsStyling: false,
                allowOutsideClick: false, // Memaksa user klik tombol untuk redirect
                customClass: {
                    popup: 'modern-alert',
                    title: 'modern-title',
                    confirmButton: 'modern-confirm-btn bg-blue-600 hover:bg-blue-700 text-white transition-all'
                },
                confirmButtonText: 'Lanjut ke Dashboard'
            }).then((result) => {
                // Redirect ke dashboard saat ditutup/dikonfirmasi
                window.location.href = '<?= base_url('dashboard') ?>';
            });
        });
        <?php unset($_SESSION['login_success']); // Hapus session agar tidak muncul terus ?>
    </script>
    <?php endif; ?>

</body>
</html>