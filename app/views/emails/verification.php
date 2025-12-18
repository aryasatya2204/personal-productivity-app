<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akun</title>
    <link href="assets/css/output.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-[#667eea] to-[#764ba2] min-h-screen font-sans antialiased">
    
    <div class="flex items-center justify-center p-6 sm:p-10">
        
        <div class="max-w-xl w-full bg-white rounded-2xl shadow-2xl overflow-hidden transition-all hover:shadow-[0_20px_60px_rgba(0,0,0,0.3)]">
            
            <div class="bg-gradient-to-r from-[#667eea] to-[#764ba2] p-10 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-md rounded-2xl mb-5">
                    <span class="text-5xl">🚀</span>
                </div>
                <h1 class="text-white text-3xl font-bold drop-shadow-md">
                    Verifikasi Akun Anda
                </h1>
                <p class="mt-2 text-white/90 text-lg">
                    Satu langkah lagi menuju produktivitas!
                </p>
            </div>
            
            <div class="p-8 sm:p-10">
                <h2 class="text-gray-800 text-2xl font-semibold mb-4">
                    Halo, <?= htmlspecialchars($userName) ?>! 👋
                </h2>
                
                <p class="text-gray-600 text-lg leading-relaxed mb-8">
                    Terima kasih telah mendaftar di <span class="text-[#667eea] font-bold">Productivity App</span>. 
                    Klik tombol di bawah untuk mengaktifkan akun Anda dan mulai meningkatkan produktivitas!
                </p>
                
                <div class="flex justify-center mb-10">
                    <a href="<?= $verifyLink ?>" 
                       class="inline-block bg-gradient-to-r from-[#667eea] to-[#764ba2] text-white no-underline py-4 px-12 rounded-xl font-bold text-lg shadow-[0_10px_25px_rgba(102,126,234,0.4)] transition-transform hover:scale-105 active:scale-95">
                        ✨ Verifikasi Akun Saya
                    </a>
                </div>
                
                <div class="bg-gray-50 border-l-4 border-[#667eea] p-5 rounded-r-lg mb-8">
                    <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-2">
                        Atau copy link ini:
                    </p>
                    <p class="break-all">
                        <a href="<?= $verifyLink ?>" class="text-[#667eea] no-underline text-sm hover:underline">
                            <?= $verifyLink ?>
                        </a>
                    </p>
                </div>
                
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-8">
                    <p class="text-amber-800 text-sm leading-relaxed">
                        ⏰ <strong>Link ini berlaku 24 jam.</strong> Jika tidak digunakan, silakan daftar ulang.
                    </p>
                </div>
                
                <p class="text-gray-400 text-xs text-center leading-relaxed">
                    🔒 Jika Anda tidak mendaftar, abaikan email ini. Akun Anda tetap aman.
                </p>
            </div>
            
            <div class="bg-gray-50 p-8 text-center border-t border-gray-100">
                <p class="text-gray-600 font-bold text-sm mb-2">
                    Productivity App
                </p>
                <p class="text-gray-400 text-xs mb-4">
                    Aplikasi produktivitas untuk membantu Anda mencapai lebih banyak
                </p>
                <div class="pt-4 border-t border-gray-200">
                    <p class="text-gray-300 text-[10px]">
                        © 2025 Productivity App. All rights reserved.
                    </p>
                </div>
            </div>
            
        </div>
    </div>
    
</body>
</html>