<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Akun</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px;
            text-align: center;
        }
        .icon-box {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.3);
            margin-bottom: 20px;
            font-size: 48px;
        }
        .header h1 {
            color: #ffffff;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .header p {
            color: rgba(255,255,255,0.9);
            font-size: 16px;
        }
        .content {
            padding: 40px;
        }
        .content h2 {
            color: #1f2937;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 16px;
        }
        .content p {
            color: #4b5563;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 32px;
        }
        .brand {
            color: #667eea;
            font-weight: bold;
        }
        .btn-container {
            text-align: center;
            margin-bottom: 40px;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            padding: 16px 48px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 16px;
            box-shadow: 0 10px 25px rgba(102,126,234,0.4);
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: scale(1.05);
        }
        .link-box {
            background: #f9fafb;
            border-left: 4px solid #667eea;
            padding: 20px;
            border-radius: 0 8px 8px 0;
            margin-bottom: 32px;
        }
        .link-box-label {
            color: #6b7280;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .link-box a {
            color: #667eea;
            text-decoration: none;
            font-size: 13px;
            word-break: break-all;
        }
        .link-box a:hover {
            text-decoration: underline;
        }
        .warning-box {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 32px;
        }
        .warning-box p {
            color: #92400e;
            font-size: 14px;
            margin: 0;
        }
        .footer-note {
            color: #9ca3af;
            font-size: 12px;
            text-align: center;
            line-height: 1.5;
        }
        .footer {
            background: #f9fafb;
            padding: 32px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer-title {
            color: #4b5563;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 8px;
        }
        .footer-desc {
            color: #9ca3af;
            font-size: 12px;
            margin-bottom: 16px;
        }
        .footer-divider {
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
        }
        .footer-copyright {
            color: #d1d5db;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon-box">
                <span>🚀</span>
            </div>
            <h1>Verifikasi Akun Anda</h1>
            <p>Satu langkah lagi menuju produktivitas!</p>
        </div>
        
        <div class="content">
            <h2>Halo, <?= htmlspecialchars($userName) ?>! 👋</h2>
            
            <p>
                Terima kasih telah mendaftar di <span class="brand">Productivity App</span>. 
                Klik tombol di bawah untuk mengaktifkan akun Anda dan mulai meningkatkan produktivitas!
            </p>
            
            <div class="btn-container">
                <a href="<?= $resetLink ?>" class="btn">
                    ✨ Verifikasi Akun Saya
                </a>
            </div>
            
            <div class="warning-box">
                <p>
                    ⏰ <strong>Link ini berlaku 24 jam.</strong> Jika tidak digunakan, silakan daftar ulang.
                </p>
            </div>
            
            <p class="footer-note">
                🔒 Jika Anda tidak mendaftar, abaikan email ini. Akun Anda tetap aman.
            </p>
        </div>
        
        <div class="footer">
            <div class="footer-title">
                Productivity App
            </div>
            <div class="footer-desc">
                Aplikasi produktivitas untuk membantu Anda mencapai lebih banyak
            </div>
            <div class="footer-divider">
                <div class="footer-copyright">
                    © 2025 Productivity App. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</body>
</html>