<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($data['title']) ? $data['title'] . ' - ' : '' ?>Productivity App</title>
    <link href="assets/css/output.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="<?= base_url('assets/img/favicon.png') ?>">
    <link rel="apple-touch-icon" href="<?= base_url('assets/img/favicon.png') ?>">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
    /* Custom SweetAlert Styling */
    .swal2-popup.modern-alert {
        border-radius: 20px !important;
        padding: 2rem !important;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1) !important;
    }
    .swal2-title.modern-title {
        font-size: 1.5rem !important;
        font-weight: 700 !important;
        color: #1f2937 !important;
    }
    .swal2-html-container.modern-text {
        color: #4b5563 !important;
        font-size: 1rem !important;
        line-height: 1.5 !important;
    }
    .modern-confirm-btn {
        padding: 0.8rem 2rem !important;
        font-weight: 600 !important;
        border-radius: 12px !important;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        
        .dark body { background-color: #0f172a !important; color: #e2e8f0 !important; }
        
        .dark .bg-white { background-color: #1e293b !important; border-color: #334155 !important; }
        .dark .bg-gray-50 { background-color: #0f172a !important; }
        .dark .bg-gray-50\/50 { background-color: #0f172a !important; }
        
        .dark button.bg-gray-100 { background-color: #334155 !important; color: #f8fafc !important; }
        .dark button.bg-gray-100:hover { background-color: #ef4444 !important; }

        .dark .bg-white\/90 { background-color: rgba(30, 41, 59, 0.95) !important; border-color: #334155 !important; }
        
        .dark .prose { color: #e2e8f0 !important; }
        .dark .prose strong, .dark .prose h1, .dark .prose h2, .dark .prose h3 { color: #f8fafc !important; }
        
        .dark .bg-yellow-50 { background-color: #422006 !important; border-color: #713f12 !important; }
        .dark .bg-yellow-100 { background-color: #713f12 !important; color: #fef08a !important; }
        
        .dark .bg-red-100 { background-color: #7f1d1d !important; color: #fca5a5 !important; border-color: #991b1b !important; }
        .dark .bg-red-50 { background-color: #450a0a !important; border-color: #7f1d1d !important; }
        .dark .text-red-600, .dark .text-red-700 { color: #fca5a5 !important; }
        
        .dark .bg-green-100 { background-color: #14532d !important; color: #86efac !important; }
        
        .dark .text-gray-900, .dark .text-gray-800 { color: #f8fafc !important; }
        .dark .text-gray-700, .dark .text-gray-600, .dark .text-gray-500 { color: #94a3b8 !important; }
        .dark .text-gray-400, .dark .text-gray-300 { color: #cbd5e1 !important; } 
        .dark .text-blue-600 { color: #60a5fa !important; }
        
        .dark input, .dark textarea, .dark select { 
            background-color: #1e293b !important; 
            border-color: #475569 !important; 
            color: #f8fafc !important; 
        }
        .dark aside { background-color: #1e293b !important; border-color: #334155 !important; }
        .dark ::-webkit-scrollbar-track { background: #0f172a; }
        .dark ::-webkit-scrollbar-thumb { background: #475569; }

        .dark .swal2-popup { background-color: #1e293b; color: #e2e8f0; }
        .dark .swal2-title { color: #f8fafc; }
        .dark .swal2-content { color: #cbd5e1; }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800 transition-colors duration-300">
    <div class="flex h-screen overflow-hidden">