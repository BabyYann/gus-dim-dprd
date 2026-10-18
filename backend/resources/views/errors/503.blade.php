<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Mode Pemeliharaan - Gus Dim Dapil Kraksaan Raya</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <meta name="theme-color" content="#0b193d">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/gus-dim.png') }}">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    body {
      background: #0b193d;
      color: #0f172a;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px 16px;
      position: relative;
      overflow-x: hidden;
    }
    .bg-pattern {
      position: absolute;
      inset: 0;
      background: radial-gradient(circle at 50% 20%, rgba(37, 99, 235, 0.22) 0%, transparent 60%),
                  radial-gradient(circle at 80% 80%, rgba(245, 158, 11, 0.15) 0%, transparent 50%),
                  #0b193d;
      z-index: 1;
    }
    .maintenance-container {
      position: relative;
      z-index: 2;
      width: 100%;
      max-width: 480px;
      background: #ffffff;
      border-radius: 28px;
      padding: 32px 24px 28px;
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.35);
      text-align: center;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .avatar-wrapper {
      position: relative;
      width: 88px;
      height: 88px;
      margin: 0 auto 18px;
    }
    .avatar-img {
      width: 100%;
      height: 100%;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #f59e0b;
      box-shadow: 0 8px 20px rgba(245, 158, 11, 0.25);
      background: #012e54;
    }
    .status-badge-pulse {
      position: absolute;
      bottom: 2px;
      right: 2px;
      width: 26px;
      height: 26px;
      background: #f59e0b;
      border-radius: 50%;
      border: 3px solid #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .status-badge-pulse svg {
      width: 13px;
      height: 13px;
      stroke: #ffffff;
    }
    .pulse-pill {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 6px 14px;
      background: #fef3c7;
      border: 1px solid #fde68a;
      border-radius: 9999px;
      font-size: 11px;
      font-weight: 800;
      color: #b45309;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      margin-bottom: 16px;
    }
    .pulse-dot {
      width: 8px;
      height: 8px;
      background: #d97706;
      border-radius: 50%;
      box-shadow: 0 0 0 0 rgba(217, 119, 6, 0.7);
      animation: pulseAnim 1.8s infinite cubic-bezier(0.66, 0, 0, 1);
    }
    @keyframes pulseAnim {
      0% {
        box-shadow: 0 0 0 0 rgba(217, 119, 6, 0.7);
      }
      70% {
        box-shadow: 0 0 0 8px rgba(217, 119, 6, 0);
      }
      100% {
        box-shadow: 0 0 0 0 rgba(217, 119, 6, 0);
      }
    }
    .maintenance-title {
      font-size: 21px;
      font-weight: 900;
      color: #0b193d;
      line-height: 1.25;
      margin-bottom: 8px;
      letter-spacing: -0.4px;
    }
    .maintenance-subtitle {
      font-size: 12.5px;
      font-weight: 600;
      color: #475569;
      margin-bottom: 18px;
    }
    .maintenance-desc {
      font-size: 13px;
      color: #64748b;
      line-height: 1.55;
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 18px;
      padding: 16px 14px;
      margin-bottom: 22px;
      text-align: left;
    }
    .info-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-bottom: 22px;
      text-align: left;
    }
    .info-item {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      font-size: 12px;
      color: #334155;
      line-height: 1.4;
    }
    .info-icon {
      width: 22px;
      height: 22px;
      border-radius: 6px;
      background: #eff6ff;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      margin-top: 1px;
    }
    .info-icon svg {
      width: 13px;
      height: 13px;
      stroke: #2563eb;
    }
    .btn-action-wrap {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }
    .btn-refresh {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      width: 100%;
      padding: 14px 18px;
      background: #16225e;
      color: #ffffff;
      border: none;
      border-radius: 16px;
      font-size: 14px;
      font-weight: 700;
      cursor: pointer;
      box-shadow: 0 4px 14px rgba(22, 34, 94, 0.25);
      transition: all 0.2s ease;
    }
    .btn-refresh:hover, .btn-refresh:active {
      background: #0f172a;
      transform: translateY(-1px);
    }
    .btn-refresh svg {
      width: 16px;
      height: 16px;
      stroke: #ffffff;
    }
    .footer-text {
      margin-top: 20px;
      font-size: 11px;
      color: #94a3b8;
      line-height: 1.4;
    }
  </style>
</head>
<body>
  <div class="bg-pattern"></div>
  <div class="maintenance-container">
    <div class="avatar-wrapper">
      <img src="{{ asset('assets/img/gus-dim.png') }}" class="avatar-img" alt="Gus Dim" onerror="this.src='/assets/img/gus-dim.png'">
      <div class="status-badge-pulse" title="Perawatan Sistem">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="3"/>
          <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
        </svg>
      </div>
    </div>

    <div class="pulse-pill">
      <div class="pulse-dot"></div>
      <span>Mode Pemeliharaan Aktif</span>
    </div>

    <h1 class="maintenance-title">Aplikasi Sedang Dalam Perawatan Berkala</h1>
    <p class="maintenance-subtitle">Sistem Informasi Gus Dim &bull; Dapil Kraksaan Raya</p>

    <div class="maintenance-desc">
      Aplikasi Gus Dim Mobile dan Dashboard Relawan saat ini sedang dinonaktifkan sementara untuk proses pemeliharaan sistem, sinkronisasi basis data, dan peningkatan performa layanan Dapil Kraksaan Raya.
    </div>

    <div class="info-list">
      <div class="info-item">
        <div class="info-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <span><strong>Keamanan Data:</strong> Seluruh basis data pendukung, relawan, dan aspirasi aman tersimpan.</span>
      </div>
      <div class="info-item">
        <div class="info-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        </div>
        <span><strong>Akses Lapangan:</strong> Penginputan dan verifikasi konstituen ditangguhkan sementara waktu.</span>
      </div>
      <div class="info-item">
        <div class="info-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <span><strong>Status:</strong> Sistem akan segera dibuka kembali setelah proses pemeliharaan selesai.</span>
      </div>
    </div>

    <div class="btn-action-wrap">
      <button class="btn-refresh" onclick="window.location.reload()">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polyline points="23 4 23 10 17 10"/>
          <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
        </svg>
        <span>Periksa / Muat Ulang</span>
      </button>
    </div>

    <div class="footer-text">
      Dapil Kraksaan Raya: Kraksaan, Besuk, Gading<br>
      Fraksi Partai NasDem DPRD Kabupaten Probolinggo
    </div>
  </div>
</body>
</html>
