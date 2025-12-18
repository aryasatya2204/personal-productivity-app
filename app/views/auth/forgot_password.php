<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Productivity App</title>
    <link href="<?= base_url('assets/css/output.css') ?>" rel="stylesheet">
    <!-- LOAD SWEETALERT DI HEAD -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    <!-- POPUP SUCCESS -->
    <?php if (isset($_SESSION['reset_email_sent'])): ?>
    <script>
    (function() {
        // Hapus session flag IMMEDIATELY untuk prevent re-trigger
        <?php 
            $emailAddress = $_SESSION['reset_email_address'] ?? '';
            unset($_SESSION['reset_email_sent']); 
            unset($_SESSION['reset_email_address']);
        ?>

        // Tunggu DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', showPopup);
        } else {
            showPopup();
        }

        function showPopup() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Email Reset Password Terkirim!',
                    html: `
                        <p style="color: #4b5563; margin-bottom: 12px;">Link reset password telah dikirim ke:</p>
                        <p style="font-weight: 600; color: #ec4899; margin-bottom: 16px;"><?= htmlspecialchars($emailAddress) ?></p>
                        <p style="font-size: 14px; color: #6b7280;">Link berlaku 1 jam. Cek inbox atau folder spam.</p>
                    `,
                    confirmButtonText: 'Mengerti',
                    confirmButtonColor: '#ec4899',
                    allowOutsideClick: false,
                    allowEscapeKey: true,
                    customClass: {
                        popup: 'bounce-popup'
                    }
                }).then(function() {
                    // Optional: Clear URL parameters after close
                    if (window.history.replaceState) {
                        window.history.replaceState({}, document.title, window.location.pathname);
                    }
                });
            } else {
                console.error('SweetAlert not loaded');
                alert('Email reset password telah dikirim ke <?= htmlspecialchars($emailAddress) ?>');
            }
        }
    })();
    </script>

    <style>
    @keyframes bounceIn {
        0% {
            transform: scale(0.3);
            opacity: 0;
        }
        50% {
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .bounce-popup {
        animation: bounceIn 0.6s ease-out !important;
    }
    </style>
    <?php endif; ?>

    <!-- POPUP ERROR -->
    <?php if (!empty($data['error'])): ?>
    <script>
    (function() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', showError);
        } else {
            showError();
        }

        function showError() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengirim Email',
                    text: '<?= addslashes($data['error']) ?>',
                    confirmButtonText: 'Coba Lagi',
                    confirmButtonColor: '#ef4444',
                    customClass: {
                        popup: 'shake-popup'
                    }
                });
            }
        }
    })();
    </script>

    <style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
        20%, 40%, 60%, 80% { transform: translateX(10px); }
    }

    .shake-popup {
        animation: shake 0.5s ease-out !important;
    }
    </style>
    <?php endif; ?>

</body>
</html>