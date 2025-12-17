<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Productivity App</title>
    <link href="<?= base_url('assets/css/output.css') ?>" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900">

    <div class="min-h-screen flex flex-col justify-center items-center px-4 sm:px-0">
        
        <div class="mb-6 text-center">
            <h1 class="text-3xl font-bold text-blue-600">ProductivityApp</h1>
            <p class="text-gray-500 mt-2 text-sm">Reset akses akun Anda</p>
        </div>

        <div class="w-full sm:max-w-md bg-white shadow-lg rounded-2xl border border-gray-100 overflow-hidden">
            <div class="p-8">
                
                <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">Lupa Password?</h2>
                <p class="text-gray-600 text-sm mb-6 text-center">
                    Masukkan alamat email yang terdaftar. Kami akan mengirimkan link untuk mereset password Anda.
                </p>

                <?php if (!empty($data['error'])): ?>
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                        <div class="flex">
                            <div class="ml-3">
                                <p class="text-sm text-red-700"><?= $data['error']; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($data['success'])): ?>
                    <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-md">
                        <div class="flex">
                            <div class="ml-3">
                                <p class="text-sm text-green-700"><?= $data['success']; ?></p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('auth/forgot-password') ?>" method="POST">
                    <div class="mb-5">
                        <label for="email" class="block font-medium text-sm text-gray-700 mb-1">Email Address</label>
                        <input type="email" name="email" id="email" required
                            class="block w-full rounded-lg border-gray-300 bg-gray-50 border px-4 py-3 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 transition-colors text-sm" 
                            placeholder="nama@email.com"
                            value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                    </div>

                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                        Kirim Link Reset
                    </button>
                </form>
            </div>

            <div class="bg-gray-50 px-8 py-4 border-t border-gray-100 flex justify-center">
                <a href="<?= base_url('login') ?>" class="text-sm font-medium text-blue-600 hover:text-blue-500 flex items-center">
                    <span class="mr-2">&larr;</span> Kembali ke Login
                </a>
            </div>
        </div>
    </div>

</body>
</html>