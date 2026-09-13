<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
  <meta name="theme-color" content="#16225e">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="apple-mobile-web-app-title" content="Gus Dim Mobile">
  <base href="{{ url('mobile') }}/">
  <link rel="manifest" href="manifest.json">
  <link rel="apple-touch-icon" href="assets/icons/gus-dim.png">
  <link rel="icon" type="image/png" href="assets/icons/gus-dim.png">
  <title>Gus Dim Mobile - Aplikasi PWA Lapangan Dapil Kraksaan Raya</title>
  <link rel="stylesheet" href="assets/css/mobile.css">
</head>
<body>

  <div class="mobile-app">
    <!-- Offline Banner -->
    <div id="offlineBanner" class="offline-banner">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="1" y1="1" x2="23" y2="23"/><path d="M16.72 11.06A10.94 10.94 0 0 1 19 12.55"/><path d="M5 12.55a10.94 10.94 0 0 1 5.17-2.39"/><path d="M10.71 5.05A16 16 0 0 1 22.58 9"/><path d="M1.42 9a15.91 15.91 0 0 1 4.7-2.88"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>
      <span>Mode Offline Aktif - Data Tersimpan di Perangkat</span>
    </div>

    <!-- Header Atas Smartphone -->
    <header class="mobile-header">
      <div class="header-inner">
        <div class="header-left">
          <button type="button" id="btnOpenDrawer" class="header-btn" title="Buka Menu Navigasi">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
          </button>
          <div class="header-brand">
            <img src="assets/icons/gus-dim.png" alt="Gus Dim" class="header-avatar">
            <div class="header-title-box">
              <span class="header-app-title">GUS DIM MOBILE</span>
              <span class="header-badge-dapil">Dapil Kraksaan Raya</span>
            </div>
          </div>
        </div>

        <div class="header-actions">
          <button type="button" id="btnRefresh" class="header-btn" title="Sinkronisasi Data">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
          </button>
          <a href="{{ url('/') }}" class="header-btn" title="Buka Tampilan Desktop">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
          </a>
        </div>
      </div>
    </header>

    <!-- Slide-Out Drawer Menu (Sidebar 4 Rumpun Sesuai Desktop) -->
    <div id="drawerBackdrop" class="drawer-backdrop"></div>
    <aside id="mobileDrawer" class="mobile-drawer">
      <div class="drawer-header">
        <div class="drawer-user-info">
          <img src="assets/icons/gus-dim.png" alt="Gus Dim" class="drawer-avatar">
          <div>
            <div class="drawer-user-name">Tim Relawan Gus Dim</div>
            <div class="drawer-user-role">Dapil Kraksaan Raya</div>
          </div>
        </div>
        <button type="button" id="btnCloseDrawer" class="drawer-close-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>

      <div class="drawer-body">
        <!-- Rumpun 1: Dashboard & Geospasial -->
        <div class="drawer-section-title">Dashboard &amp; Peta</div>
        <div class="drawer-menu-item active" data-page="dashboard">
          <div class="drawer-menu-item-left">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span>Beranda Utama</span>
          </div>
        </div>
        <div class="drawer-menu-item" data-page="peta">
          <div class="drawer-menu-item-left">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" x2="9" y1="3" y2="18"/><line x1="15" x2="15" y1="6" y2="21"/></svg>
            <span>Peta Sebaran Dapil</span>
          </div>
          <span class="drawer-badge">GIS</span>
        </div>

        <!-- Rumpun 2: Basis Data & Lapangan -->
        <div class="drawer-section-title">Basis Data &amp; Lapangan</div>
        <div class="drawer-menu-item" data-page="pendukung">
          <div class="drawer-menu-item-left">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <span>Data Pendukung</span>
          </div>
          <span id="drawerBadgePendukung" class="drawer-badge">0</span>
        </div>
        <div class="drawer-menu-item" data-page="input">
          <div class="drawer-menu-item-left">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            <span>Entri 5 Jalur Terpadu</span>
          </div>
          <span class="drawer-badge" style="background:#eff6ff;color:#2563eb;">OCR</span>
        </div>
        <div class="drawer-menu-item" data-page="leaderboard">
          <div class="drawer-menu-item-left">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
            <span>Leaderboard Relawan</span>
          </div>
          <span class="drawer-badge" style="background:#fef3c7;color:#b45309;">Top</span>
        </div>

        <!-- Rumpun 3: Aspirasi & Kebijakan -->
        <div class="drawer-section-title">Aspirasi &amp; Kebijakan</div>
        <div class="drawer-menu-item" data-page="aspirasi">
          <div class="drawer-menu-item-left">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            <span>Aspirasi Warga</span>
          </div>
          <span id="drawerBadgeAspirasi" class="drawer-badge">0</span>
        </div>
        <div class="drawer-menu-item" data-page="reses">
          <div class="drawer-menu-item-left">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M8 10h.01"/><path d="M16 10h.01"/></svg>
            <span>Reses &amp; Pokir APBD</span>
          </div>
          <span id="drawerBadgePokir" class="drawer-badge">0</span>
        </div>

        <!-- Rumpun 4: Sistem & Pengawasan -->
        <div class="drawer-section-title">Sistem &amp; Pengawasan</div>
        <div class="drawer-menu-item" data-page="riwayat">
          <div class="drawer-menu-item-left">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <span>Riwayat &amp; Log Audit</span>
          </div>
        </div>
        <div class="drawer-menu-item" data-page="pengaturan">
          <div class="drawer-menu-item-left">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
            <span>Pengaturan Operator</span>
          </div>
        </div>
        <div class="drawer-menu-item" data-page="profil">
          <div class="drawer-menu-item-left">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span>Profil Saya</span>
          </div>
        </div>
      </div>

      <div class="drawer-footer">
        <a href="{{ url('/') }}" class="drawer-menu-item" style="background:#f8fafc;border:1px solid #e2e8f0;">
          <div class="drawer-menu-item-left">
            <svg viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
            <span style="color:#2563eb;font-weight:700;">Versi Desktop Lengkap</span>
          </div>
        </a>
        <div class="drawer-menu-item" style="color:#dc2626;" onclick="showToast('Sesi akun tetap aman di perangkat lokal.', 'info')">
          <div class="drawer-menu-item-left">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            <span>Keluar Sesi</span>
          </div>
        </div>
      </div>
    </aside>

    <!-- Konten Scrollable -->
    <main id="mobileMain" class="mobile-main">

      <!-- ==========================================
           HALAMAN 1: DASHBOARD (BERANDA UTAMA)
           ========================================== -->
      <section id="page-dashboard" class="tab-pane active">

        <!-- Progress Target Suara Dapil -->
        <div class="progress-widget">
          <div class="progress-header">
            <span class="progress-title">Progres Target Suara Dapil</span>
            <span id="progressTargetPct" class="progress-target-text" style="font-weight:800;color:#fbbf24;">0%</span>
          </div>
          <div class="progress-bar-bg">
            <div id="progressTargetFill" class="progress-bar-fill" style="width: 0%;"></div>
          </div>
          <div class="progress-footer">
            <span id="progressTargetSub">0 / 25.000 Suara</span>
            <span>Target: 25.000 Suara</span>
          </div>
        </div>

        <!-- Quick Shortcut Grid (8 Akses Cepat Modul) -->
        <div class="shortcut-grid">
          <div class="shortcut-tile" onclick="navigatePage('pendukung')">
            <div class="shortcut-icon" style="background:#eff6ff;color:#2563eb;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <span class="shortcut-label">Pendukung</span>
          </div>

          <div class="shortcut-tile" onclick="navigatePage('input')">
            <div class="shortcut-icon" style="background:#fef3c7;color:#d97706;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            </div>
            <span class="shortcut-label">Entri 5 Jalur</span>
          </div>

          <div class="shortcut-tile" onclick="navigatePage('aspirasi')">
            <div class="shortcut-icon" style="background:#f0fdf4;color:#16a34a;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            </div>
            <span class="shortcut-label">Aspirasi</span>
          </div>

          <div class="shortcut-tile" onclick="navigatePage('reses')">
            <div class="shortcut-icon" style="background:#e0f2fe;color:#0284c7;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/></svg>
            </div>
            <span class="shortcut-label">Pokir APBD</span>
          </div>

          <div class="shortcut-tile" onclick="navigatePage('peta')">
            <div class="shortcut-icon" style="background:#faf5ff;color:#9333ea;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" x2="9" y1="3" y2="18"/><line x1="15" x2="15" y1="6" y2="21"/></svg>
            </div>
            <span class="shortcut-label">Peta Sebaran</span>
          </div>

          <div class="shortcut-tile" onclick="navigatePage('leaderboard')">
            <div class="shortcut-icon" style="background:#fff7ed;color:#ea580c;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
            </div>
            <span class="shortcut-label">Leaderboard</span>
          </div>

          <div class="shortcut-tile" onclick="navigatePage('riwayat')">
            <div class="shortcut-icon" style="background:#f1f5f9;color:#475569;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </div>
            <span class="shortcut-label">Log Riwayat</span>
          </div>

          <div class="shortcut-tile" onclick="navigatePage('pengaturan')">
            <div class="shortcut-icon" style="background:#fdf2f8;color:#db2777;">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
            </div>
            <span class="shortcut-label">Pengaturan</span>
          </div>
        </div>

        <!-- KPI Grid 2x2 -->
        <div class="kpi-grid">
          <div class="kpi-card">
            <div class="kpi-header">
              <span class="kpi-title">Total Pendukung</span>
              <div class="kpi-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
              </div>
            </div>
            <div id="kpiTotalPendukung" class="kpi-value">0</div>
            <div class="kpi-subtext">Warga Dapil Terdata</div>
          </div>

          <div class="kpi-card green">
            <div class="kpi-header">
              <span class="kpi-title">Terverifikasi</span>
              <div class="kpi-icon green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
              </div>
            </div>
            <div id="kpiTerverifikasi" class="kpi-value">0</div>
            <div class="kpi-subtext">KTP &amp; Berkas Valid</div>
          </div>

          <div class="kpi-card gold">
            <div class="kpi-header">
              <span class="kpi-title">Aspirasi Warga</span>
              <div class="kpi-icon gold">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              </div>
            </div>
            <div id="kpiTotalAspirasi" class="kpi-value">0</div>
            <div class="kpi-subtext">Usulan Lapangan</div>
          </div>

          <div class="kpi-card info">
            <div class="kpi-header">
              <span class="kpi-title">Pokir APBD</span>
              <div class="kpi-icon info">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/></svg>
              </div>
            </div>
            <div id="kpiTotalPokir" class="kpi-value">0</div>
            <div class="kpi-subtext">Program Dewan 2025</div>
          </div>
        </div>

        <!-- Sebaran Suara 3 Kecamatan -->
        <div class="card">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
            <span style="font-size:14px;font-weight:700;color:#0f172a;">Sebaran 3 Kecamatan Dapil</span>
            <span style="font-size:11px;font-weight:600;color:#2563eb;background:#eff6ff;padding:2px 8px;border-radius:999px;">Kraksaan Raya</span>
          </div>

          <div style="display:flex;flex-direction:column;gap:10px;">
            <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px dashed #e2e8f0;">
              <div>
                <div style="font-size:13px;font-weight:700;color:#0f172a;">Kecamatan Kraksaan</div>
                <div style="font-size:11px;color:#64748b;">Ibu Kota Dapil &bull; 13 Desa/Kelurahan</div>
              </div>
              <span id="kecKraksaanVal" style="font-size:15px;font-weight:800;color:#16225e;">0</span>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px dashed #e2e8f0;">
              <div>
                <div style="font-size:13px;font-weight:700;color:#0f172a;">Kecamatan Besuk</div>
                <div style="font-size:11px;color:#64748b;">Wilayah Penyangga &bull; 9 Desa</div>
              </div>
              <span id="kecBesukVal" style="font-size:15px;font-weight:800;color:#16225e;">0</span>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;">
              <div>
                <div style="font-size:13px;font-weight:700;color:#0f172a;">Kecamatan Gading</div>
                <div style="font-size:11px;color:#64748b;">Wilayah Selatan &bull; 9 Desa</div>
              </div>
              <span id="kecGadingVal" style="font-size:15px;font-weight:800;color:#16225e;">0</span>
            </div>
          </div>
        </div>

      </section>

      <!-- ==========================================
           HALAMAN 2: DATA PENDUKUNG
           ========================================== -->
      <section id="page-pendukung" class="tab-pane">
        <div class="page-title-banner">
          <div>
            <div class="page-title-text">Database Pendukung</div>
            <div class="page-subtitle-text">Dapil Kraksaan Raya &bull; Terintegrasi NIK</div>
          </div>
          <button type="button" class="btn-outline-touch" onclick="navigatePage('input')" style="height:36px;font-size:12px;padding:0 10px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah
          </button>
        </div>

        <!-- Search & Filter Controls -->
        <div class="search-filter-wrapper">
          <div class="search-input-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" id="searchSupporterInput" class="search-input-mobile" placeholder="Cari nama, NIK, atau desa...">
            <button type="button" id="btnClearSearch" class="search-clear-btn">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </div>

          <div class="filter-pills-scroll">
            <button type="button" class="filter-pill active" data-filter-type="all" data-filter-val="">Semua</button>
            <button type="button" class="filter-pill" data-filter-type="status" data-filter-val="valid">Terverifikasi</button>
            <button type="button" class="filter-pill" data-filter-type="status" data-filter-val="pending">Pending</button>
            <button type="button" class="filter-pill" data-filter-type="kecamatan" data-filter-val="Kraksaan">Kraksaan</button>
            <button type="button" class="filter-pill" data-filter-type="kecamatan" data-filter-val="Besuk">Besuk</button>
            <button type="button" class="filter-pill" data-filter-type="kecamatan" data-filter-val="Gading">Gading</button>
          </div>
        </div>

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;padding:0 2px;">
          <span style="font-size:12px;font-weight:700;color:#64748b;">HASIL PENCARIAN</span>
          <span id="supporterResultsCount" style="font-size:11px;font-weight:600;color:#64748b;background:#e2e8f0;padding:2px 8px;border-radius:999px;">0 Data</span>
        </div>

        <!-- Kontainer Touch Cards -->
        <div id="supportersCardList"></div>
      </section>

      <!-- ==========================================
           HALAMAN 3: ENTRI 5 JALUR
           ========================================== -->
      <section id="page-input" class="tab-pane">
        <div class="page-title-banner">
          <div>
            <div class="page-title-text">Entri 5 Jalur Terpadu</div>
            <div class="page-subtitle-text">Pilih metode pendataan sesuai kondisi lapangan</div>
          </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:20px;">
          <div class="touch-card" onclick="openEntryForm('mandiri', 'Jalur 1: Formulir Kilat &amp; AI OCR KTP')">
            <div class="card-top-row">
              <div class="card-avatar" style="background:#eff6ff;color:#2563eb;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
              </div>
              <div class="card-info">
                <div class="card-name">Jalur 1: Formulir Kilat &amp; AI OCR</div>
                <div class="card-nik" style="font-family:inherit;">Pindai fisik KTP otomatis dan kunci GPS</div>
              </div>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
          </div>

          <div class="touch-card" onclick="openEntryForm('struktur', 'Jalur 2: Struktur Relawan Kordes / Korcam')">
            <div class="card-top-row">
              <div class="card-avatar" style="background:#fef3c7;color:#d97706;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
              </div>
              <div class="card-info">
                <div class="card-name">Jalur 2: Struktur Kordes / Korcam</div>
                <div class="card-nik" style="font-family:inherit;">Input berjenjang dari koordinator desa/kecamatan</div>
              </div>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
          </div>

          <div class="touch-card" onclick="openEntryForm('tokoh', 'Jalur 3: Tokoh Masyarakat &amp; Kyai')">
            <div class="card-top-row">
              <div class="card-avatar" style="background:#f0fdf4;color:#16a34a;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
              </div>
              <div class="card-info">
                <div class="card-name">Jalur 3: Tokoh Masyarakat &amp; Kyai</div>
                <div class="card-nik" style="font-family:inherit;">Rekomendasi sesepuh, ustadz, dan paguyuban</div>
              </div>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
          </div>

          <div class="touch-card" onclick="openEntryForm('saksi', 'Jalur 4: Saksi TPS Terdata')">
            <div class="card-top-row">
              <div class="card-avatar" style="background:#fdf2f8;color:#db2777;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>
              </div>
              <div class="card-info">
                <div class="card-name">Jalur 4: Saksi TPS Terdata</div>
                <div class="card-nik" style="font-family:inherit;">Pendukung berkomitmen bertugas di TPS</div>
              </div>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
          </div>

          <div class="touch-card" onclick="openEntryForm('simpatisan', 'Jalur 5: Relawan Mandiri &amp; Simpatisan')">
            <div class="card-top-row">
              <div class="card-avatar" style="background:#faf5ff;color:#9333ea;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
              </div>
              <div class="card-info">
                <div class="card-name">Jalur 5: Relawan &amp; Simpatisan</div>
                <div class="card-nik" style="font-family:inherit;">Pendaftaran mandiri masyarakat pendukung Gus Dim</div>
              </div>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
          </div>
        </div>
      </section>

      <!-- ==========================================
           HALAMAN 4: ASPIRASI WARGA
           ========================================== -->
      <section id="page-aspirasi" class="tab-pane">
        <div class="page-title-banner">
          <div>
            <div class="page-title-text">Aspirasi Warga</div>
            <div class="page-subtitle-text">Jaring masukan konstituen Dapil Kraksaan Raya</div>
          </div>
          <button type="button" class="btn-outline-touch" onclick="openAspirasiForm()" style="height:36px;font-size:12px;padding:0 10px;background:#f0fdf4;color:#16a34a;border-color:#bbf7d0;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Buat Aspirasi
          </button>
        </div>

        <div class="filter-pills-scroll" style="margin-bottom:12px;">
          <button type="button" class="filter-pill active" onclick="filterAspirasiCat('', this)">Semua</button>
          <button type="button" class="filter-pill" onclick="filterAspirasiCat('Infrastruktur', this)">Infrastruktur</button>
          <button type="button" class="filter-pill" onclick="filterAspirasiCat('Pertanian', this)">Pertanian</button>
          <button type="button" class="filter-pill" onclick="filterAspirasiCat('Pendidikan', this)">Pendidikan</button>
          <button type="button" class="filter-pill" onclick="filterAspirasiCat('UMKM', this)">UMKM &amp; Ekonomi</button>
        </div>

        <div id="aspirasiDedicatedList"></div>
      </section>

      <!-- ==========================================
           HALAMAN 5: RESES & POKIR APBD
           ========================================== -->
      <section id="page-reses" class="tab-pane">
        <div class="page-title-banner">
          <div>
            <div class="page-title-text">Reses &amp; Pokir APBD</div>
            <div class="page-subtitle-text">Pengawalan usulan dewan ke program daerah</div>
          </div>
          <button type="button" class="btn-outline-touch" onclick="openPokirForm()" style="height:36px;font-size:12px;padding:0 10px;background:#eff6ff;color:#2563eb;border-color:#bfdbfe;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Usul Pokir
          </button>
        </div>

        <div class="segmented-control">
          <button type="button" class="segment-btn active" data-reses-tab="pokir" onclick="switchResesSubTab('pokir', this)">Usulan Pokir APBD</button>
          <button type="button" class="segment-btn" data-reses-tab="laporan" onclick="switchResesSubTab('laporan', this)">Laporan Reses Dewan</button>
        </div>

        <div id="pokirDedicatedList"></div>
      </section>

      <!-- ==========================================
           HALAMAN 6: PETA SEBARAN (GIS MOBILE)
           ========================================== -->
      <section id="page-peta" class="tab-pane">
        <div class="page-title-banner">
          <div>
            <div class="page-title-text">Peta Sebaran Dapil</div>
            <div class="page-subtitle-text">Pemetaan wilayah Kraksaan &bull; Besuk &bull; Gading</div>
          </div>
        </div>

        <!-- Sebaran 3 Kecamatan Cards -->
        <div class="geo-district-card">
          <div class="geo-district-header">
            <span class="geo-district-name">Kecamatan Kraksaan</span>
            <span class="badge-status valid">Basis Utama</span>
          </div>
          <p style="font-size:12px;color:#64748b;margin-bottom:8px;">Total 13 Desa/Kelurahan &bull; Pusat Komando Pemenangan</p>
          <div class="geo-village-grid" id="gridDesaKraksaan"></div>
        </div>

        <div class="geo-district-card">
          <div class="geo-district-header">
            <span class="geo-district-name">Kecamatan Besuk</span>
            <span class="badge-status pending">Penyangga</span>
          </div>
          <p style="font-size:12px;color:#64748b;margin-bottom:8px;">Total 9 Desa &bull; Basis Petani &amp; Jaringan Desa</p>
          <div class="geo-village-grid" id="gridDesaBesuk"></div>
        </div>

        <div class="geo-district-card">
          <div class="geo-district-header">
            <span class="geo-district-name">Kecamatan Gading</span>
            <span class="badge-status info">Wilayah Selatan</span>
          </div>
          <p style="font-size:12px;color:#64748b;margin-bottom:8px;">Total 9 Desa &bull; Jaringan Tokoh Agama &amp; Kyai</p>
          <div class="geo-village-grid" id="gridDesaGading"></div>
        </div>
      </section>

      <!-- ==========================================
           HALAMAN 7: LEADERBOARD RELAWAN
           ========================================== -->
      <section id="page-leaderboard" class="tab-pane">
        <div class="page-title-banner">
          <div>
            <div class="page-title-text">Leaderboard Tim</div>
            <div class="page-subtitle-text">Peringkat produktivitas relawan lapangan</div>
          </div>
        </div>

        <!-- Podium Top 3 -->
        <div class="podium-container">
          <!-- Rank 2 Perak -->
          <div class="podium-item second">
            <div class="podium-avatar-box">
              <div class="podium-avatar">AM</div>
              <div class="podium-badge">2</div>
            </div>
            <div class="podium-name">Ahmad M.</div>
            <div class="podium-score">842 Data</div>
            <div class="podium-pillar">2</div>
          </div>

          <!-- Rank 1 Emas -->
          <div class="podium-item first">
            <div class="podium-avatar-box">
              <div class="podium-avatar" style="color:#b45309;background:#fef3c7;">HK</div>
              <div class="podium-badge">1</div>
            </div>
            <div class="podium-name">H. Kholiq</div>
            <div class="podium-score">1.250 Data</div>
            <div class="podium-pillar">1</div>
          </div>

          <!-- Rank 3 Perunggu -->
          <div class="podium-item third">
            <div class="podium-avatar-box">
              <div class="podium-avatar">FR</div>
              <div class="podium-badge">3</div>
            </div>
            <div class="podium-name">Fathur R.</div>
            <div class="podium-score">615 Data</div>
            <div class="podium-pillar">3</div>
          </div>
        </div>

        <!-- Daftar Peringkat Tambahan -->
        <div style="font-size:12px;font-weight:700;color:#64748b;margin-bottom:8px;">PERINGKAT KORCAM &amp; KORDES</div>
        <div id="leaderboardList"></div>
      </section>

      <!-- ==========================================
           HALAMAN 8: RIWAYAT & LOG AUDIT
           ========================================== -->
      <section id="page-riwayat" class="tab-pane">
        <div class="page-title-banner">
          <div>
            <div class="page-title-text">Riwayat &amp; Log Audit</div>
            <div class="page-subtitle-text">Rekam jejak digital aktivitas lapangan</div>
          </div>
        </div>

        <div class="timeline-list" id="auditTimelineList"></div>
      </section>

      <!-- ==========================================
           HALAMAN 9: PENGATURAN OPERATOR & SISTEM
           ========================================== -->
      <section id="page-pengaturan" class="tab-pane">
        <div class="page-title-banner">
          <div>
            <div class="page-title-text">Pengaturan Sistem</div>
            <div class="page-subtitle-text">Manajemen operator &amp; preferensi aplikasi</div>
          </div>
        </div>

        <div class="card">
          <div style="font-size:14px;font-weight:700;margin-bottom:8px;">Daftar Akun Petugas Lapangan</div>
          <div style="display:flex;flex-direction:column;gap:10px;" id="operatorList"></div>
        </div>

        <div class="card">
          <div style="font-size:14px;font-weight:700;margin-bottom:8px;">Mode Penyimpanan &amp; Offline PWA</div>
          <p style="font-size:12px;color:#64748b;margin-bottom:12px;">Data form dan cache antarmuka disimpan di penyimpanan lokal perangkat smartphone untuk keandalan di lapangan.</p>
          <button type="button" class="btn-outline-touch" style="width:100%;" onclick="clearPwaCache()">
            Perbarui Cache Aplikasi &amp; Muat Ulang
          </button>
        </div>
      </section>

      <!-- ==========================================
           HALAMAN 10: PROFIL SAYA
           ========================================== -->
      <section id="page-profil" class="tab-pane">
        <div class="card" style="text-align:center;padding:20px 16px;">
          <div style="width:64px;height:64px;border-radius:9999px;background:#16225e;color:#fff;font-size:24px;font-weight:800;display:inline-flex;align-items:center;justify-content:center;margin-bottom:10px;border:3px solid #f59e0b;">
            PL
          </div>
          <div style="font-size:17px;font-weight:800;color:#0f172a;">Petugas Lapangan &amp; Relawan</div>
          <div style="font-size:12px;color:#2563eb;font-weight:600;margin-top:2px;">Koordinator Lapangan Dapil Kraksaan Raya</div>
          <div style="display:inline-block;background:#f1f5f9;padding:4px 12px;border-radius:999px;font-size:11px;color:#475569;margin-top:8px;">
            Cakupan: Kraksaan &bull; Besuk &bull; Gading
          </div>
        </div>

        <div class="card">
          <div style="font-size:14px;font-weight:700;margin-bottom:12px;">Statistik Pribadi Relawan</div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
            <div style="background:#f8fafc;padding:12px;border-radius:8px;border:1px solid #e2e8f0;">
              <div style="font-size:11px;color:#64748b;">Total Input Saya</div>
              <div style="font-size:18px;font-weight:800;color:#16225e;" id="myInputCount">42 Data</div>
            </div>
            <div style="background:#f8fafc;padding:12px;border-radius:8px;border:1px solid #e2e8f0;">
              <div style="font-size:11px;color:#64748b;">Akurasi Validasi</div>
              <div style="font-size:18px;font-weight:800;color:#16a34a;">98% Valid</div>
            </div>
          </div>
        </div>

        <div class="card">
          <div style="font-size:14px;font-weight:700;margin-bottom:12px;">Keamanan Kata Sandi</div>
          <form onsubmit="event.preventDefault(); showToast('Kata sandi berhasil diperbarui.', 'success');">
            <div class="form-group">
              <label class="form-label">Kata Sandi Baru</label>
              <input type="password" class="form-input-touch" placeholder="Minimal 6 karakter" required>
            </div>
            <button type="submit" class="btn-primary-touch" style="height:44px;font-size:14px;">
              Simpan Perubahan Sandi
            </button>
          </form>
        </div>

        <div style="text-align:center;padding:16px 0;font-size:11px;color:#94a3b8;">
          Gus Dim Mobile v2.5.0 PWA &bull; Dapil Kraksaan Raya<br>
          Fraksi Partai NasDem DPRD Kabupaten Probolinggo
        </div>
      </section>

    </main>

    <!-- ==========================================
         BOTTOM NAVIGATION BAR (5 TAB + CENTER FAB)
         ========================================== -->
    <nav class="mobile-bottom-nav">
      <a href="#dashboard" class="nav-item active" data-page="dashboard">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <span>Beranda</span>
      </a>

      <a href="#pendukung" class="nav-item" data-page="pendukung">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        <span>Pendukung</span>
      </a>

      <!-- Center Raised Floating Action Button -->
      <div class="nav-fab-container">
        <button type="button" id="fabCenter" class="nav-fab" title="Entri Pendukung Cepat">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        </button>
      </div>

      <a href="#aspirasi" class="nav-item" data-page="aspirasi">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        <span>Aspirasi</span>
      </a>

      <a href="#drawer" id="btnBottomMenu" class="nav-item" data-page="menu">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
        <span>Menu</span>
      </a>
    </nav>

    <!-- ==========================================
         BOTTOM SHEET DRAWER (MODAL SENTUH)
         ========================================== -->
    <div id="sheetBackdrop" class="sheet-backdrop"></div>
    <div id="bottomSheet" class="bottom-sheet">
      <div class="sheet-handle-bar">
        <div class="sheet-handle"></div>
      </div>
      <div class="sheet-header">
        <span id="sheetTitle" class="sheet-title">Detail Data</span>
        <button type="button" class="sheet-close-btn" onclick="closeBottomSheet()">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <div id="sheetContent" class="sheet-content"></div>
    </div>

    <!-- Toast Notification -->
    <div id="mobileToast" class="mobile-toast"></div>
  </div>

  <script src="assets/js/mobile.js"></script>
</body>
</html>