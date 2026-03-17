<?php
$currentPage = 'offline';
$metaTitle = 'Offline — Faradj MMC';
?>
<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= htmlspecialchars($metaTitle) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="/assets/css/style.css" />
    <style>
        .offline-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(120deg, #e0c3fc 0%, #8ec5fc 100%);
            padding: 20px;
        }
        .offline-box {
            max-width: 420px;
            width: 100%;
            text-align: center;
            padding: 48px 32px;
            background: rgba(255,255,255,0.95);
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        }
        .offline-icon {
            font-size: 64px;
            color: #6c63ff;
            margin-bottom: 24px;
        }
        .offline-box h1 {
            font-size: 1.5rem;
            margin-bottom: 12px;
        }
        .offline-box p {
            color: var(--text-light);
            margin-bottom: 28px;
        }
        .btn-retry {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #6c63ff, #ff6584);
            color: white;
            padding: 14px 28px;
            border-radius: 50px;
            font-weight: 700;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-retry:hover { opacity: 0.95; }
    </style>
</head>
<body>
    <div class="offline-page">
        <div class="offline-box">
            <div class="offline-icon"><i class="fas fa-wifi"></i></div>
            <h1>İnternet bağlantısı yoxdur</h1>
            <p>Zəhmət olmasa, bağlantınızı yoxlayın və yenidən cəhd edin.</p>
            <button type="button" class="btn-retry" onclick="location.reload()">
                <i class="fas fa-redo"></i> Yenidən cəhd edin
            </button>
        </div>
    </div>
</body>
</html>
