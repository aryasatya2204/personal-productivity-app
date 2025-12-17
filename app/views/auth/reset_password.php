<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Password Baru - Productivity App</title>
    <link href="<?= base_url('assets/css/output.css') ?>" rel="stylesheet">
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-900">

    <div class="min-h-screen flex flex-col justify-center items-center px-4 sm:px-0">
        
        <div class="mb-6 text-center">
            <h1 class="text-3xl font-bold text-blue-600">ProductivityApp</h1>
        </div>

        <div class="w-full sm:max-w-md bg-white shadow-lg rounded-2xl border border-gray-100 p-8">
            
            <h2 class="text-xl font-semibold text-gray-800 mb-6 text-center">Buat Password Baru</h2>

            <form action="" method="POST">
                
                <div class="mb-4">
                    <label for="password" class="block font-medium text-sm text-gray-700 mb-1">Password Baru</label>
                    <input type="password" name="password" id="password" required
                        class="block w-full rounded-lg border-gray-300 bg-gray-50 border px-4 py-3 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 transition-colors <?php echo (!empty($data['password_error'])) ? 'border-red-500' : ''; ?>"
                        placeholder="Minimal 6 karakter">
                    <span class="text-xs text-red-500 mt-1"><?php echo $data['password_error'] ?? ''; ?></span>
                </div>

                <div class="mb-6">
                    <label for="confirm_password" class="block font-medium text-sm text-gray-700 mb-1">Konfirmasi Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" required
                        class="block w-full rounded-lg border-gray-300 bg-gray-50 border px-4 py-3 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-200 transition-colors <?php echo (!empty($data['confirm_password_error'])) ? 'border-red-500' : ''; ?>"
                        placeholder="Ketik ulang password">
                    <span class="text-xs text-red-500 mt-1"><?php echo $data['confirm_password_error'] ?? ''; ?></span>
                </div>

                <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                    Ubah Password
                </button>
            </form>
        </div>
    </div>

</body>
</html>