<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ProductivityApp - Kelola Hidup Lebih Baik</title>
    <link href="assets/css/output.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
    <link rel="apple-touch-icon" href="<?= base_url('assets/img/favicon.png') ?>">
    <style>
        html { scroll-behavior: smooth; }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes blob {
            0%, 100% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }
        
        @keyframes gradient {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-blob { animation: blob 7s ease-in-out infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
        
        .animate-gradient {
            background-size: 200% 200%;
            animation: gradient 5s ease infinite;
        }
        
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .card-hover:hover {
            transform: translateY(-8px) scale(1.02);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-slate-50 via-white to-blue-50">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-xl border-b border-gray-100/50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="w-9 h-9 sm:w-11 sm:h-11 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-xl sm:rounded-2xl flex items-center justify-center text-white font-bold text-lg sm:text-xl shadow-lg shadow-blue-500/30 rotate-3">
                        P
                    </div>
                    <span class="font-bold text-lg sm:text-xl bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">ProductivityApp</span>
                </div>

                <div class="hidden md:flex items-center gap-8">
                    <a href="#fitur" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Fitur</a>
                    <a href="#tentang" class="text-gray-600 hover:text-blue-600 font-medium transition-colors">Tentang</a>
                    <div class="h-6 w-px bg-gray-200"></div>
                    <a href="<?= base_url('login') ?>" class="text-gray-900 hover:text-blue-600 font-medium transition-colors">Masuk</a>
                    <a href="<?= base_url('register') ?>" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-2.5 rounded-full font-semibold transition-all shadow-lg shadow-blue-500/30 hover:shadow-xl hover:shadow-blue-500/40 hover:-translate-y-0.5">
                        Daftar Gratis
                    </a>
                </div>

                <div class="md:hidden flex items-center gap-3">
                    <a href="<?= base_url('login') ?>" class="text-blue-600 font-semibold text-sm">Masuk</a>
                    <a href="<?= base_url('register') ?>" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 rounded-full font-semibold text-sm shadow-lg shadow-blue-500/30">
                        Daftar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative pt-24 sm:pt-32 pb-16 sm:pb-24 lg:pt-40 lg:pb-32 overflow-hidden min-h-screen flex items-center">
        <!-- Animated Background Blobs -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
            <div class="absolute -top-20 -left-40 w-80 h-80 bg-gradient-to-br from-blue-400 to-cyan-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
            <div class="absolute top-40 left-1/2 w-80 h-80 bg-gradient-to-br from-indigo-400 to-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
            <div class="text-center">
                <!-- Badge -->
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 text-xs sm:text-sm font-semibold mb-6 sm:mb-8 border border-blue-100/50 shadow-sm animate-float">
                    <span class="relative flex h-2 w-2 mr-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-600 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-600"></span>
                    </span>
                    Versi 2.0 Kini Tersedia
                </div>

                <!-- Heading -->
                <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold text-gray-900 tracking-tight mb-4 sm:mb-6 leading-tight px-4">
                    Atur Waktu,<br class="hidden sm:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 animate-gradient">
                        Capai Mimpi.
                    </span>
                </h1>

                <!-- Description -->
                <p class="mt-4 max-w-2xl mx-auto text-base sm:text-lg md:text-xl text-gray-600 mb-8 sm:mb-10 leading-relaxed px-4">
                    Platform all-in-one untuk manajemen tugas, fokus, dan kebiasaan. 
                    Dirancang untuk membantu Anda menyelesaikan lebih banyak dengan stres lebih sedikit.
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row justify-center gap-3 sm:gap-4 px-4 mb-12 sm:mb-16">
                    <a href="<?= base_url('register') ?>" class="group px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-2xl hover:from-blue-700 hover:to-indigo-700 transition-all shadow-xl shadow-blue-500/30 hover:shadow-2xl hover:shadow-blue-500/40 flex items-center justify-center hover:-translate-y-1">
                        Mulai Sekarang - Gratis
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </a>
                    <a href="#fitur" class="px-6 sm:px-8 py-3 sm:py-4 bg-white text-gray-700 font-bold rounded-2xl border-2 border-gray-200 hover:bg-gray-50 hover:border-gray-300 transition-all flex items-center justify-center shadow-sm hover:shadow-md hover:-translate-y-1">
                        Pelajari Lebih Lanjut
                    </a>
                </div>

                <!-- Feature Highlights -->
                <div class="mt-8 sm:mt-12 flex flex-wrap justify-center gap-4 sm:gap-6 max-w-3xl mx-auto px-4">
                    <div class="flex items-center gap-2 bg-white/80 backdrop-blur-sm px-4 py-2 rounded-full border border-gray-200 shadow-sm">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-sm sm:text-base text-gray-700 font-medium">Gratis Selamanya</span>
                    </div>
                    <div class="flex items-center gap-2 bg-white/80 backdrop-blur-sm px-4 py-2 rounded-full border border-gray-200 shadow-sm">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-sm sm:text-base text-gray-700 font-medium">Tanpa Iklan</span>
                    </div>
                    <div class="flex items-center gap-2 bg-white/80 backdrop-blur-sm px-4 py-2 rounded-full border border-gray-200 shadow-sm">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span class="text-sm sm:text-base text-gray-700 font-medium">Mudah Digunakan</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-16 sm:py-20 lg:py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-12 sm:mb-16">
                <div class="inline-block px-4 py-1.5 rounded-full bg-blue-50 text-blue-700 text-sm font-semibold mb-4">
                    Fitur Unggulan
                </div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
                    Semua yang Anda Butuhkan
                </h2>
                <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto">
                    Tools powerful yang dirancang untuk meningkatkan produktivitas Anda
                </p>
            </div>

            <!-- Feature Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <!-- Card 1 -->
                <div class="card-hover bg-gradient-to-br from-white to-green-50/30 p-6 sm:p-8 rounded-3xl border-2 border-green-100 shadow-lg shadow-green-100/50">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-green-400 to-emerald-500 rounded-2xl flex items-center justify-center mb-5 sm:mb-6 shadow-lg shadow-green-500/30">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3">Task Management</h3>
                    <p class="text-gray-600 leading-relaxed text-sm sm:text-base">
                        Catat, atur prioritas, dan selesaikan tugas harian Anda dengan sistem checklist yang intuitif dan memuaskan.
                    </p>
                    <div class="mt-5 sm:mt-6">
                        <a href="#" class="text-green-600 font-semibold text-sm inline-flex items-center hover:gap-2 transition-all">
                            Lihat Detail
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="card-hover bg-gradient-to-br from-white to-red-50/30 p-6 sm:p-8 rounded-3xl border-2 border-red-100 shadow-lg shadow-red-100/50">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-red-400 to-rose-500 rounded-2xl flex items-center justify-center mb-5 sm:mb-6 shadow-lg shadow-red-500/30">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3">Focus Timer</h3>
                    <p class="text-gray-600 leading-relaxed text-sm sm:text-base">
                        Teknik Pomodoro terintegrasi. Fokus 25 menit, istirahat 5 menit untuk tingkatkan konsentrasi maksimal.
                    </p>
                    <div class="mt-5 sm:mt-6">
                        <a href="#" class="text-red-600 font-semibold text-sm inline-flex items-center hover:gap-2 transition-all">
                            Lihat Detail
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="card-hover bg-gradient-to-br from-white to-purple-50/30 p-6 sm:p-8 rounded-3xl border-2 border-purple-100 shadow-lg shadow-purple-100/50">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-purple-400 to-indigo-500 rounded-2xl flex items-center justify-center mb-5 sm:mb-6 shadow-lg shadow-purple-500/30">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3">Habit Tracker</h3>
                    <p class="text-gray-600 leading-relaxed text-sm sm:text-base">
                        Bangun kebiasaan baik baru. Pantau progres harian Anda dan tetap konsisten dengan visualisasi yang jelas.
                    </p>
                    <div class="mt-5 sm:mt-6">
                        <a href="#" class="text-purple-600 font-semibold text-sm inline-flex items-center hover:gap-2 transition-all">
                            Lihat Detail
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-16 sm:py-20 lg:py-24 bg-gradient-to-br from-slate-50 to-blue-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-8 sm:gap-12 items-center">
                <div>
                    <div class="inline-block px-4 py-1.5 rounded-full bg-blue-50 text-blue-700 text-sm font-semibold mb-4">
                        Tentang Kami
                    </div>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 sm:mb-6">
                        Produktivitas yang <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Sederhana</span>
                    </h2>
                    <p class="text-gray-600 leading-relaxed mb-4 text-sm sm:text-base">
                        Kami percaya bahwa produktivitas tidak harus rumit. ProductivityApp dirancang dengan prinsip kesederhanaan dan efektivitas.
                    </p>
                    <p class="text-gray-600 leading-relaxed mb-6 sm:mb-8 text-sm sm:text-base">
                        Dengan interface yang bersih dan fitur yang fokus, kami membantu Anda mencapai tujuan dengan cara yang menyenangkan.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Cepat & Responsif</h4>
                                <p class="text-sm text-gray-600">Interface yang ringan dan cepat untuk pengalaman terbaik</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Aman & Private</h4>
                                <p class="text-sm text-gray-600">Data Anda tersimpan aman dengan enkripsi end-to-end</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-1">Customizable</h4>
                                <p class="text-sm text-gray-600">Sesuaikan aplikasi dengan gaya dan kebutuhan Anda</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative order-first md:order-last">
                    <div class="relative aspect-square bg-gradient-to-br from-blue-500 to-indigo-600 rounded-3xl shadow-2xl shadow-blue-500/30 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-tr from-purple-500/30 to-pink-500/30"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-1/2 h-1/2 text-white opacity-20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="relative z-10 h-full flex flex-col items-center justify-center text-white p-8">
                            <svg class="w-24 h-24 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-2xl font-bold mb-2">Mulai Hari Ini</h3>
                            <p class="text-blue-100 text-center">Gratis & Tanpa Batas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 sm:py-20 lg:py-24 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl opacity-10 animate-blob"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl opacity-10 animate-blob animation-delay-2000"></div>
        </div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-4 sm:mb-6">
                Siap Menjadi Lebih Produktif?
            </h2>
            <p class="text-base sm:text-lg md:text-xl text-blue-100 mb-8 sm:mb-10 max-w-2xl mx-auto">
                Mulai perjalanan produktivitas Anda hari ini, tanpa perlu kartu kredit
            </p>
            <a href="#" class="inline-flex items-center px-6 sm:px-8 py-3 sm:py-4 bg-white text-blue-600 font-bold rounded-2xl hover:bg-gray-50 transition-all shadow-xl hover:shadow-2xl hover:-translate-y-1">
                Mulai Gratis Sekarang
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-400 py-8 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center gap-4 text-center">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-lg flex items-center justify-center text-white font-bold">
                        P
                    </div>
                    <span class="font-bold text-lg text-white">ProductivityApp</span>
                </div>
                <p class="text-sm">© 2025 All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>