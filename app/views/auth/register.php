<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - Productivity App</title>
    <link href="../src/output.css" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900">

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="mb-6 text-center">
            <h1 class="text-3xl font-bold text-blue-600">ProductivityApp</h1>
            <p class="text-gray-500 mt-2">Mulai perjalanan produktifmu hari ini.</p>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 ppy-8 bg-white shadow-xl rounded-2xl border border-gray-100">
            <form action="<?= base_url('register') ?>" method="POST">
                
                <div class="mb-4">
                    <label for="name" class="block font-medium text-sm text-gray-700">Nama Lengkap</label>
                    <input type="text" name="name" id="name" 
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 border px-4 py-2 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 transition-colors <?php echo (!empty($data['name_error'])) ? 'border-red-500' : ''; ?>" 
                        value="<?php echo $data['name']; ?>" placeholder="Jhon Doe">
                    <span class="text-xs text-red-500 mt-1"><?php echo $data['name_error']; ?></span>
                </div>

                <div class="mb-4">
                    <label for="email" class="block font-medium text-sm text-gray-700">Alamat Email</label>
                    <input type="email" name="email" id="email" 
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 border px-4 py-2 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 transition-colors <?php echo (!empty($data['email_error'])) ? 'border-red-500' : ''; ?>" 
                        value="<?php echo $data['email']; ?>" placeholder="nama@email.com">
                    <span class="text-xs text-red-500 mt-1"><?php echo $data['email_error']; ?></span>
                </div>

                <div class="mb-4">
                    <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                    <input type="password" name="password" id="password" 
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 border px-4 py-2 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 transition-colors <?php echo (!empty($data['password_error'])) ? 'border-red-500' : ''; ?>" 
                        placeholder="Minimal 6 karakter">
                    <span class="text-xs text-red-500 mt-1"><?php echo $data['password_error']; ?></span>
                </div>

                <div class="mb-6">
                    <label for="confirm_password" class="block font-medium text-sm text-gray-700">Ulangi Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" 
                        class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-50 border px-4 py-2 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 transition-colors <?php echo (!empty($data['confirm_password_error'])) ? 'border-red-500' : ''; ?>" 
                        placeholder="Ketik ulang password">
                    <span class="text-xs text-red-500 mt-1"><?php echo $data['confirm_password_error']; ?></span>
                </div>

                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                    Daftar Sekarang
                </button>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Sudah punya akun? 
                        <a href="<?= base_url('login') ?>" class="font-medium text-blue-600 hover:text-blue-500 hover:underline">Masuk di sini</a>
                    </p>
                </div>
            </form>
        </div>
        
        <p class="mt-8 text-center text-xs text-gray-400">
            &copy; <?php echo date('Y'); ?> Personal Productivity App
        </p>
    </div>

</body>
</html>