<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="assets/css/output.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
  
  
<body class="bg-gradient-to-br from-[#f093fb] to-[#f5576c] min-h-screen font-sans antialiased">
    
    <div class="flex items-center justify-center p-6 sm:p-10">
        
        <div class="max-w-xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden shadow-[0_20px_60px_rgba(0,0,0,0.2)]">
            
            <div class="bg-gradient-to-r from-[#f093fb] to-[#f5576c] p-10 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-md rounded-2xl mb-5 border border-white/30">
                    <span class="text-5xl">🔐</span>
                </div>
                <h1 class="text-white text-3xl font-bold drop-shadow-md">
                    Reset Password
                </h1>
                <p class="mt-2 text-white/90 text-lg">
                    Permintaan reset password Anda
                </p>
            </div>
            
            <div class="p-8 sm:p-10">
                <h2 class="text-gray-800 text-2xl font-semibold mb-4">
                    Halo! 👋
                </h2>
                
                <p class="text-gray-600 text-lg leading-relaxed mb-8">
                    Kami menerima permintaan untuk reset password akun Anda. 
                    Klik tombol di bawah untuk membuat password baru:
                </p>
                
                <div class="flex justify-center mb-10">
                    <a href="<?= $resetLink ?>" 
                       class="inline-block bg-gradient-to-r from-[#f093fb] to-[#f5576c] text-white no-underline py-4 px-12 rounded-xl font-bold text-lg shadow-[0_10px_25px_rgba(240,147,251,0.4)] transition-transform hover:scale-105 active:scale-95">
                        🔑 Reset Password Saya
                    </a>
                </div>
                
                <div class="bg-gray-50 border-l-4 border-[#f093fb] p-5 rounded-r-lg mb-8">
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-2">
                        ATAU COPY LINK INI:
                    </p>
                    <p class="break-all">
                        <a href="<?= $resetLink ?>" class="text-[#f5576c] no-underline text-sm hover:underline">
                            <?= $resetLink ?>
                        </a>
                    </p>
                </div>
                
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-8">
                    <p class="text-red-800 text-sm leading-relaxed italic">
                        ⚠️ <strong>Link ini hanya berlaku 1 jam.</strong> Jika tidak digunakan, Anda perlu request ulang.
                    </p>
                </div>
                
                <p class="text-gray-400 text-xs text-center leading-relaxed">
                    🔒 Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tidak akan berubah.
                </p>
            </div>
            
            <div class="bg-gray-50 p-8 text-center border-t border-gray-100">
                <p class="text-gray-600 font-bold text-sm mb-2 uppercase tracking-wide">
                    Productivity App
                </p>
                <p class="text-gray-400 text-xs mb-4">
                    Keamanan akun Anda adalah prioritas kami
                </p>
                <div class="pt-4 border-t border-gray-200">
                    <p class="text-gray-300 text-[10px]">
                        © 2025 Productivity App. All rights reserved.
                    </p>
                </div>
            </div>
            
        </div>
    </div>
    
  <?php if (isset($_SESSION['reset_email_sent'])): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Email Reset Password Terkirim!',
    html: `
        <p class="text-gray-600 mb-3">Link reset password telah dikirim ke:</p>
        <p class="font-semibold text-pink-600"><?= $_SESSION['reset_email_address'] ?></p>
        <p class="text-sm text-gray-500 mt-4">Link berlaku 1 jam. Cek inbox atau folder spam.</p>
    `,
    confirmButtonText: 'Mengerti',
    confirmButtonColor: '#ec4899',
    allowOutsideClick: false
});
</script>
<?php 
    unset($_SESSION['reset_email_sent']); 
    unset($_SESSION['reset_email_address']);
endif; 
?>
</body>
</html>