<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun - Productivity App</title>
    <link href="<?= base_url('assets/css/output.css') ?>" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans antialiased">

    <div class="min-h-screen flex bg-white">
        
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24 w-full lg:w-1/2">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                
                <div>
                    <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Buat akun baru</h2>
                    <p class="mt-2 text-sm text-gray-600">
                        Atau <a href="<?= base_url('login') ?>" class="font-medium text-blue-600 hover:text-blue-500">masuk ke akun yang ada</a>
                    </p>
                </div>

                <div class="mt-8">
                    <div>
                        <a href="<?= base_url('auth/google') ?>" class="w-full inline-flex justify-center py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/></svg>
                            <span class="ml-2">Daftar dengan Google</span>
                        </a>
                    </div>

                    <div class="mt-6 relative">
                        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-300"></div></div>
                        <div class="relative flex justify-center text-sm"><span class="px-2 bg-white text-gray-500">Atau dengan email</span></div>
                    </div>

                    <div class="mt-6">
                        <form action="<?= base_url('register') ?>" method="POST" class="space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="text" name="name" id="name" required class="block w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-3 border px-3" placeholder="John Doe" value="<?= $data['name'] ?? '' ?>">
                                </div>
                                <p class="mt-1 text-xs text-red-600"><?= $data['name_error'] ?? '' ?></p>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="email" name="email" id="email" required class="block w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-3 border px-3" placeholder="nama@email.com" value="<?= $data['email'] ?? '' ?>">
                                </div>
                                <p class="mt-1 text-xs text-red-600"><?= $data['email_error'] ?? '' ?></p>
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="password" name="password" id="password" required 
                                           class="block w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-3 border px-3 pr-10" 
                                           placeholder="••••••••" onkeyup="checkPasswordLength()">
                                    
                                    <button type="button" onclick="togglePassword('password', 'eye-icon-1')" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer focus:outline-none">
                                        <svg id="eye-icon-1" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                                <p id="password-hint" class="mt-1 text-xs text-gray-500 transition-colors duration-300">
                                    * Minimal 8 karakter.
                                </p>
                                <p class="mt-1 text-xs text-red-600"><?= $data['password_error'] ?? '' ?></p>
                            </div>

                            <div>
                                <label for="confirm_password" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="password" name="confirm_password" id="confirm_password" required 
                                           class="block w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 sm:text-sm py-3 border px-3 pr-10" 
                                           placeholder="••••••••">
                                    
                                    <button type="button" onclick="togglePassword('confirm_password', 'eye-icon-2')" class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer focus:outline-none">
                                        <svg id="eye-icon-2" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="mt-1 text-xs text-red-600"><?= $data['confirm_password_error'] ?? '' ?></p>
                            </div>

                            <div>
                                <button type="submit" id="submit-btn" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-transform transform hover:-translate-y-0.5">
                                    Daftar Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="hidden lg:block relative w-0 flex-1">
            <img class="absolute inset-0 h-full w-full object-cover" src="https://images.unsplash.com/photo-1497215728101-856f4ea42174?auto=format&fit=crop&w=1950&q=80" alt="Workspace">
            <div class="absolute inset-0 bg-blue-900 opacity-20"></div>
            <div class="absolute bottom-10 left-10 text-white p-6">
                <h3 class="text-3xl font-bold">Mulai Produktivitas Anda</h3>
                <p class="mt-2 text-lg text-gray-100">Gabung dengan ribuan profesional.</p>
            </div>
        </div>
    </div>

    <script>
        // Fungsi Toggle Password
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === "password") {
                input.type = "text";
                // Ganti ikon menjadi 'eye-off' (silang)
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />';
            } else {
                input.type = "password";
                // Ganti ikon kembali ke 'eye'
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }

        // Fungsi Validasi Panjang Password
        function checkPasswordLength() {
            const password = document.getElementById('password').value;
            const hint = document.getElementById('password-hint');
            const btn = document.getElementById('submit-btn');

            if (password.length >= 8) {
                hint.classList.remove('text-gray-500', 'text-red-500');
                hint.classList.add('text-green-600');
                hint.innerHTML = '✓ Panjang password sudah sesuai.';
            } else {
                hint.classList.remove('text-green-600', 'text-gray-500');
                hint.classList.add('text-red-500');
                hint.innerHTML = '* Password harus minimal 8 karakter (' + password.length + '/8)';
            }
        }
    </script>

</body>
</html>