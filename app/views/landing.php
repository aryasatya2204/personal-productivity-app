<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Productivity</title>
    <link href="../src/output.css" rel="stylesheet">
</head>

<body class="font-sans text-gray-800 antialiased bg-white">

    <nav class="flex items-center justify-between px-6 py-4 bg-white shadow-sm fixed w-full top-0 z-50">
        <div class="text-2xl font-bold text-blue-600">ProductivityWeb</div>
        <div class="space-x-4">
            <a href="<?= base_url('login') ?>" class="text-gray-600 hover:text-blue-600 font-medium">Masuk</a>
            <a href="<?= base_url('register') ?>" class="bg-blue-600 text-white px-5 py-2 rounded-full hover:bg-blue-700 transition">Daftar Sekarang</a>
        </div>
    </nav>

    <section class="flex flex-col items-center justify-center min-h-screen text-center px-4 pt-20 bg-gradient-to-b from-blue-50 to-white">
        <h1 class="text-5xl md:text-6xl font-extrabold text-gray-900 mb-6 leading-tight">
            Atur Hidupmu, <br>
            <span class="text-blue-600">Capai Lebih Banyak.</span>
        </h1>
        <p class="text-lg md:text-xl text-gray-600 mb-8 max-w-2xl">
            Satu aplikasi sederhana untuk mengelola tugas harian dan catatan penting Anda.
            Fokus pada produktivitas tanpa gangguan.
        </p>
        <div class="flex space-x-4">
            <a href="<?= base_url('register') ?>" class="bg-blue-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-blue-700 shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                Mulai Gratis
            </a>
            <a href="#fitur" class="bg-white text-gray-700 border border-gray-300 px-8 py-3 rounded-lg text-lg font-semibold hover:bg-gray-50 transition">
                Pelajari Fitur
            </a>
        </div>
    </section>

    <section id="fitur" class="py-20 px-6 max-w-6xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold mb-4">Kenapa Memilih Kami?</h2>
            <p class="text-gray-600">Didesain minimalis dengan fitur esensial yang Anda butuhkan.</p>
        </div>

        <div class="grid md:grid-cols-2 gap-12">
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:border-blue-200 transition">
                <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-2xl mb-6">
                    ✅
                </div>
                <h3 class="text-2xl font-bold mb-3">Manajemen Tugas (Todo)</h3>
                <p class="text-gray-600 leading-relaxed">
                    Catat tugas harian, atur tenggat waktu, dan tandai yang sudah selesai.
                    Jangan biarkan pekerjaan menumpuk tanpa arah.
                </p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 hover:border-purple-200 transition">
                <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center text-2xl mb-6">
                    📝
                </div>
                <h3 class="text-2xl font-bold mb-3">Catatan Pribadi (Notes)</h3>
                <p class="text-gray-600 leading-relaxed">
                    Simpan ide brilian, resep, atau catatan rapat.
                    Fitur <b>Pin</b> memudahkan Anda mengakses catatan terpenting di posisi teratas.
                </p>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-white py-8 text-center">
        <p>&copy; <?php echo date('Y'); ?> Personal Productivity App.</p>
    </footer>

</body>

</html>