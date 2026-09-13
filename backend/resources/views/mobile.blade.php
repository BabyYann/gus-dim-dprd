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
        <div class="header-brand">
          <img src="assets/icons/gus-dim.png" alt="Gus Dim" class="header-avatar">
          <div class="header-title-box">
            <span class="header-app-title">GUS DIM MOBILE</span>
            <span class="header-badge-dapil">Dapil Kraksaan Raya</span>
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

    <!-- Konten Scrollable -->
    <main id="mobileMain" class="mobile-main">

      <!-- ==========================================
           TAB 1: BERANDA (DASHBOARD & KPI)
           ========================================== -->
      <section id="tab-beranda" class="tab-pane active">

        <!-- Progress Target Suara Dapil -->
        <div class="progress-widget">
          <div class="progress-header">
            <span class="progress-title">Progres Target Suara Dapil</span>
            <span id="progressTargetPct" class="progress-target-text" style="font-weight:700;color:#fbbf24;">0%</span>
          </div>
          <div class="progress-bar-bg">
            <div id="progressTargetFill" class="progress-bar-fill" style="width: 0%;"></div>
          </div>
          <div class="progress-footer">
            <span id="progressTargetSub">0 / 25.000 Suara</span>
            <span>Target: 25.000 Suara</span>
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
            <div class="kpi-subtext">KTP & Berkas Valid</div>
          </div>

          <div class="kpi-card gold">
            <div class="kpi-header">
              <span class="kpi-title">Aspirasi Warga</span>
              <div class="kpi-icon gold">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>
              </div>
            </div>
            <div id="kpiTotalAspirasi" class="kpi-value">0</div>
            <div class="kpi-subtext">Usulan Lapangan</div>
          </div>

          <div class="kpi-card info">
            <div class="kpi-header">
              <span class="kpi-title">Pokir APBD</span>
              <div class="kpi-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/></svg>
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
                <div style="font-size:11px;color:#64748b;">Ibu Kota Dapil & Pusat Basis</div>
              </div>
              <span id="kecKraksaanVal" style="font-size:15px;font-weight:800;color:#16225e;">0</span>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px dashed #e2e8f0;">
              <div>
                <div style="font-size:13px;font-weight:700;color:#0f172a;">Kecamatan Besuk</div>
                <div style="font-size:11px;color:#64748b;">Wilayah Penyangga Pertanian</div>
              </div>
              <span id="kecBesukVal" style="font-size:15px;font-weight:800;color:#16225e;">0</span>
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;">
              <div>
                <div style="font-size:13px;font-weight:700;color:#0f172a;">Kecamatan Gading</div>
                <div style="font-size:11px;color:#64748b;">Wilayah Selatan & Tokoh Masyarakat</div>
              </div>
              <span id="kecGadingVal" style="font-size:15px;font-weight:800;color:#16225e;">0</span>
            </div>
          </div>
        </div>

        <!-- Banner Entri Cepat Lapangan -->
        <div class="card" style="background:linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);border-color:#bfdbfe;cursor:pointer;" onclick="openJalurBottomSheet()">
          <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:44px;height:44px;border-radius:12px;background:#2563eb;color:#fff;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </div>
            <div style="flex:1;">
              <div style="font-size:14px;font-weight:700;color:#1e40af;">Entri Pendukung Lapangan Cepat</div>
              <div style="font-size:12px;color:#3b82f6;">Pilih 5 jalur: Mandiri AI OCR, Struktur, Tokoh, Saksi, Simpatisan</div>
            </div>
          </div>
        </div>

      </section>

      <!-- ==========================================
           TAB 2: PENDUKUNG (TOUCH CARDS & PENCARIAN)
           ========================================== -->
      <section id="tab-pendukung" class="tab-pane">

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

        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;padding:0 4px;">
          <span style="font-size:13px;font-weight:700;color:#475569;">Daftar Pendukung</span>
          <span id="supporterResultsCount" style="font-size:11px;font-weight:600;color:#64748b;background:#e2e8f0;padding:2px 8px;border-radius:999px;">0 Data</span>
        </div>

        <!-- Kontainer Touch Cards -->
        <div id="supportersCardList">
          <!-- Diisi otomatis oleh mobile.js -->
        </div>

      </section>

      <!-- ==========================================
           TAB 3: RESES & POKIR
           ========================================== -->
      <section id="tab-reses" class="tab-pane">

        <div class="segmented-control">
          <button type="button" class="segment-btn active" data-segment="aspirasi">Aspirasi Lapangan</button>
          <button type="button" class="segment-btn" data-segment="pokir">Usulan Pokir APBD</button>
        </div>

        <!-- Kontainer Reses / Aspirasi Cards -->
        <div id="resesCardList">
          <!-- Diisi otomatis oleh mobile.js -->
        </div>

      </section>

      <!-- ==========================================
           TAB 4: LAINNYA (MENU, LEADERBOARD, PROFIL)
           ========================================== -->
      <section id="tab-lainnya" class="tab-pane">

        <div class="card" style="margin-bottom:16px;">
          <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
            <div style="width:48px;height:48px;border-radius:9999px;background:#16225e;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:18px;">
              PL
            </div>
            <div>
              <div style="font-size:15px;font-weight:700;color:#0f172a;">Petugas Lapangan & Relawan</div>
              <div style="font-size:12px;color:#64748b;">Akun Lapangan Terverifikasi (Kordes/Korcam)</div>
            </div>
          </div>
          <div style="background:#f8fafc;padding:10px 12px;border-radius:8px;font-size:12px;color:#475569;">
            Wilayah Tugas: <strong>Dapil Kraksaan Raya (Kraksaan, Besuk, Gading)</strong>
          </div>
        </div>

        <!-- Menu Opsi -->
        <div style="display:flex;flex-direction:column;gap:8px;">
          <div class="touch-card" onclick="showToast('Modul Leaderboard: Korcam Kraksaan menduduki peringkat teratas!', 'info')">
            <div style="display:flex;align-items:center;justify-content:space-between;width:100%;">
              <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:36px;height:36px;border-radius:8px;background:#fef3c7;color:#d97706;display:flex;align-items:center;justify-content:center;">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>
                </div>
                <div>
                  <div style="font-size:14px;font-weight:700;color:#0f172a;">Leaderboard Relawan</div>
                  <div style="font-size:11px;color:#64748b;">Peringkat input tim pemenangan dapil</div>
                </div>
              </div>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
          </div>

          <div class="touch-card" onclick="showToast('Log audit aktif: 100% aktivitas entri terekam sistem.', 'info')">
            <div style="display:flex;align-items:center;justify-content:space-between;width:100%;">
              <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:36px;height:36px;border-radius:8px;background:#eff6ff;color:#2563eb;display:flex;align-items:center;justify-content:center;">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                <div>
                  <div style="font-size:14px;font-weight:700;color:#0f172a;">Riwayat Entri & Log Lapangan</div>
                  <div style="font-size:11px;color:#64748b;">Catatan waktu dan bukti input data</div>
                </div>
              </div>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
          </div>

          <a href="{{ url('/') }}" class="touch-card" style="text-decoration:none;">
            <div style="display:flex;align-items:center;justify-content:space-between;width:100%;">
              <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:36px;height:36px;border-radius:8px;background:#f0fdf4;color:#16a34a;display:flex;align-items:center;justify-content:center;">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                </div>
                <div>
                  <div style="font-size:14px;font-weight:700;color:#0f172a;">Buka Versi Desktop Lengkap</div>
                  <div style="font-size:11px;color:#64748b;">Akses tabel analitik, GIS multi-layer, dan ekspor</div>
                </div>
              </div>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
          </a>

          <div class="touch-card" style="border-color:#fecaca;" onclick="showToast('Sesi akun tetap aman di perangkat lokal.', 'info')">
            <div style="display:flex;align-items:center;justify-content:space-between;width:100%;">
              <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:36px;height:36px;border-radius:8px;background:#fee2e2;color:#dc2626;display:flex;align-items:center;justify-content:center;">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                </div>
                <div>
                  <div style="font-size:14px;font-weight:700;color:#dc2626;">Keluar Sesi (Logout)</div>
                  <div style="font-size:11px;color:#64748b;">Tutup akses akun pada perangkat ini</div>
                </div>
              </div>
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </div>
          </div>
        </div>

        <div style="text-align:center;padding:24px 0 12px 0;font-size:11px;color:#94a3b8;">
          Gus Dim Mobile v2.5.0 PWA &bull; Dapil Kraksaan Raya<br>
          Fraksi Partai NasDem DPRD
        </div>

      </section>

    </main>

    <!-- ==========================================
         BOTTOM NAVIGATION BAR (5 TAB + CENTER FAB)
         ========================================== -->
    <nav class="mobile-bottom-nav">
      <a href="#beranda" class="nav-item active" data-tab="beranda">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        <span>Beranda</span>
      </a>

      <a href="#pendukung" class="nav-item" data-tab="pendukung">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        <span>Pendukung</span>
      </a>

      <!-- Center Raised Floating Action Button -->
      <div class="nav-fab-container">
        <button type="button" id="fabCenter" class="nav-fab" title="Entri Pendukung Cepat">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        </button>
      </div>

      <a href="#reses" class="nav-item" data-tab="reses">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>
        <span>Reses</span>
      </a>

      <a href="#lainnya" class="nav-item" data-tab="lainnya">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
        <span>Lainnya</span>
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
      <div id="sheetContent" class="sheet-content">
        <!-- Konten dinamis bottom sheet -->
      </div>
    </div>

    <!-- Toast Notification -->
    <div id="mobileToast" class="mobile-toast"></div>
  </div>

  <script src="assets/js/mobile.js"></script>
</body>
</html>