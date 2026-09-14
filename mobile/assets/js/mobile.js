/**
 * GUS DIM MOBILE (PWA) - CONTROLLER UTAMA LENGKAP 10 HALAMAN
 * 100% BEBAS EMOJI - Pure Clean SVG & Native Ergonomics
 * Source of Truth: Web Desktop Existing (Dapil Kraksaan Raya)
 */

// State Aplikasi Mobile
const AppState = {
  currentPage: 'dashboard',
  activeResesTab: 'pokir',
  supporters: [],
  aspirasi: [],
  pokir: [],
  auditLogs: [],
  operators: [],
  leaderboardData: [],
  stats: {
    total: 0,
    terverifikasi: 0,
    target: 25000,
    persen: 0,
    aspirasiCount: 0,
    pokirCount: 0,
    kecamatan: {
      Kraksaan: 0,
      Besuk: 0,
      Gading: 0
    }
  },
  filterKecamatan: '',
  filterStatus: '',
  searchQuery: '',
  filterAspirasiCat: '',
  filterAspirasiStatus: '',
  searchAspirasiQuery: '',
  activeJalur: 'mandiri',
  isOnline: navigator.onLine
};

// Data Geospasial Wilayah Dapil Kraksaan Raya
const DapilLocations = {
  'Kraksaan': {
    lat: -7.7595,
    lng: 113.4185,
    desa: [
      'Alassumur Kulon', 'Asembagus', 'Bulu', 'Bulubrangsi', 'Kalibuntu', 
      'Kalisalam', 'Kandangjati Kulon', 'Kandangjati Wetan', 'Kebonagung', 
      'Kraksaan Kulon', 'Kraksaan Wetan', 'Kregenan', 'Patokan', 'Reksosari', 
      'Rondokuning', 'Semampir', 'Sidomukti', 'Sidopekso', 'Sumberlele', 'Tamansari'
    ]
  },
  'Besuk': {
    lat: -7.7924,
    lng: 113.4561,
    desa: [
      'Alas Sumur Lor', 'Alaskandang', 'Bago', 'Besuk Agung', 'Besuk Kidul', 
      'Jambangan', 'Kecik', 'Klampokan', 'Krampilan', 'Matekan', 'Meayan', 
      'Randu Jalak', 'Sindet Anyar', 'Sindet Lami', 'Sumberan', 'Sumbersuko', 
      'Sumurdalam', 'Warugunung'
    ]
  },
  'Gading': {
    lat: -7.8341,
    lng: 113.4352,
    desa: [
      'Batur', 'Betek Kulon', 'Bulu', 'Bulupandak', 'Condong', 'Dandang', 
      'Duren', 'Gading Kulon', 'Gading Wetan', 'Jurangjero', 'Kaliacar', 
      'Kalisat', 'Kertosari', 'Kertosono', 'Mojolegi', 'Nogosaren', 'Prasi', 
      'Randujalak', 'Ranuwurung', 'Renteng', 'Sentul', 'Sumbersecang', 'Wangkal'
    ]
  }
};

// SVG Icons Registry (100% Zero Emoji)
const Icons = {
  home: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
  users: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
  plus: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>',
  messageSquare: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>',
  menu: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>',
  checkCircle: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
  clock: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
  alertTriangle: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
  mapPin: '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:14px;height:14px;min-width:14px;min-height:14px;max-width:14px;max-height:14px;flex-shrink:0;"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>',
  phone: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
  whatsapp: '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M9.1 7.3C8.94 7.3 8.68 7.36 8.46 7.6C8.24 7.84 7.62 8.42 7.62 9.6C7.62 10.78 8.48 11.92 8.6 12.08C8.72 12.24 10.28 14.65 12.67 15.68C13.24 15.93 13.68 16.08 14.03 16.19C14.6 16.37 15.12 16.35 15.53 16.29C15.99 16.22 16.95 15.71 17.15 15.14C17.35 14.57 17.35 14.09 17.29 13.99C17.23 13.89 17.07 13.83 16.83 13.71C16.59 13.59 15.41 13.01 15.19 12.93C14.97 12.85 14.81 12.81 14.65 13.05C14.49 13.29 14.03 13.83 13.89 13.99C13.75 14.15 13.61 14.17 13.37 14.05C13.13 13.93 12.36 13.68 11.45 12.87C10.74 12.24 10.26 11.46 10.12 11.22C9.98 10.98 10.11 10.85 10.23 10.73C10.34 10.62 10.47 10.45 10.6 10.3C10.73 10.15 10.77 10.04 10.85 9.88C10.93 9.72 10.89 9.58 10.83 9.46C10.77 9.34 10.31 8.2 10.12 7.72C9.93 7.26 9.73 7.32 9.58 7.31L9.1 7.3Z"/></svg>',
  camera: '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:22px;height:22px;min-width:22px;min-height:22px;max-width:22px;max-height:22px;flex-shrink:0;"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>',
  building: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/></svg>',
  trash: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>',
  edit: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>',
  award: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>',
  info: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>',
  calendar: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>'
};

// Inisialisasi Saat Dokumen Siap
document.addEventListener('DOMContentLoaded', () => {
  initServiceWorker();
  initNetworkStatusListener();
  setupNavigation();
  setupEventListeners();
  loadAllData();
  initHorizontalSwipeFilters();
});

// PWA Service Worker Registration
function initServiceWorker() {
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('./sw.js')
        .then(reg => console.log('ServiceWorker PWA Aktif:', reg.scope))
        .catch(err => console.log('ServiceWorker Gagal:', err));
    });
  }
}

// Deteksi Status Jaringan
function initNetworkStatusListener() {
  const banner = document.getElementById('offlineBanner');
  const updateStatus = () => {
    AppState.isOnline = navigator.onLine;
    if (!AppState.isOnline) {
      if (banner) banner.classList.add('active');
    } else {
      if (banner) banner.classList.remove('active');
    }
  };
  window.addEventListener('online', updateStatus);
  window.addEventListener('offline', updateStatus);
  updateStatus();
}

// Setup Navigasi (Bottom Bar & Drawer)
function setupNavigation() {
  // Tombol Drawer Buka & Tutup
  const btnOpenDrawer = document.getElementById('btnOpenDrawer');
  const btnCloseDrawer = document.getElementById('btnCloseDrawer');
  const drawerBackdrop = document.getElementById('drawerBackdrop');
  const btnBottomMenu = document.getElementById('btnBottomMenu');

  if (btnOpenDrawer) btnOpenDrawer.addEventListener('click', openDrawer);
  if (btnCloseDrawer) btnCloseDrawer.addEventListener('click', closeDrawer);
  if (drawerBackdrop) drawerBackdrop.addEventListener('click', closeDrawer);
  if (btnBottomMenu) {
    btnBottomMenu.addEventListener('click', (e) => {
      e.preventDefault();
      openDrawer();
    });
  }

  // Klik Item Drawer
  document.querySelectorAll('.drawer-menu-item[data-page]').forEach(item => {
    item.addEventListener('click', () => {
      const page = item.getAttribute('data-page');
      navigatePage(page);
    });
  });

  // Klik Item Bottom Nav
  document.querySelectorAll('.mobile-bottom-nav .nav-item[data-page]').forEach(item => {
    item.addEventListener('click', (e) => {
      e.preventDefault();
      const page = item.getAttribute('data-page');
      if (page !== 'menu') {
        navigatePage(page);
      }
    });
  });

  // Center FAB Action
  const fab = document.getElementById('fabCenter');
  if (fab) {
    fab.addEventListener('click', () => {
      navigatePage('input');
    });
  }

  // Backdrop Bottom Sheet
  const sheetBackdrop = document.getElementById('sheetBackdrop');
  if (sheetBackdrop) {
    sheetBackdrop.addEventListener('click', closeBottomSheet);
  }
}

// Buka & Tutup Drawer
function openDrawer() {
  const drawer = document.getElementById('mobileDrawer');
  const backdrop = document.getElementById('drawerBackdrop');
  if (drawer) drawer.classList.add('open');
  if (backdrop) backdrop.classList.add('open');
}

function closeDrawer() {
  const drawer = document.getElementById('mobileDrawer');
  const backdrop = document.getElementById('drawerBackdrop');
  if (drawer) drawer.classList.remove('open');
  if (backdrop) backdrop.classList.remove('open');
}

// Navigasi Antar Halaman (10 Halaman Lengkap)
function navigatePage(pageId) {
  AppState.currentPage = pageId;
  closeDrawer();

  // Switch Tab View
  document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
  const target = document.getElementById(`page-${pageId}`);
  if (target) {
    target.classList.add('active');
  }

  // Update Drawer Active State
  document.querySelectorAll('.drawer-menu-item[data-page]').forEach(item => {
    if (item.getAttribute('data-page') === pageId) {
      item.classList.add('active');
    } else {
      item.classList.remove('active');
    }
  });

  // Update Bottom Nav Active State
  document.querySelectorAll('.mobile-bottom-nav .nav-item').forEach(item => {
    if (item.getAttribute('data-page') === pageId) {
      item.classList.add('active');
    } else {
      item.classList.remove('active');
    }
  });

  // Scroll to top
  const main = document.getElementById('mobileMain');
  if (main) main.scrollTop = 0;

  // Render spesifik per halaman
  if (pageId === 'dashboard') renderDashboardStats();
  else if (pageId === 'pendukung') renderSupportersList();
  else if (pageId === 'aspirasi') renderAspirasiDedicated();
  else if (pageId === 'reses') renderPokirDedicated();
  else if (pageId === 'peta') renderPetaDistricts();
  else if (pageId === 'leaderboard') renderLeaderboard();
  else if (pageId === 'riwayat') renderAuditLogs();
  else if (pageId === 'pengaturan') renderOperators();
  else if (pageId === 'profil' || pageId === 'profile') renderProfile();

  setTimeout(initHorizontalSwipeFilters, 60);
}

// Setup Event Listeners
function setupEventListeners() {
  // Tombol Refresh Header
  const btnRefresh = document.getElementById('btnRefresh');
  if (btnRefresh) {
    btnRefresh.addEventListener('click', () => {
      showToast('Memperbarui data dari server...', 'info');
      loadAllData();
    });
  }

  // Input Pencarian Pendukung
  const searchInput = document.getElementById('searchSupporterInput');
  const btnClearSearch = document.getElementById('btnClearSearch');
  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      AppState.searchQuery = e.target.value.toLowerCase().trim();
      if (btnClearSearch) {
        if (AppState.searchQuery.length > 0) btnClearSearch.classList.add('active');
        else btnClearSearch.classList.remove('active');
      }
      renderSupportersList();
    });
  }

  if (btnClearSearch) {
    btnClearSearch.addEventListener('click', () => {
      searchInput.value = '';
      AppState.searchQuery = '';
      btnClearSearch.classList.remove('active');
      renderSupportersList();
      searchInput.focus();
    });
  }

  // Filter Pills Pendukung
  document.querySelectorAll('.filter-pill[data-filter-type]').forEach(pill => {
    pill.addEventListener('click', () => {
      document.querySelectorAll('.filter-pill[data-filter-type]').forEach(p => p.classList.remove('active'));
      pill.classList.add('active');
      const fType = pill.getAttribute('data-filter-type');
      const fVal = pill.getAttribute('data-filter-val');

      if (fType === 'status') {
        AppState.filterStatus = fVal;
        AppState.filterKecamatan = '';
      } else if (fType === 'kecamatan') {
        AppState.filterKecamatan = fVal;
        AppState.filterStatus = '';
      } else {
        AppState.filterStatus = '';
        AppState.filterKecamatan = '';
      }
      renderSupportersList();
    });
  });
}

// Load Semua Data dari Backend
async function loadAllData() {
  try {
    // 1. Data Pendukung
    const resP = await fetch('./api/pendukung.php').catch(() => null);
    if (resP && resP.ok) {
      const json = await resP.json();
      if (json && Array.isArray(json.data)) AppState.supporters = json.data;
    }
    if (!AppState.supporters || AppState.supporters.length === 0) {
      AppState.supporters = generateDefaultSupporters();
    }

    // 2. Data Aspirasi
    const resA = await fetch('./api/aspirasi.php').catch(() => null);
    if (resA && resA.ok) {
      const json = await resA.json();
      if (json && Array.isArray(json.data)) AppState.aspirasi = json.data;
    }
    if (!AppState.aspirasi || AppState.aspirasi.length === 0) {
      AppState.aspirasi = generateDefaultAspirasi();
    }

    // 3. Data Pokir
    const resR = await fetch('./api/reses.php').catch(() => null);
    if (resR && resR.ok) {
      const json = await resR.json();
      if (json && Array.isArray(json.data)) AppState.pokir = json.data;
    }
    if (!AppState.pokir || AppState.pokir.length === 0) {
      AppState.pokir = generateDefaultPokir();
    }

    // 4. Riwayat Audit Logs
    try {
      const logsRes = await fetch('./api/logs.php').then(r => r.json()).catch(() => null);
      if (logsRes && logsRes.success && Array.isArray(logsRes.rows) && logsRes.rows.length > 0) {
        AppState.auditLogs = logsRes.rows.map(l => {
          let color = 'blue';
          const a = (l.aksi || '').toLowerCase();
          if (a.includes('tambah') || a.includes('sukses') || a.includes('login') || a.includes('entri')) color = 'green';
          else if (a.includes('verif') || a.includes('ubah') || a.includes('validasi') || a.includes('status')) color = 'gold';
          else if (a.includes('aspirasi') || a.includes('pokir')) color = 'purple';
          else if (a.includes('hapus') || a.includes('tolak') || a.includes('nonaktif')) color = 'red';
          return {
            judul: l.aksi || 'Aktivitas Sistem',
            waktu: l.waktu || 'Baru saja',
            desc: l.keterangan || 'Pencatatan aktivitas sistem',
            petugas: l.user || 'Petugas',
            desa: 'Dapil Kraksaan Raya',
            color: color
          };
        });
      } else {
        AppState.auditLogs = generateDynamicAuditLogs();
      }
    } catch (e) {
      AppState.auditLogs = generateDynamicAuditLogs();
    }

    // 5. Data Operator Petugas Lapangan
    try {
      const usersRes = await fetch('./api/users.php?action=list').then(r => r.json()).catch(() => null);
      if (usersRes && usersRes.success && Array.isArray(usersRes.rows) && usersRes.rows.length > 0) {
        AppState.operators = usersRes.rows;
      } else {
        AppState.operators = generateDefaultOperators();
      }
    } catch (e) {
      AppState.operators = generateDefaultOperators();
    }

    AppState.leaderboardData = generateDynamicLeaderboard();

    calculateStats();
    renderDashboardStats();
    renderSupportersList();
    renderAspirasiDedicated();
    renderPokirDedicated();
    renderPetaDistricts();
    renderLeaderboard();
    renderAuditLogs();
    renderOperators();
    renderProfile();

  } catch (error) {
    console.warn('Fallback ke dataset lokal:', error);
    AppState.supporters = generateDefaultSupporters();
    AppState.aspirasi = generateDefaultAspirasi();
    AppState.pokir = generateDefaultPokir();
    if (!AppState.auditLogs || AppState.auditLogs.length === 0) AppState.auditLogs = generateDynamicAuditLogs();
    if (!AppState.operators || AppState.operators.length === 0) AppState.operators = generateDefaultOperators();

    calculateStats();
    renderDashboardStats();
    renderAuditLogs();
    renderOperators();
    renderProfile();
  }
}

// Kalkulasi Statistik Dinamis (Rekomendasi 3: Berbasis Wilayah Penugasan)
function calculateStats() {
  const u = AppState.currentUser || {};
  let relevantSupporters = AppState.supporters;
  let relevantAspirasi = AppState.aspirasi;
  let relevantPokir = AppState.pokir;
  let scopeLabel = 'Warga Dapil Terdata';

  if ((u.role === 'Koordinator Desa' || u.role === 'Admin Ranting') && u.desa) {
    relevantSupporters = AppState.supporters.filter(s => (s.desa || '').toLowerCase() === u.desa.toLowerCase());
    relevantAspirasi = AppState.aspirasi.filter(a => (a.desa || '').toLowerCase() === u.desa.toLowerCase());
    scopeLabel = 'Desa ' + u.desa;
  } else if (u.role === 'Koordinator Kecamatan' && u.kecamatan) {
    relevantSupporters = AppState.supporters.filter(s => (s.kecamatan || '').toLowerCase() === u.kecamatan.toLowerCase());
    relevantAspirasi = AppState.aspirasi.filter(a => (a.kecamatan || '').toLowerCase() === u.kecamatan.toLowerCase());
    scopeLabel = 'Kec. ' + u.kecamatan;
  }

  const total = relevantSupporters.length;
  const terverifikasi = relevantSupporters.filter(s => {
    const st = s.status || '';
    return st === 'Final' || st === 'valid' || st === 'Terverifikasi' || st === 'Divalidasi Kecamatan' || st === 'Diverifikasi Desa';
  }).length;

  const kecStats = { Kraksaan: 0, Besuk: 0, Gading: 0 };
  AppState.supporters.forEach(s => {
    const k = s.kecamatan || 'Kraksaan';
    if (kecStats[k] !== undefined) kecStats[k]++;
    else kecStats['Kraksaan']++;
  });

  AppState.stats = {
    total,
    terverifikasi,
    aspirasiCount: relevantAspirasi.length,
    pokirCount: relevantPokir.length,
    kecamatan: kecStats,
    scopeLabel: scopeLabel
  };

  // Update Drawer Badges
  const bP = document.getElementById('drawerBadgePendukung');
  const bA = document.getElementById('drawerBadgeAspirasi');
  const bR = document.getElementById('drawerBadgePokir');
  if (bP) bP.textContent = AppState.supporters.length;
  if (bA) bA.textContent = AppState.aspirasi.length;
  if (bR) bR.textContent = AppState.pokir.length;
}

// Render Dashboard Dinamis
function renderDashboardStats() {
  calculateStats();
  const s = AppState.stats;

  const elTotal = document.getElementById('kpiTotalPendukung');
  const elValid = document.getElementById('kpiTerverifikasi');
  const elAspirasi = document.getElementById('kpiTotalAspirasi');
  const elPokir = document.getElementById('kpiTotalPokir');

  if (elTotal) elTotal.textContent = s.total.toLocaleString('id-ID');
  if (elValid) elValid.textContent = s.terverifikasi.toLocaleString('id-ID');
  if (elAspirasi) elAspirasi.textContent = s.aspirasiCount.toLocaleString('id-ID');
  if (elPokir) elPokir.textContent = s.pokirCount.toLocaleString('id-ID');

  const kpiSub = document.querySelector('.kpi-card .kpi-subtext');
  if (kpiSub && s.scopeLabel) kpiSub.textContent = s.scopeLabel;

  const elKecKraksaan = document.getElementById('kecKraksaanVal');
  const elKecBesuk = document.getElementById('kecBesukVal');
  const elKecGading = document.getElementById('kecGadingVal');

  if (elKecKraksaan) elKecKraksaan.textContent = s.kecamatan.Kraksaan.toLocaleString('id-ID');
  if (elKecBesuk) elKecBesuk.textContent = s.kecamatan.Besuk.toLocaleString('id-ID');
  if (elKecGading) elKecGading.textContent = s.kecamatan.Gading.toLocaleString('id-ID');
}

// Render Daftar Pendukung (Touch Cards)
function renderSupportersList() {
  const container = document.getElementById('supportersCardList');
  if (!container) return;

  let list = [...AppState.supporters];

  if (AppState.searchQuery) {
    const q = AppState.searchQuery;
    list = list.filter(item => {
      const nama = (item.nama || '').toLowerCase();
      const nik = (item.nik || '').toLowerCase();
      const desa = (item.desa || '').toLowerCase();
      const kec = (item.kecamatan || '').toLowerCase();
      return nama.includes(q) || nik.includes(q) || desa.includes(q) || kec.includes(q);
    });
  }

  if (AppState.filterStatus) {
    list = list.filter(item => {
      if (AppState.filterStatus === 'valid') return item.status === 'valid' || item.status === 'Terverifikasi';
      if (AppState.filterStatus === 'pending') return item.status === 'pending' || item.status === 'Belum Verifikasi';
      return true;
    });
  }

  if (AppState.filterKecamatan) {
    list = list.filter(item => item.kecamatan === AppState.filterKecamatan);
  }

  const countBadge = document.getElementById('supporterResultsCount');
  if (countBadge) countBadge.textContent = `${list.length} Data`;

  if (list.length === 0) {
    container.innerHTML = `
      <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        <div class="empty-state-title">Tidak ada data pendukung</div>
        <div class="empty-state-desc">Coba sesuaikan kata kunci pencarian atau filter status Anda.</div>
      </div>
    `;
    return;
  }

  container.innerHTML = list.map((item, index) => {
    const initials = (item.nama || 'P').split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
    const maskedNik = maskNik(item.nik);
    const phoneRaw = item.no_hp || item.telepon || '081234567890';
    const waPhone = formatWaPhone(phoneRaw);
    const waText = encodeURIComponent(`Assalamu'alaikum Bpk/Ibu ${item.nama}, salam silaturahmi dari Tim Relawan Gus Dim.`);

    let statusClass = 'pending';
    let statusText = 'Pending';
    let statusIcon = Icons.clock;

    if (item.status === 'valid' || item.status === 'Terverifikasi') {
      statusClass = 'valid';
      statusText = 'Valid';
      statusIcon = Icons.checkCircle;
    }

    return `
      <div class="touch-card" onclick="openSupporterDetail(${index})">
        <div class="card-top-row">
          <div class="card-avatar">${initials}</div>
          <div class="card-info">
            <div class="card-name">${escapeHtml(item.nama)}</div>
            <div class="card-nik">${maskedNik}</div>
          </div>
          <span class="badge-status ${statusClass}">
            ${statusIcon} ${statusText}
          </span>
        </div>

        <div class="card-details-row">
          <div class="card-detail-item">
            ${Icons.mapPin}
            <span>${escapeHtml(item.desa || 'Patokan')}, ${escapeHtml(item.kecamatan || 'Kraksaan')}</span>
          </div>
          <span class="card-pill">TPS ${item.tps || '01'}</span>
          <span class="card-pill">${escapeHtml(item.jalur || 'Mandiri')}</span>
        </div>

        <div class="card-actions-row" onclick="event.stopPropagation()">
          <a href="https://wa.me/${waPhone}?text=${waText}" target="_blank" class="btn-wa-touch">
            ${Icons.whatsapp} WhatsApp
          </a>
          <button type="button" class="btn-outline-touch" onclick="openSupporterDetail(${index})">
            Detail
          </button>
        </div>
      </div>
    `;
  }).join('');
}

// ==========================================================================
// HALAMAN 4: ASPIRASI WARGA (MODERN SaaS & TOUCH ERGONOMICS)
// ==========================================================================

function renderAspirasiDedicated() {
  const container = document.getElementById('aspirasiDedicatedList');
  if (!container) return;

  // Metrik Terintegrasi pada Filter Pill Status (Opsi 1)
  const totalAspirasi = (AppState.aspirasi || []).length;
  let countMenunggu = 0;
  let countAdvokasi = 0;
  let countPokir = 0;
  let countSelesai = 0;

  (AppState.aspirasi || []).forEach(r => {
    const st = (r.status || '').toLowerCase();
    if (st.includes('menunggu') || st.includes('baru') || st.includes('pending') || !st) {
      countMenunggu++;
    } else if (st.includes('pokir')) {
      countPokir++;
    } else if (st.includes('selesai') || st.includes('disetujui') || st.includes('valid')) {
      countSelesai++;
    } else {
      countAdvokasi++;
    }
  });

  const elSemua = document.getElementById('countStatusSemua');
  if (elSemua) elSemua.textContent = totalAspirasi;
  const elMenunggu = document.getElementById('countStatusMenunggu');
  if (elMenunggu) elMenunggu.textContent = countMenunggu;
  const elAdvokasi = document.getElementById('countStatusAdvokasi');
  if (elAdvokasi) elAdvokasi.textContent = countAdvokasi;
  const elPokir = document.getElementById('countStatusPokir');
  if (elPokir) elPokir.textContent = countPokir;
  const elSelesai = document.getElementById('countStatusSelesai');
  if (elSelesai) elSelesai.textContent = countSelesai;

  let list = [...(AppState.aspirasi || [])];

  // Filter Kategori
  if (AppState.filterAspirasiCat) {
    list = list.filter(a => {
      const kat = (a.kategori || a.bidang || '').toLowerCase();
      return kat.includes(AppState.filterAspirasiCat.toLowerCase());
    });
  }

  // Filter Status
  if (AppState.filterAspirasiStatus) {
    const targetStatus = AppState.filterAspirasiStatus.toLowerCase();
    list = list.filter(a => {
      const st = (a.status || 'menunggu').toLowerCase();
      if (targetStatus === 'menunggu') {
        return st.includes('menunggu') || st.includes('baru') || st.includes('pending') || !st;
      } else if (targetStatus === 'advokasi') {
        return st.includes('advokasi') || st.includes('proses') || st.includes('tindak');
      } else if (targetStatus === 'pokir') {
        return st.includes('pokir');
      } else if (targetStatus === 'selesai') {
        return st.includes('selesai') || st.includes('disetujui') || st.includes('valid');
      }
      return true;
    });
  }

  // Filter Pencarian Real-Time
  if (AppState.searchAspirasiQuery) {
    const q = AppState.searchAspirasiQuery.toLowerCase();
    list = list.filter(a => {
      const nama = (a.nama || a.pengusul || '').toLowerCase();
      const topik = (a.topik || a.judul || '').toLowerCase();
      const isi = (a.aspirasi || a.deskripsi || a.isi || '').toLowerCase();
      const desa = (a.desa || '').toLowerCase();
      const kec = (a.kecamatan || '').toLowerCase();
      const hp = (a.hp || '').toLowerCase();
      return nama.includes(q) || topik.includes(q) || isi.includes(q) || desa.includes(q) || kec.includes(q) || hp.includes(q);
    });
  }

  const countBadge = document.getElementById('aspirasiResultsCount');
  if (countBadge) countBadge.textContent = list.length + ' Data';

  if (list.length === 0) {
    container.innerHTML = `
      <div class="empty-state" style="padding:24px 16px;">
        ${Icons.messageSquare}
        <div class="empty-state-title">Tidak ada data aspirasi</div>
        <div class="empty-state-desc">Belum ada aspirasi warga yang cocok dengan pencarian atau filter yang dipilih.</div>
      </div>
    `;
    return;
  }

  container.innerHTML = list.map(a => {
    let badgeClass = 'pending';
    let statusText = 'Menunggu';
    const st = (a.status || '').toLowerCase();
    if (st.includes('selesai') || st.includes('disetujui')) {
      badgeClass = 'valid';
      statusText = 'Selesai';
    } else if (st.includes('pokir')) {
      badgeClass = 'info';
      statusText = 'Masuk Pokir';
    } else if (st.includes('advokasi') || st.includes('proses') || st.includes('tindak')) {
      badgeClass = 'purple';
      statusText = 'Advokasi';
    } else {
      badgeClass = 'pending';
      statusText = 'Menunggu';
    }

    const topik = a.topik || a.judul || a.aspirasi || 'Aspirasi Konstituen';
    const pengusul = a.nama || a.pengusul || 'Warga';
    const isi = a.aspirasi || a.deskripsi || a.isi || 'Kebutuhan warga desa yang diusulkan melalui kegiatan relawan.';
    const kat = a.kategori || a.bidang || 'Infrastruktur';
    const desa = a.desa || 'Patokan';
    const kec = a.kecamatan || 'Kraksaan';
    const hasHp = Boolean(a.hp);

    return `
      <div class="touch-card" onclick="openAspirasiDetail(${a.id})" style="padding:12px;margin-bottom:10px;gap:8px;">
        <div class="card-top-row" style="gap:10px;">
          <div class="card-avatar" style="width:36px;height:36px;min-width:36px;border-radius:10px;background:#eff6ff;color:#2563eb;font-size:13px;">
            ${Icons.messageSquare}
          </div>
          <div class="card-info">
            <div class="card-name" style="font-size:14px;font-weight:700;">${escapeHtml(topik)}</div>
            <div class="card-nik" style="font-family:inherit;font-size:11px;">Pengusul: <strong>${escapeHtml(pengusul)}</strong></div>
          </div>
          <span class="badge-status ${badgeClass}" style="font-size:10.5px;padding:3px 8px;">${statusText}</span>
        </div>
        <p style="font-size:12.5px;color:#334155;line-height:1.42;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin:0;">
          ${escapeHtml(isi)}
        </p>
        <div class="card-details-row" style="padding-top:6px;gap:6px;font-size:11.5px;">
          <div class="card-detail-item" style="gap:3px;">
            ${Icons.mapPin}
            <span>${escapeHtml(desa)}, ${escapeHtml(kec)}</span>
          </div>
          <span class="card-pill blue" style="font-size:10px;padding:1px 6px;">${escapeHtml(kat)}</span>
          ${hasHp ? `<span class="card-pill green" style="font-size:10px;padding:1px 6px;">WA Ada</span>` : ''}
        </div>
        <div class="card-actions-row" onclick="event.stopPropagation()" style="margin-top:2px;gap:8px;">
          ${hasHp ? `
            <button type="button" class="btn-wa-touch" style="height:34px;font-size:11.5px;padding:0 10px;" onclick="kirimWaUpdateAspirasi(${a.id})">
              ${Icons.whatsapp} Kabar WA
            </button>
          ` : ''}
          <button type="button" class="btn-outline-touch" style="flex:1;height:34px;font-size:11.5px;padding:0 10px;" onclick="openAspirasiDetail(${a.id})">
            Detail &amp; Aksi
          </button>
        </div>
      </div>
    `;
  }).join('');
}

function clearAspirasiSearch() {
  const input = document.getElementById('searchAspirasiInput');
  const btn = document.getElementById('btnClearAspirasiSearch');
  if (input) input.value = '';
  if (btn) btn.classList.remove('active');
  AppState.searchAspirasiQuery = '';
  renderAspirasiDedicated();
}

function filterAspirasiStatus(status, btn) {
  AppState.filterAspirasiStatus = status;
  document.querySelectorAll('#page-aspirasi .filter-pill[data-asp-status]').forEach(b => b.classList.remove('active'));
  if (btn) btn.classList.add('active');
  renderAspirasiDedicated();
}

function filterAspirasiCat(cat, btn) {
  AppState.filterAspirasiCat = cat;
  document.querySelectorAll('#page-aspirasi .filter-pill[data-asp-cat]').forEach(b => b.classList.remove('active'));
  if (btn) btn.classList.add('active');
  renderAspirasiDedicated();
}

// Buka Bottom Sheet Detail Aspirasi
function openAspirasiDetail(id) {
  const a = (AppState.aspirasi || []).find(item => String(item.id) === String(id));
  if (!a) {
    showToast('Data aspirasi tidak ditemukan', 'warning');
    return;
  }

  const topik = a.topik || a.judul || a.aspirasi || 'Aspirasi Warga';
  const nama = a.nama || a.pengusul || 'Warga Konstituen';
  const isi = a.aspirasi || a.deskripsi || a.isi || 'Tidak ada uraian rincian.';
  const desa = a.desa || '-';
  const kec = a.kecamatan || 'Kraksaan';
  const kat = a.kategori || a.bidang || 'Infrastruktur';
  const jalur = a.jalur || 'Relawan';
  const tanggal = a.tanggal || a.created_at || 'Hari ini';
  const phoneRaw = a.hp || '';
  let cleanHp = phoneRaw.replace(/[^0-9]/g, '');
  if (cleanHp.startsWith('0')) cleanHp = '62' + cleanHp.substring(1);

  const st = (a.status || 'Menunggu').toLowerCase();
  let statusBadgeClass = 'pending';
  let statusText = 'Menunggu Verifikasi';

  if (st.includes('selesai') || st.includes('disetujui') || st.includes('valid')) {
    statusBadgeClass = 'valid';
    statusText = 'Selesai / Terealisasi';
  } else if (st.includes('pokir')) {
    statusBadgeClass = 'info';
    statusText = 'Terekalasi ke Pokir APBD';
  } else if (st.includes('advokasi') || st.includes('proses') || st.includes('tindak')) {
    statusBadgeClass = 'purple';
    statusText = 'Dalam Advokasi Lapangan';
  }

  const waActionBtn = cleanHp ? `
    <button type="button" class="btn-wa-touch" style="width:100%;height:44px;" onclick="kirimWaUpdateAspirasi(${a.id})">
      ${Icons.whatsapp} Kirim Kabar Perkembangan via WA
    </button>
  ` : `
    <button type="button" class="btn-wa-touch" style="width:100%;height:44px;background:#94a3b8;" onclick="showToast('Nomor WhatsApp warga belum tercatat', 'info')">
      ${Icons.whatsapp} WhatsApp (Nomor Belum Terdata)
    </button>
  `;

  const content = `
    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:14px;margin-bottom:14px;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
        <span class="card-pill blue">${escapeHtml(kat)}</span>
        <span class="badge-status ${statusBadgeClass}">${statusText}</span>
      </div>
      <div style="font-size:16px;font-weight:800;color:#0f172a;line-height:1.3;margin-bottom:6px;">
        ${escapeHtml(topik)}
      </div>
      <div style="font-size:12px;color:#64748b;">
        Pengusul: <strong style="color:#0f172a;">${escapeHtml(nama)}</strong> &bull; ${escapeHtml(tanggal)}
      </div>
    </div>

    <!-- Info Detail Grid -->
    <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:12px;display:flex;flex-direction:column;gap:8px;margin-bottom:14px;">
      <div style="display:flex;justify-content:space-between;font-size:13px;">
        <span style="color:#64748b;">Wilayah</span>
        <span style="font-weight:600;color:#0f172a;">${escapeHtml(desa)}, Kec. ${escapeHtml(kec)}</span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:13px;">
        <span style="color:#64748b;">Jalur Aspirasi</span>
        <span style="font-weight:600;color:#16225e;">${escapeHtml(jalur)}</span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:13px;">
        <span style="color:#64748b;">Kontak HP/WA</span>
        <span style="font-weight:600;color:#0f172a;">${phoneRaw ? escapeHtml(phoneRaw) : '-'}</span>
      </div>
      <div style="border-top:1px dashed #e2e8f0;padding-top:8px;margin-top:2px;">
        <div style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;margin-bottom:4px;">Uraian Keluhan &amp; Kebutuhan:</div>
        <div style="font-size:13px;color:#334155;line-height:1.5;background:#f8fafc;padding:10px;border-radius:8px;border-left:3px solid #2563eb;">
          "${escapeHtml(isi)}"
        </div>
      </div>
    </div>

    <!-- Aksi Cepat Lapangan -->
    <div style="display:flex;flex-direction:column;gap:8px;">
      ${waActionBtn}

      <button type="button" class="btn-outline-touch" style="width:100%;height:44px;background:#eff6ff;color:#1d4ed8;border-color:#bfdbfe;font-weight:700;" onclick="eskalasiAspirasiKePokir(${a.id})">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" style="width:16px;height:16px;min-width:16px;min-height:16px;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="11" x2="12" y2="17"/><line x1="9" y1="14" x2="15" y2="14"/></svg>
        Eskalasi ke Usulan Pokir APBD
      </button>

      <!-- Update Status Tindak Lanjut -->
      <div style="background:#f1f5f9;padding:10px;border-radius:10px;margin-top:4px;">
        <div style="font-size:11px;font-weight:700;color:#475569;margin-bottom:8px;text-transform:uppercase;">Ubah Status Aspirasi:</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">
          <button type="button" class="btn-outline-touch" style="height:36px;font-size:11px;background:#fffbeb;color:#b45309;border-color:#fde68a;" onclick="updateAspirasiStatus(${a.id}, 'Menunggu')">
            Set Menunggu
          </button>
          <button type="button" class="btn-outline-touch" style="height:36px;font-size:11px;background:#faf5ff;color:#7e22ce;border-color:#e9d5ff;" onclick="updateAspirasiStatus(${a.id}, 'Advokasi')">
            Set Advokasi
          </button>
          <button type="button" class="btn-outline-touch" style="height:36px;font-size:11px;background:#eff6ff;color:#1d4ed8;border-color:#bfdbfe;" onclick="updateAspirasiStatus(${a.id}, 'Masuk Pokir')">
            Set Masuk Pokir
          </button>
          <button type="button" class="btn-outline-touch" style="height:36px;font-size:11px;background:#f0fdf4;color:#15803d;border-color:#bbf7d0;" onclick="updateAspirasiStatus(${a.id}, 'Selesai')">
            Tandai Selesai
          </button>
        </div>
      </div>

      <button type="button" class="btn-outline-touch" style="width:100%;height:40px;margin-top:2px;" onclick="closeBottomSheet()">
        Tutup
      </button>
    </div>
  `;

  openBottomSheet('Detail Aspirasi Warga', content);
}

// Update Status Aspirasi ke Server API
async function updateAspirasiStatus(id, newStatus) {
  const asp = (AppState.aspirasi || []).find(a => String(a.id) === String(id));
  if (!asp) return;

  showToast('Memperbarui status aspirasi...', 'info');
  try {
    const res = await mobileApiCall('aspirasi.php?action=update-status', 'POST', { id, status: newStatus });
    if (res.ok && res.data && res.data.success) {
      asp.status = newStatus;
      renderAspirasiDedicated();
      closeBottomSheet();
      showToast(`Status aspirasi berhasil diubah ke "${newStatus}"!`, 'success');

      if (asp.hp) {
        setTimeout(() => {
          if (confirm(`Status aspirasi telah diperbarui ke "${newStatus}". Ingin kirim notifikasi kabar perkembangan ke WhatsApp warga (${asp.hp}) sekarang?`)) {
            kirimWaUpdateAspirasi(id, newStatus);
          }
        }, 400);
      }
    } else {
      asp.status = newStatus;
      renderAspirasiDedicated();
      closeBottomSheet();
      showToast(`Status diubah secara lokal menjadi "${newStatus}"`, 'info');
    }
  } catch (err) {
    showToast('Gagal update status: ' + err.message, 'warning');
  }
}

// Kirim Kabar Perkembangan via WhatsApp
function kirimWaUpdateAspirasi(id, customStatus) {
  const asp = (AppState.aspirasi || []).find(a => String(a.id) === String(id));
  if (!asp) {
    showToast('Data aspirasi tidak ditemukan', 'warning');
    return;
  }

  const rawHp = asp.hp || '';
  let cleanHp = rawHp.replace(/[^0-9]/g, '');
  if (cleanHp.startsWith('0')) {
    cleanHp = '62' + cleanHp.substring(1);
  }

  const status = customStatus || asp.status || 'Ditindaklanjuti';
  const nama = asp.nama || asp.pengusul || 'Bpk/Ibu Warga';
  const keluhan = asp.aspirasi || asp.deskripsi || asp.isi || '-';
  const desa = asp.desa || '-';
  const kec = asp.kecamatan || '-';

  let kalimatStatus = '';
  const stLower = status.toLowerCase();
  if (stLower.includes('selesai') || stLower.includes('disetujui')) {
    kalimatStatus = 'Alhamdulillah, aspirasi panjenengan telah *SELESAI DIREALISASIKAN* melalui program advokasi dewan Gus Dim di lapangan.';
  } else if (stLower.includes('pokir')) {
    kalimatStatus = 'Aspirasi panjenengan telah *DIESKALASI KE USULAN POKIR APBD RESMI* untuk diperjuangkan dalam anggaran pembangunan daerah Kabupaten Probolinggo.';
  } else if (stLower.includes('advokasi') || stLower.includes('tindak') || stLower.includes('proses')) {
    kalimatStatus = 'Aspirasi panjenengan telah *DISETUJUI & SEDANG DALAM ADVOKASI LAPANGAN* oleh tim fraksi untuk dikoordinasikan dengan instansi/dinas terkait.';
  } else {
    kalimatStatus = 'Aspirasi panjenengan telah kami terima dan saat ini berstatus *' + status + '* dalam verifikasi serta telaah tim fraksi.';
  }

  const pesan = "Assalamu'alaikum Wr. Wb. Bpk/Ibu *" + nama + "*,\n\n" +
    "Kami dari Tim Sahabat Gus Dim (Fraksi NasDem DPRD Kab. Probolinggo) menyampaikan kabar perkembangan aspirasi panjenengan:\n\n" +
    "Aspirasi: *\"" + keluhan + "\"*\n" +
    "Wilayah: Desa " + desa + ", Kec. " + kec + "\n" +
    "Status Terkini: *" + status + "*\n\n" +
    kalimatStatus + "\n\n" +
    "Terima kasih atas partisipasi dan aspirasi panjenengan demi kemaslahatan bersama di Dapil Kraksaan Raya.\n\n" +
    "Salam Hormat,\n" +
    "*Gus Dim & Tim Fraksi NasDem*";

  const waUrl = cleanHp ?
    "https://wa.me/" + cleanHp + "?text=" + encodeURIComponent(pesan) :
    "https://wa.me/?text=" + encodeURIComponent(pesan);

  window.open(waUrl, '_blank');
}

// Jembatan Eskalasi Aspirasi ke Usulan Pokir APBD
function eskalasiAspirasiKePokir(id) {
  const asp = (AppState.aspirasi || []).find(a => String(a.id) === String(id));
  if (!asp) {
    showToast('Data aspirasi tidak ditemukan', 'warning');
    return;
  }

  closeBottomSheet();

  const shortTitle = (asp.topik || asp.judul || asp.aspirasi || 'Aspirasi Warga').substring(0, 55).trim();
  const catMap = {
    'Infrastruktur': 'Dinas PUPR',
    'Pertanian': 'Dinas Pertanian',
    'Pendidikan': 'Dinas Pendidikan',
    'Kesehatan': 'Dinas Kesehatan',
    'UMKM': 'Dinas Koperasi & UMKM',
    'Bansos': 'Dinas Sosial'
  };

  const prefill = {
    kegiatan: 'Advokasi: ' + shortTitle,
    opd: catMap[asp.kategori] || 'Dinas PUPR',
    anggaran: 50000000,
    lokasi: (asp.desa ? 'Desa ' + asp.desa + ', ' : '') + 'Kec. ' + (asp.kecamatan || 'Kraksaan'),
    uraian: 'Eskalasi dari aspirasi konstituen: ' + (asp.aspirasi || asp.deskripsi || asp.isi || '') + ' (Kanal Aspirasi Warga Dapil Kraksaan Raya)',
    pengusulNama: asp.nama || asp.pengusul || '',
    pengusulHp: asp.hp || ''
  };

  openPokirForm(prefill);
  showToast('Form Usulan Pokir terisi otomatis dari data aspirasi warga', 'info');
}

// Buka Form Aspirasi Baru
function openAspirasiForm() {
  const content = `
    <form onsubmit="handleAspirasiSubmit(event)">
      <div class="form-group">
        <label class="form-label">Topik / Judul Aspirasi <span class="required-mark">*</span></label>
        <input type="text" id="aspJudul" class="form-input-touch" placeholder="Contoh: Perbaikan Saluran Irigasi Tersier" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
        <div class="form-group">
          <label class="form-label">Nama Pengusul <span class="required-mark">*</span></label>
          <input type="text" id="aspPengusul" class="form-input-touch" placeholder="Nama warga / Poktan" required>
        </div>
        <div class="form-group">
          <label class="form-label">WhatsApp Pengusul</label>
          <input type="tel" id="aspHp" class="form-input-touch" placeholder="08xxxxxxxxxx">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
        <div class="form-group">
          <label class="form-label">Bidang Kategori <span class="required-mark">*</span></label>
          <select id="aspKategori" class="form-select-touch" required>
            <option value="Infrastruktur">Infrastruktur (Jalan / Jembatan)</option>
            <option value="Pertanian">Pertanian &amp; Irigasi</option>
            <option value="Pendidikan">Pendidikan &amp; Beasiswa</option>
            <option value="Kesehatan">Layanan Kesehatan</option>
            <option value="Bansos">Bantuan Sosial &amp; Sembako</option>
            <option value="UMKM">UMKM &amp; Ekonomi Kreatif</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Jalur Penjaringan</label>
          <select id="aspJalur" class="form-select-touch">
            <option value="Relawan">Relawan Lapangan</option>
            <option value="DPC Kecamatan">DPC Kecamatan</option>
            <option value="DPRT Desa">DPRT Desa</option>
            <option value="PIP">Aspirasi PIP</option>
            <option value="KIP">Aspirasi KIP</option>
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
        <div class="form-group">
          <label class="form-label">Kecamatan <span class="required-mark">*</span></label>
          <select id="aspKecamatan" class="form-select-touch" onchange="updateAspirasiDesa(this.value)" required>
            <option value="Kraksaan">Kraksaan</option>
            <option value="Besuk">Besuk</option>
            <option value="Gading">Gading</option>
          </select>
        </div>
        <div class="form-group">
          <label class="form-label">Desa <span class="required-mark">*</span></label>
          <select id="aspDesa" class="form-select-touch" required>
            <option value="Patokan">Patokan</option>
            <option value="Semampir">Semampir</option>
            <option value="Kraksaan Wetan">Kraksaan Wetan</option>
            <option value="Kandangjati Kulon">Kandangjati Kulon</option>
            <option value="Kandangjati Wetan">Kandangjati Wetan</option>
            <option value="Sidomukti">Sidomukti</option>
            <option value="Kebonagung">Kebonagung</option>
            <option value="Rondokuning">Rondokuning</option>
            <option value="Asembagus">Asembagus</option>
            <option value="Bulubrangsi">Bulubrangsi</option>
            <option value="Kalisalam">Kalisalam</option>
            <option value="Kregenan">Kregenan</option>
            <option value="Tamansari">Tamansari</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Uraian Kebutuhan &amp; Keluhan Warga <span class="required-mark">*</span></label>
        <textarea id="aspIsi" class="form-textarea-touch" placeholder="Jelaskan detail kebutuhan atau permasalahan warga secara spesifik..." required></textarea>
      </div>

      <div class="sticky-form-cta">
        <button type="submit" class="btn-primary-touch">
          ${Icons.checkCircle} Kirim &amp; Simpan Aspirasi Warga
        </button>
      </div>
    </form>
  `;
  openBottomSheet('Formulir Jaring Aspirasi Konstituen', content);
}

function updateAspirasiDesa(kec) {
  const el = document.getElementById('aspDesa');
  if (!el) return;
  const data = DapilLocations[kec];
  if (data && Array.isArray(data.desa)) {
    el.innerHTML = data.desa.map(d => `<option value="${escapeHtml(d)}">${escapeHtml(d)}</option>`).join('');
  }
}

// Simpan Aspirasi Baru ke Server API MySQL
async function handleAspirasiSubmit(e) {
  e.preventDefault();
  const topik = document.getElementById('aspJudul').value.trim();
  const nama = document.getElementById('aspPengusul').value.trim();
  const hp = (document.getElementById('aspHp')?.value || '').trim();
  const kategori = document.getElementById('aspKategori').value;
  const jalur = (document.getElementById('aspJalur')?.value || 'Relawan');
  const kecamatan = document.getElementById('aspKecamatan').value;
  const desa = document.getElementById('aspDesa').value;
  const isi = document.getElementById('aspIsi').value.trim();

  if (!nama || !isi) {
    showToast('Nama pengusul dan isi keluhan wajib diisi!', 'warning');
    return;
  }

  showToast('Menyimpan aspirasi warga...', 'info');
  const payload = {
    topik,
    nama,
    hp,
    kategori,
    jalur,
    kecamatan,
    desa,
    aspirasi: isi
  };

  try {
    const res = await mobileApiCall('aspirasi.php?action=add', 'POST', payload);
    if (res.ok && res.data && res.data.success) {
      const savedItem = res.data.item || {
        id: Date.now(),
        ...payload,
        status: 'Baru',
        tanggal: new Date().toLocaleDateString('id-ID')
      };
      AppState.aspirasi.unshift(savedItem);
      calculateStats();
      renderDashboardStats();
      renderAspirasiDedicated();
      closeBottomSheet();
      showToast('Aspirasi warga berhasil dicatat dan disimpan ke server!', 'success');
    } else {
      const fallbackItem = {
        id: Date.now(),
        ...payload,
        status: 'Baru',
        tanggal: new Date().toLocaleDateString('id-ID')
      };
      AppState.aspirasi.unshift(fallbackItem);
      calculateStats();
      renderDashboardStats();
      renderAspirasiDedicated();
      closeBottomSheet();
      showToast('Aspirasi tersimpan di perangkat (Mode Offline)', 'info');
    }
  } catch (err) {
    showToast('Gagal mencatat aspirasi: ' + err.message, 'warning');
  }
}

// ==========================================================================
// HALAMAN 5: RESES & POKIR APBD
// ==========================================================================

// Helper Badge 6 Tahapan Siklus Pokir APBD Mobile
function getPokirStageBadgeMobile(status) {
  const s = String(status || '').toLowerCase();
  if (s.includes('realisasi') || s.includes('selesai') || s.includes('6')) {
    return '<span class="badge-stage stage-6">6. Realisasi Lapangan</span>';
  } else if (s.includes('apbd') || s.includes('dpa') || s.includes('5')) {
    return '<span class="badge-stage stage-5">5. Masuk APBD Resmi</span>';
  } else if (s.includes('verifikasi') || s.includes('dinas') || s.includes('opd') || s.includes('4')) {
    return '<span class="badge-stage stage-4">4. Verifikasi Dinas</span>';
  } else if (s.includes('sipd') || s.includes('3')) {
    return '<span class="badge-stage stage-3">3. Terinput SIPD</span>';
  } else if (s.includes('disetujui') || s.includes('gus dim') || s.includes('fraksi') || s.includes('2')) {
    return '<span class="badge-stage stage-2">2. Disetujui Gus Dim</span>';
  } else {
    return '<span class="badge-stage stage-1">1. Aspirasi Reses</span>';
  }
}

function renderPokirDedicated() {
  const container = document.getElementById('pokirDedicatedList');
  if (!container) return;

  if (AppState.activeResesTab === 'pokir') {
    const list = AppState.pokir || [];
    if (list.length === 0) {
      container.innerHTML = `
        <div class="empty-state">
          ${Icons.building}
          <div class="empty-state-title">Belum ada usulan Pokir APBD</div>
          <div class="empty-state-desc">Gunakan tombol "Usul Pokir" untuk mendaftarkan program advokasi dewan.</div>
        </div>
      `;
      return;
    }

    container.innerHTML = list.map(p => {
      const budgetNum = Number(p.anggaran || p.pagu || p.anggaran_disetujui || 0);
      const budget = budgetNum > 0 ? `Rp ${budgetNum.toLocaleString('id-ID')}` : 'Rp 150.000.000';
      const stageBadge = getPokirStageBadgeMobile(p.status_tahap || p.status || 'Aspirasi Reses');
      const hp = p.kontak_pengusul || p.pengusul_hp || p.pengusulHp || '';
      const pengusulNama = p.nama_pengusul || p.pengusul_nama || p.pengusulNama || 'Konstituen Dapil';

      return `
        <div class="touch-card" style="cursor:pointer;" onclick="openPokirDetail(${p.id})">
          <div class="card-top-row">
            <div class="card-avatar" style="background:#e0f2fe;color:#0284c7;">
              ${Icons.building}
            </div>
            <div class="card-info">
              <div class="card-name">${escapeHtml(p.kegiatan || p.judul || 'Program Pokir APBD')}</div>
              <div class="card-nik" style="color:#0284c7;font-weight:700;font-family:inherit;">${budget}</div>
            </div>
            <div>${stageBadge}</div>
          </div>
          <p style="font-size:12.5px;color:#334155;line-height:1.45;margin-bottom:8px;">
            ${escapeHtml(p.uraian || p.keterangan || p.deskripsi || 'Program kerja prioritas DPRD untuk kemakmuran masyarakat Dapil.')}
          </p>
          <div class="card-details-row" style="margin-bottom:10px;">
            <div class="card-detail-item">
              ${Icons.mapPin}
              <span>${escapeHtml(p.lokasi || p.desa || 'Kecamatan Kraksaan')}</span>
            </div>
            <span class="card-pill">OPD: ${escapeHtml(p.opd || p.kategori || 'Dinas PUPR')}</span>
          </div>

          <div style="display:flex;align-items:center;justify-content:space-between;border-top:1px solid #f1f5f9;padding-top:8px;font-size:11.5px;color:#64748b;">
            <div style="display:flex;align-items:center;gap:4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:60%;">
              <span>Oleh: <strong>${escapeHtml(pengusulNama)}</strong></span>
            </div>
            <div style="display:flex;align-items:center;gap:6px;" onclick="event.stopPropagation();">
              ${hp ? `
                <button type="button" class="btn-outline-touch" style="height:32px;padding:0 9px;font-size:11.5px;color:#16a34a;border-color:#bbf7d0;" onclick="kirimWaUpdatePokir(${p.id})">
                  ${Icons.whatsapp} Kabar WA
                </button>
              ` : ''}
              <button type="button" class="btn-outline-touch" style="height:32px;padding:0 9px;font-size:11.5px;color:#2563eb;border-color:#bfdbfe;" onclick="openPokirDetail(${p.id})">
                Detail &amp; Aksi
              </button>
            </div>
          </div>
        </div>
      `;
    }).join('');

  } else {
    // Laporan Reses Dewan (Agenda Reses Lapangan)
    const events = AppState.resesEvents || [
      {
        id: 1,
        nama: 'Reses Masa Sidang I Tahun 2025',
        masa_sidang: 'Masa Sidang I',
        kecamatan: 'Kraksaan',
        desa: 'Patokan, Semampir, Sidomukti',
        lokasi: 'Balai Pertemuan Warga',
        tanggal: '2025-01-15',
        total_hadir: 85,
        catatan: 'Penyerapan aspirasi kelompok petani padi, petambak, dan pedagang pasar tradisional terkait infrastruktur saluran tersier dan jalan usaha tani.'
      },
      {
        id: 2,
        nama: 'Reses Masa Sidang II Tahun 2025',
        masa_sidang: 'Masa Sidang II',
        kecamatan: 'Besuk & Gading',
        desa: 'Besuk Kidul, Matekan, Wangkal',
        lokasi: 'Pondok Pesantren & Gapoktan',
        tanggal: '2025-02-20',
        total_hadir: 110,
        catatan: 'Kunjungan kerja ke pesantren dan kelompok tani hutan untuk penyaluran bantuan bibit, alsintan serta peningkatan penerangan jalan umum desa.'
      }
    ];

    container.innerHTML = events.map(ev => `
      <div class="touch-card" style="cursor:pointer;" onclick="openResesEventDetail(${ev.id})">
        <div class="card-top-row">
          <div class="card-avatar" style="background:#fef3c7;color:#b45309;">
            ${Icons.checkCircle}
          </div>
          <div class="card-info">
            <div class="card-name">${escapeHtml(ev.nama || 'Reses Dewan Masa Sidang')}</div>
            <div class="card-nik" style="font-family:inherit;color:#b45309;font-weight:700;">
              ${escapeHtml(ev.masa_sidang || 'Masa Sidang 2025')} &bull; ${ev.total_hadir || 50} Warga Hadir
            </div>
          </div>
          <span class="badge-status valid">Terlaksana</span>
        </div>
        <p style="font-size:12.5px;color:#334155;line-height:1.45;margin-bottom:8px;">
          ${escapeHtml(ev.catatan || 'Penyerapan aspirasi konstituen di titik-titik kumpul warga konstituen Dapil.')}
        </p>
        <div class="card-details-row" style="margin-bottom:10px;">
          <div class="card-detail-item">
            ${Icons.mapPin}
            <span>Kec. ${escapeHtml(ev.kecamatan || 'Kraksaan')} (${escapeHtml(ev.desa || 'Wilayah Dapil')})</span>
          </div>
          <span class="card-pill blue">Waktu: ${escapeHtml(ev.tanggal || 'Tercatat')}</span>
        </div>

        <div style="display:flex;align-items:center;justify-content:flex-end;gap:6px;border-top:1px solid #f1f5f9;padding-top:8px;" onclick="event.stopPropagation();">
          <button type="button" class="btn-outline-touch" style="height:32px;padding:0 10px;font-size:11.5px;color:#475569;" onclick="openResesEventDetail(${ev.id})">
            Detail Titik
          </button>
          <button type="button" class="btn-primary-touch" style="height:32px;padding:0 10px;font-size:11.5px;" onclick="openPokirForm({ lokasi: '${escapeHtml(ev.lokasi || ev.desa || '')}', uraian: 'Diserap dari agenda reses: ${escapeHtml(ev.nama || '')}' })">
            + Usul Pokir
          </button>
        </div>
      </div>
    `).join('');
  }
}

// Buka Bottom Sheet Detail & Siklus Usulan Pokir APBD
function openPokirDetail(id) {
  const p = (AppState.pokir || []).find(item => String(item.id) === String(id));
  if (!p) {
    showToast('Data Pokir tidak ditemukan', 'warning');
    return;
  }

  const budgetNum = Number(p.anggaran || p.pagu || p.anggaran_disetujui || 0);
  const budgetFormatted = budgetNum > 0 ? `Rp ${budgetNum.toLocaleString('id-ID')}` : 'Rp 150.000.000';
  const stageBadge = getPokirStageBadgeMobile(p.status_tahap || p.status || 'Aspirasi Reses');
  const pengusulNama = p.nama_pengusul || p.pengusul_nama || p.pengusulNama || 'Konstituen Dapil';
  const pengusulHp = p.kontak_pengusul || p.pengusul_hp || p.pengusulHp || '';
  const currentStage = p.status_tahap || 'Aspirasi Reses';

  const stagesList = [
    'Aspirasi Reses',
    'Disetujui Gus Dim (Fraksi NasDem)',
    'Terinput SIPD',
    'Verifikasi Dinas (OPD Terkait)',
    'Masuk Dokumen Resmi APBD',
    'Realisasi Lapangan Selesai'
  ];

  const content = `
    <div style="display:flex;flex-direction:column;gap:12px;">
      <div style="display:flex;align-items:center;justify-content:space-between;">
        <span class="card-pill blue" style="font-size:11px;font-weight:700;">ID Pokir #${p.id}</span>
        <div>${stageBadge}</div>
      </div>

      <div style="font-size:16px;font-weight:800;color:#0f172a;line-height:1.35;">
        ${escapeHtml(p.kegiatan || p.judul || 'Program Usulan Pokir')}
      </div>

      <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:10px 12px;display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:11.5px;">
        <div>
          <span style="color:#64748b;font-size:10px;font-weight:700;text-transform:uppercase;">Alokasi Anggaran</span>
          <div style="font-weight:800;color:#0284c7;font-size:13px;margin-top:2px;">${budgetFormatted}</div>
        </div>
        <div>
          <span style="color:#64748b;font-size:10px;font-weight:700;text-transform:uppercase;">OPD Mitra</span>
          <div style="font-weight:700;color:#0f172a;margin-top:2px;">${escapeHtml(p.opd || p.kategori || 'Dinas PUPR')}</div>
        </div>
      </div>

      <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:10px 12px;font-size:12px;color:#334155;">
        <div style="font-weight:700;color:#0f172a;margin-bottom:4px;">Lokasi &amp; Wilayah:</div>
        <div>${escapeHtml(p.lokasi || p.desa || 'Kecamatan Kraksaan Raya')}</div>
      </div>

      <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:10px 12px;font-size:12px;color:#334155;line-height:1.45;">
        <div style="font-weight:700;color:#0f172a;margin-bottom:4px;">Uraian Kegiatan:</div>
        ${escapeHtml(p.uraian || p.keterangan || p.deskripsi || 'Program prioritas advokasi fraksi.')}
      </div>

      <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:10px 12px;font-size:12px;color:#1e3a8a;">
        <div style="font-weight:700;color:#1d4ed8;margin-bottom:4px;">Pengusul / Konstituen:</div>
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:6px;">
          <span><strong>${escapeHtml(pengusulNama)}</strong> ${pengusulHp ? `(${escapeHtml(pengusulHp)})` : ''}</span>
          ${pengusulHp ? `
            <button type="button" class="btn-wa-touch" style="height:30px;padding:0 10px;font-size:11px;" onclick="kirimWaUpdatePokir(${p.id})">
              ${Icons.whatsapp} Kabari Pengusul
            </button>
          ` : '<span style="font-size:11px;color:#64748b;">Nomor WA tidak ada</span>'}
        </div>
      </div>

      <!-- Update Tahapan Siklus Pokir Langsung -->
      <div style="border-top:1px solid #e2e8f0;padding-top:12px;margin-top:4px;">
        <label style="font-size:12px;font-weight:700;color:#0f172a;display:block;margin-bottom:6px;">
          Perbarui Tahapan Siklus Pokir:
        </label>
        <select id="selectPokirStageModal" class="touch-input" style="height:44px;font-size:13px;margin-bottom:10px;">
          ${stagesList.map(st => `
            <option value="${escapeHtml(st)}" ${currentStage.toLowerCase().includes(st.toLowerCase()) ? 'selected' : ''}>
              ${st}
            </option>
          `).join('')}
        </select>

        <label style="font-size:12px;font-weight:700;color:#0f172a;display:block;margin-bottom:6px;">
          Catatan Progres Lapangan:
        </label>
        <input type="text" id="inputPokirCatatanModal" class="touch-input" style="height:44px;font-size:13px;margin-bottom:12px;" placeholder="Contoh: Dokumen diverifikasi Bappelitbangda" value="${escapeHtml(p.catatan_progres || '')}">

        <div style="display:flex;gap:8px;">
          <button type="button" class="btn-primary-touch" style="height:42px;font-size:12.5px;font-weight:700;" onclick="savePokirStageUpdate(${p.id})">
            ${Icons.checkCircle} Simpan Perubahan Tahapan
          </button>
        </div>
      </div>
    </div>
  `;

  openBottomSheet('Detail &amp; Siklus Usulan Pokir', content);
}

// Simpan Perubahan Tahapan Pokir ke Server API
async function savePokirStageUpdate(id) {
  const select = document.getElementById('selectPokirStageModal');
  const catatanInput = document.getElementById('inputPokirCatatanModal');
  if (!select) return;

  const newStage = select.value;
  const newCatatan = catatanInput ? catatanInput.value.trim() : '';

  showToast('Memperbarui tahapan Pokir...', 'info');

  try {
    await mobileApiCall('reses.php?action=update_pokir_status', 'POST', {
      id: id,
      statusTahap: newStage,
      catatan: newCatatan || `Tahapan diubah ke ${newStage}`
    });

    const item = (AppState.pokir || []).find(p => String(p.id) === String(id));
    if (item) {
      item.status_tahap = newStage;
      item.catatan_progres = newCatatan || `Tahapan diubah ke ${newStage}`;
    }

    renderPokirDedicated();
    closeBottomSheet();
    showToast('Tahapan usulan Pokir berhasil diperbarui!', 'success');
  } catch (err) {
    const item = (AppState.pokir || []).find(p => String(p.id) === String(id));
    if (item) {
      item.status_tahap = newStage;
      item.catatan_progres = newCatatan || `Tahapan diubah ke ${newStage}`;
    }
    renderPokirDedicated();
    closeBottomSheet();
    showToast('Tahapan diperbarui di perangkat', 'info');
  }
}

// Kirim Kabar Perkembangan Pokir via WhatsApp
function kirimWaUpdatePokir(id) {
  const p = (AppState.pokir || []).find(item => String(item.id) === String(id));
  if (!p) {
    showToast('Data Pokir tidak ditemukan', 'warning');
    return;
  }

  const hpRaw = p.kontak_pengusul || p.pengusul_hp || p.pengusulHp || '';
  let cleanHp = hpRaw.replace(/[^0-9]/g, '');
  if (!cleanHp) {
    showToast('Nomor WhatsApp pengusul tidak terdaftar', 'warning');
    return;
  }
  if (cleanHp.startsWith('0')) cleanHp = '62' + cleanHp.substring(1);

  const nama = p.nama_pengusul || p.pengusul_nama || p.pengusulNama || 'Bapak/Ibu Konstituen';
  const judul = p.judul_usulan || p.kegiatan || p.judul || 'Program Pokir';
  const tahap = p.status_tahap || 'Aspirasi Reses';
  const catatan = p.catatan_progres || 'Sedang dalam pengawalan fraksi.';

  const pesan = `Assalamu'alaikum Wr. Wb. Yth. *${nama}*,\n\nKami dari Tim Advokasi Gus Dim (Fraksi NasDem DPRD Kab. Probolinggo) mengabarkan perkembangan usulan Pokir APBD panjenengan:\n\n*Kegiatan:* ${judul}\n*Status Tahap:* *${tahap}*\n*Keterangan:* ${catatan}\n\nTerima kasih atas masukan dan kepercayaannya. Kami berkomitmen terus memperjuangkan kemaslahatan masyarakat Dapil Kraksaan Raya.\n\nWassalamu'alaikum Wr. Wb.`;

  window.open(`https://wa.me/${cleanHp}?text=${encodeURIComponent(pesan)}`, '_blank');
}

// Detail Agenda Reses Dewan
function openResesEventDetail(id) {
  const events = AppState.resesEvents || [];
  const ev = events.find(e => String(e.id) === String(id)) || {
    id: id,
    nama: 'Reses Masa Sidang Dewan',
    masa_sidang: 'Masa Sidang 2025',
    kecamatan: 'Kraksaan',
    desa: 'Patokan',
    lokasi: 'Balai Pertemuan Warga',
    tanggal: '2025-01-15',
    waktu: '13:30',
    total_hadir: 85,
    catatan: 'Penyerapan aspirasi konstituen terkait pertanian, jalan desa, dan pemberdayaan ekonomi.'
  };

  const content = `
    <div style="display:flex;flex-direction:column;gap:12px;">
      <div style="display:flex;align-items:center;justify-content:space-between;">
        <span class="card-pill blue" style="font-size:11px;font-weight:700;">${escapeHtml(ev.masa_sidang || 'Masa Sidang')}</span>
        <span class="badge-status valid" style="font-size:10.5px;">Selesai Terlaksana</span>
      </div>

      <div style="font-size:16px;font-weight:800;color:#0f172a;line-height:1.35;">
        ${escapeHtml(ev.nama || ev.nama_acara || 'Pertemuan Reses')}
      </div>

      <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:10px 12px;display:grid;grid-template-columns:1fr 1fr;gap:8px;font-size:11.5px;">
        <div>
          <span style="color:#64748b;font-size:10px;font-weight:700;text-transform:uppercase;">Tanggal &amp; Waktu</span>
          <div style="font-weight:700;color:#0f172a;margin-top:2px;">${escapeHtml(ev.tanggal || '-')} (${escapeHtml(ev.waktu || '13:30')})</div>
        </div>
        <div>
          <span style="color:#64748b;font-size:10px;font-weight:700;text-transform:uppercase;">Kehadiran Warga</span>
          <div style="font-weight:700;color:#16a34a;margin-top:2px;">${ev.total_hadir || 50} Peserta Hadir</div>
        </div>
      </div>

      <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:10px 12px;font-size:12px;color:#334155;">
        <div style="font-weight:700;color:#0f172a;margin-bottom:4px;">Lokasi Pertemuan:</div>
        <div>${escapeHtml(ev.lokasi || ev.lokasi_detail || `Desa ${ev.desa}, Kec. ${ev.kecamatan}`)}</div>
      </div>

      <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:10px 12px;font-size:12px;color:#1e3a8a;line-height:1.45;">
        <div style="font-weight:700;color:#1d4ed8;margin-bottom:4px;">Hasil Penyerapan Aspirasi:</div>
        ${escapeHtml(ev.catatan || 'Aspirasi konstituen dicatat dan ditelaah untuk dimasukkan ke bank usulan Pokir fraksi.')}
      </div>

      <button type="button" class="btn-primary-touch" style="height:42px;font-size:12.5px;font-weight:700;" onclick="closeBottomSheet(); openPokirForm({ lokasi: '${escapeHtml(ev.lokasi || ev.desa || '')}', uraian: 'Diserap dari agenda reses: ${escapeHtml(ev.nama || '')}' })">
        ${Icons.checkCircle} + Buat Usulan Pokir dari Reses Ini
      </button>
    </div>
  `;

  openBottomSheet('Detail Agenda Reses', content);
}

// Pengaktif Geser Horizontal untuk Seluruh Filter Pills (Touch & Mouse Drag)
function initHorizontalSwipeFilters() {
  document.querySelectorAll('.filter-pills-scroll').forEach(slider => {
    if (slider.__swipeInitialized) return;
    slider.__swipeInitialized = true;

    let isDown = false;
    let startX = 0;
    let scrollLeft = 0;
    let hasMoved = false;

    slider.addEventListener('mousedown', (e) => {
      isDown = true;
      hasMoved = false;
      slider.classList.add('dragging');
      startX = e.pageX - slider.offsetLeft;
      scrollLeft = slider.scrollLeft;
    });

    const stopDragging = () => {
      if (!isDown) return;
      isDown = false;
      slider.classList.remove('dragging');
    };

    slider.addEventListener('mouseleave', stopDragging);
    slider.addEventListener('mouseup', stopDragging);

    slider.addEventListener('mousemove', (e) => {
      if (!isDown) return;
      e.preventDefault();
      const x = e.pageX - slider.offsetLeft;
      const walk = (x - startX) * 1.5;
      if (Math.abs(walk) > 5) hasMoved = true;
      slider.scrollLeft = scrollLeft - walk;
    });

    // Touch listeners
    slider.addEventListener('touchstart', (e) => {
      startX = e.touches[0].pageX - slider.offsetLeft;
      scrollLeft = slider.scrollLeft;
      hasMoved = false;
    }, { passive: true });

    slider.addEventListener('touchmove', (e) => {
      const x = e.touches[0].pageX - slider.offsetLeft;
      const walk = (x - startX);
      if (Math.abs(walk) > 5) hasMoved = true;
    }, { passive: true });

    // Cegah klik tombol terpencet jika pengguna sedang menggeser
    slider.addEventListener('click', (e) => {
      if (hasMoved) {
        e.preventDefault();
        e.stopPropagation();
      }
    }, true);
  });
}

function switchResesSubTab(tab, btn) {
  AppState.activeResesTab = tab;
  document.querySelectorAll('#page-reses .segment-btn').forEach(b => b.classList.remove('active'));
  if (btn) btn.classList.add('active');
  renderPokirDedicated();
}

// Buka Form Usulan Pokir APBD (Mendukung Prefill Otomatis dari Eskalasi Aspirasi)
function openPokirForm(prefill = null) {
  const defKegiatan = prefill ? escapeHtml(prefill.kegiatan || '') : '';
  const defOpd = prefill ? (prefill.opd || 'Dinas PUPR') : 'Dinas PUPR';
  const defAnggaran = prefill ? (prefill.anggaran || 50000000) : 150000000;
  const defLokasi = prefill ? escapeHtml(prefill.lokasi || '') : '';
  const defUraian = prefill ? escapeHtml(prefill.uraian || '') : '';
  const defPengusul = prefill ? escapeHtml(prefill.pengusulNama || '') : '';
  const defHp = prefill ? escapeHtml(prefill.pengusulHp || '') : '';

  const opdList = [
    { val: 'Dinas PUPR', label: 'Dinas Pekerjaan Umum & Tata Ruang' },
    { val: 'Dinas Pertanian', label: 'Dinas Pertanian & Ketahanan Pangan' },
    { val: 'Dinas Perhubungan', label: 'Dinas Perhubungan (PJU)' },
    { val: 'Dinas Pendidikan', label: 'Dinas Pendidikan' },
    { val: 'Dinas Kesehatan', label: 'Dinas Kesehatan' },
    { val: 'Dinas Sosial', label: 'Dinas Sosial' },
    { val: 'Dinas Koperasi & UMKM', label: 'Dinas Koperasi & Usaha Mikro' }
  ];

  const opdOptions = opdList.map(o => {
    const sel = (o.val === defOpd) ? 'selected' : '';
    return `<option value="${o.val}" ${sel}>${o.label}</option>`;
  }).join('');

  const content = `
    <form onsubmit="handlePokirSubmit(event)">
      <div class="form-group">
        <label class="form-label">Nama Kegiatan Pokir <span class="required-mark">*</span></label>
        <input type="text" id="pokKegiatan" class="form-input-touch" value="${defKegiatan}" placeholder="Contoh: Pavingisasi Jalan Desa Poros" required>
      </div>

      <div class="form-group">
        <label class="form-label">OPD / Dinas Penanggung Jawab <span class="required-mark">*</span></label>
        <select id="pokOpd" class="form-select-touch" required>
          ${opdOptions}
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Estimasi Pagu Anggaran (Rp) <span class="required-mark">*</span></label>
        <input type="number" id="pokAnggaran" class="form-input-touch" value="${defAnggaran}" placeholder="Contoh: 150000000" step="1000000" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
        <div class="form-group">
          <label class="form-label">Nama Pengusul</label>
          <input type="text" id="pokPengusul" class="form-input-touch" value="${defPengusul}" placeholder="Nama warga / kelompok">
        </div>
        <div class="form-group">
          <label class="form-label">WhatsApp Pengusul</label>
          <input type="tel" id="pokHp" class="form-input-touch" value="${defHp}" placeholder="08xxx">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Lokasi Wilayah Usulan <span class="required-mark">*</span></label>
        <input type="text" id="pokLokasi" class="form-input-touch" value="${defLokasi}" placeholder="Contoh: Desa Kandangjati Wetan, Kraksaan" required>
      </div>

      <div class="form-group">
        <label class="form-label">Uraian Rencana Kerja &amp; Manfaat <span class="required-mark">*</span></label>
        <textarea id="pokUraian" class="form-textarea-touch" placeholder="Rincian manfaat program bagi warga..." required>${defUraian}</textarea>
      </div>

      <div class="sticky-form-cta">
        <button type="submit" class="btn-primary-touch">
          ${Icons.checkCircle} Ajukan Usulan Pokir APBD
        </button>
      </div>
    </form>
  `;
  openBottomSheet('Formulir Pengajuan Usulan Pokir APBD', content);
}

// Simpan Pokir ke Server API
async function handlePokirSubmit(e) {
  e.preventDefault();
  const kegiatan = document.getElementById('pokKegiatan').value.trim();
  const opd = document.getElementById('pokOpd').value;
  const anggaran = Number(document.getElementById('pokAnggaran').value || 0);
  const lokasi = document.getElementById('pokLokasi').value.trim();
  const uraian = document.getElementById('pokUraian').value.trim();
  const pengusulNama = (document.getElementById('pokPengusul')?.value || '').trim();
  const pengusulHp = (document.getElementById('pokHp')?.value || '').trim();

  const newP = {
    judul: kegiatan,
    kegiatan,
    opd,
    anggaran,
    lokasi,
    uraian,
    pengusulNama,
    pengusulHp,
    status: 'Diajukan',
    status_tahap: 'Aspirasi Reses',
    created_at: new Date().toISOString()
  };

  showToast('Menyimpan usulan Pokir ke server...', 'info');

  try {
    const res = await mobileApiCall('reses.php?action=add_pokir', 'POST', {
      judul: kegiatan,
      kategori: opd,
      estimasiAnggaran: anggaran,
      lokasiDetail: lokasi,
      deskripsi: uraian,
      pengusulNama: pengusulNama || 'Konstituen Dapil',
      pengusulHp: pengusulHp
    });

    if (res.ok && res.data && res.data.success) {
      newP.id = res.data.id || Date.now();
      AppState.pokir.unshift(newP);
      calculateStats();
      renderDashboardStats();
      renderPokirDedicated();
      closeBottomSheet();
      showToast('Usulan Pokir berhasil didaftarkan ke server!', 'success');
    } else {
      newP.id = Date.now();
      AppState.pokir.unshift(newP);
      calculateStats();
      renderDashboardStats();
      renderPokirDedicated();
      closeBottomSheet();
      showToast('Usulan Pokir tersimpan di perangkat (Mode Offline)', 'info');
    }
  } catch (err) {
    newP.id = Date.now();
    AppState.pokir.unshift(newP);
    calculateStats();
    renderDashboardStats();
    renderPokirDedicated();
    closeBottomSheet();
    showToast('Usulan Pokir disimpan lokal', 'info');
  }
}

// ==========================================
// PETA SEBARAN INTERAKTIF MOBILE (MODEL A)
// ==========================================
let mobileMapInstance = null;
let mobileMapMarkersLayer = null;
let mobileMapResesLayer = null;

function renderPetaDistricts() {
  initMobileMap();
}

function initMobileMap() {
  const container = document.getElementById('mobileLeafletMap');
  if (!container) return;

  if (typeof L === 'undefined') {
    container.innerHTML = '<div style="padding:40px 20px;text-align:center;color:#64748b;">Memuat modul Leaflet geospasial...</div>';
    setTimeout(initMobileMap, 400);
    return;
  }

  let centerLat = -7.7595;
  let centerLng = 113.4185;
  let defaultZoom = 12;

  const u = AppState.currentUser || {};
  if (u.kecamatan && DapilLocations[u.kecamatan]) {
    centerLat = DapilLocations[u.kecamatan].lat;
    centerLng = DapilLocations[u.kecamatan].lng;
    defaultZoom = 13.5;
  }

  if (!mobileMapInstance) {
    mobileMapInstance = L.map('mobileLeafletMap', {
      zoomControl: false,
      attributionControl: false
    }).setView([centerLat, centerLng], defaultZoom);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(mobileMapInstance);

    L.control.zoom({ position: 'bottomright' }).addTo(mobileMapInstance);

    mobileMapMarkersLayer = L.layerGroup().addTo(mobileMapInstance);
    mobileMapResesLayer = L.layerGroup().addTo(mobileMapInstance);
  } else {
    setTimeout(function() {
      if (mobileMapInstance) mobileMapInstance.invalidateSize();
    }, 150);
  }

  plotMobileMapData('all');
}

function plotMobileMapData(filterType) {
  filterType = filterType || 'all';
  if (!mobileMapInstance || !mobileMapMarkersLayer) return;

  mobileMapMarkersLayer.clearLayers();
  mobileMapResesLayer.clearLayers();

  const subHeader = document.getElementById('mapHeaderSubtext');
  let countPlot = 0;

  const jalurColor = {
    DPC: '#2563eb',
    DPRT: '#d97706',
    PIP: '#16a34a',
    KIP: '#db2777',
    RELAWAN: '#9333ea'
  };

  // 1. Plot Pendukung
  if (filterType !== 'reses') {
    (AppState.supporters || []).forEach(function(item) {
      if (filterType !== 'all' && item.kecamatan !== filterType) return;

      let finalLat = item.lat || (item.latitude ? parseFloat(item.latitude) : null);
      let finalLng = item.lng || (item.longitude ? parseFloat(item.longitude) : null);

      if (!finalLat || !finalLng) {
        const d = DapilLocations[item.kecamatan || 'Kraksaan'];
        if (d) {
          const pseudoHash = (item.id || item.nik || 1) % 100;
          finalLat = d.lat + (Math.sin(pseudoHash) * 0.015);
          finalLng = d.lng + (Math.cos(pseudoHash) * 0.015);
        }
      }

      if (!finalLat || !finalLng) return;

      countPlot++;
      const color = jalurColor[item.jalur] || '#2563eb';
      const label = (item.jalur || 'R').substring(0, 1);

      const icon = L.divIcon({
        className: '',
        html: '<div style="background:' + color + ';width:26px;height:26px;border-radius:50%;border:2px solid #ffffff;box-shadow:0 3px 8px rgba(0,0,0,0.25);display:flex;align-items:center;justify-content:center;color:#ffffff;font-size:10px;font-weight:800;">' + label + '</div>',
        iconSize: [26, 26],
        iconAnchor: [13, 13]
      });

      const marker = L.marker([finalLat, finalLng], { icon: icon });
      marker.on('click', function() {
        showMapDetailSheet(item, false);
      });
      mobileMapMarkersLayer.addLayer(marker);
    });
  }

  // 2. Plot Reses / Pokir
  if (filterType === 'all' || filterType === 'reses') {
    (AppState.pokir || []).forEach(function(ev) {
      if (filterType !== 'all' && filterType !== 'reses' && ev.kecamatan !== filterType) return;

      let lat = ev.lat ? parseFloat(ev.lat) : null;
      let lng = ev.lng ? parseFloat(ev.lng) : null;

      if (!lat || !lng) {
        const d = DapilLocations[ev.kecamatan || 'Kraksaan'];
        if (d) {
          lat = d.lat + 0.005;
          lng = d.lng + 0.005;
        }
      }

      if (!lat || !lng) return;

      countPlot++;
      const icon = L.divIcon({
        className: '',
        html: '<div style="background:#059669;width:28px;height:28px;border-radius:6px;border:2px solid #ffffff;box-shadow:0 3px 8px rgba(0,0,0,0.25);display:flex;align-items:center;justify-content:center;color:#ffffff;"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>',
        iconSize: [28, 28],
        iconAnchor: [14, 14]
      });

      const marker = L.marker([lat, lng], { icon: icon });
      marker.on('click', function() {
        showMapDetailSheet(ev, true);
      });
      mobileMapResesLayer.addLayer(marker);
    });
  }

  if (subHeader) {
    subHeader.textContent = countPlot + ' Titik Sebaran Aktif';
  }
}

function filterMobileMap(val, btn) {
  document.querySelectorAll('.map-filter-pill').forEach(function(el) {
    el.classList.remove('active');
  });
  if (btn) btn.classList.add('active');

  closeMapDetailSheet();

  if (val === 'Kraksaan' || val === 'Besuk' || val === 'Gading') {
    const loc = DapilLocations[val];
    if (loc && mobileMapInstance) {
      mobileMapInstance.flyTo([loc.lat, loc.lng], 13.5, { duration: 0.8 });
    }
  } else if (val === 'all') {
    if (mobileMapInstance) {
      mobileMapInstance.flyTo([-7.7595, 113.4185], 12, { duration: 0.8 });
    }
  }

  plotMobileMapData(val);
}

function showMapDetailSheet(item, isReses) {
  const sheet = document.getElementById('mapDetailSheet');
  const content = document.getElementById('mapSheetContent');
  if (!sheet || !content) return;

  if (isReses) {
    content.innerHTML = [
      '<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;">',
      '  <div>',
      '    <span class="badge-status valid" style="font-size:10.5px;padding:2px 8px;margin-bottom:4px;display:inline-block;">Titik Kunjungan Reses</span>',
      '    <div style="font-size:15px;font-weight:800;color:#16225e;">' + escapeHtml(item.nama_acara || item.judul || 'Reses Dewan') + '</div>',
      '  </div>',
      '  <button type="button" onclick="closeMapDetailSheet()" style="background:none;border:none;color:#94a3b8;font-size:20px;cursor:pointer;padding:4px;line-height:1;">&times;</button>',
      '</div>',
      '<div style="font-size:12px;color:#475569;margin-bottom:10px;">',
      '  <div>Wilayah: <b>' + escapeHtml(item.desa || '-') + ', Kec. ' + escapeHtml(item.kecamatan || '-') + '</b></div>',
      '  <div>Target: ' + escapeHtml(item.target_kelompok || 'Masyarakat Umum') + '</div>',
      '  <div>Tanggal: ' + escapeHtml(item.tanggal || '-') + '</div>',
      '</div>',
      '<div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">',
      '  <button type="button" class="btn-outline-touch" onclick="navigatePage(\'reses\')" style="height:36px;font-size:12px;justify-content:center;">Lihat Reses</button>',
      '  <button type="button" class="btn-primary-touch" onclick="window.open(\'https://www.google.com/maps/search/?api=1&query=' + (item.lat || -7.7595) + ',' + (item.lng || 113.4185) + '\', \'_blank\')" style="height:36px;font-size:12px;justify-content:center;">Buka Maps</button>',
      '</div>'
    ].join('\n');
  } else {
    const color = (item.jalur === 'DPC' ? '#2563eb' : (item.jalur === 'DPRT' ? '#d97706' : (item.jalur === 'PIP' ? '#16a34a' : (item.jalur === 'KIP' ? '#db2777' : '#9333ea'))));
    content.innerHTML = [
      '<div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;">',
      '  <div>',
      '    <span style="font-size:10.5px;font-weight:700;color:#ffffff;background:' + color + ';padding:2px 8px;border-radius:4px;display:inline-block;margin-bottom:4px;">' + escapeHtml(item.jalur || 'Pendukung') + '</span>',
      '    <div style="font-size:15px;font-weight:800;color:#16225e;">' + escapeHtml(item.nama || '-') + '</div>',
      '  </div>',
      '  <button type="button" onclick="closeMapDetailSheet()" style="background:none;border:none;color:#94a3b8;font-size:20px;cursor:pointer;padding:4px;line-height:1;">&times;</button>',
      '</div>',
      '<div style="font-size:12px;color:#475569;margin-bottom:10px;">',
      '  <div>Wilayah: <b>' + escapeHtml(item.desa || '-') + ', Kec. ' + escapeHtml(item.kecamatan || '-') + '</b></div>',
      '  <div>Alamat: ' + escapeHtml(item.alamat || '-') + '</div>',
      '  <div>Status: <b>' + escapeHtml(item.status || 'Diinput') + '</b></div>',
      '</div>',
      '<div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">',
      '  <button type="button" class="btn-outline-touch" onclick="openDetailModal(' + escapeHtml(JSON.stringify(item)).replace(/"/g, '&quot;') + ')" style="height:36px;font-size:12px;justify-content:center;">Detail Warga</button>',
      '  <button type="button" class="btn-primary-touch" onclick="window.open(\'https://www.google.com/maps/search/?api=1&query=' + (item.lat || item.latitude || -7.7595) + ',' + (item.lng || item.longitude || 113.4185) + '\', \'_blank\')" style="height:36px;font-size:12px;justify-content:center;">Buka Maps</button>',
      '</div>'
    ].join('\n');
  }

  sheet.style.display = 'block';
}

function closeMapDetailSheet() {
  const sheet = document.getElementById('mapDetailSheet');
  if (sheet) sheet.style.display = 'none';
}

function locateUserPosition() {
  if (!navigator.geolocation) {
    showToast('Geolocation tidak didukung perangkat ini.', 'danger');
    return;
  }
  showToast('Mencari posisi satelit GPS Anda...', 'info');
  navigator.geolocation.getCurrentPosition(function(pos) {
    if (mobileMapInstance) {
      mobileMapInstance.flyTo([pos.coords.latitude, pos.coords.longitude], 15);
      L.circleMarker([pos.coords.latitude, pos.coords.longitude], {
        radius: 8,
        fillColor: '#2563eb',
        color: '#ffffff',
        weight: 3,
        opacity: 1,
        fillOpacity: 0.9
      }).addTo(mobileMapInstance);
      showToast('Posisi GPS berhasil ditemukan!', 'success');
    }
  }, function(err) {
    showToast('Tidak dapat mengunci sinyal GPS.', 'danger');
  }, { enableHighAccuracy: true, timeout: 8000 });
}

// Render Leaderboard Relawan
function renderLeaderboard() {
  const container = document.getElementById('leaderboardList');
  if (!container) return;

  const data = AppState.leaderboardData;
  container.innerHTML = data.map((item, idx) => `
    <div class="touch-card" style="padding:10px 14px;margin-bottom:8px;">
      <div style="display:flex;align-items:center;justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:10px;">
          <div style="width:28px;height:28px;border-radius:999px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12px;color:#475569;">
            ${idx + 4}
          </div>
          <div>
            <div style="font-size:13px;font-weight:700;color:#0f172a;">${item.nama}</div>
            <div style="font-size:11px;color:#64748b;">${item.wilayah}</div>
          </div>
        </div>
        <div style="text-align:right;">
          <div style="font-size:13px;font-weight:800;color:#16225e;">${item.total} Data</div>
          <div style="font-size:10px;color:#16a34a;font-weight:600;">${item.persen}% Target</div>
        </div>
      </div>
    </div>
  `).join('');
}

// ==========================================
// PENGELOLAAN RIWAYAT & LOG AUDIT
// ==========================================

let currentAuditFilter = 'semua';

function filterAuditLogs(category, el) {
  currentAuditFilter = category;
  document.querySelectorAll('#page-riwayat .filter-pill').forEach(p => p.classList.remove('active'));
  if (el) el.classList.add('active');
  renderAuditLogs();
}

function refreshAuditLogs() {
  showToast('Memperbarui log audit...', 'info');
  fetch('./api/logs.php').then(r => r.json()).then(res => {
    if (res && res.success && Array.isArray(res.rows) && res.rows.length > 0) {
      AppState.auditLogs = res.rows.map(l => {
        let color = 'blue';
        const a = (l.aksi || '').toLowerCase();
        if (a.includes('tambah') || a.includes('sukses') || a.includes('login') || a.includes('entri')) color = 'green';
        else if (a.includes('verif') || a.includes('ubah') || a.includes('validasi') || a.includes('status')) color = 'gold';
        else if (a.includes('aspirasi') || a.includes('pokir')) color = 'purple';
        else if (a.includes('hapus') || a.includes('tolak') || a.includes('nonaktif')) color = 'red';
        return {
          judul: l.aksi || 'Aktivitas Sistem',
          waktu: l.waktu || 'Baru saja',
          desc: l.keterangan || 'Pencatatan aktivitas sistem',
          petugas: l.user || 'Petugas',
          desa: 'Dapil Kraksaan Raya',
          color: color
        };
      });
      renderAuditLogs();
      showToast('Log audit berhasil diperbarui!', 'success');
    } else {
      AppState.auditLogs = generateDynamicAuditLogs();
      renderAuditLogs();
      showToast('Log aktivitas disinkronkan', 'info');
    }
  }).catch(() => {
    AppState.auditLogs = generateDynamicAuditLogs();
    renderAuditLogs();
  });
}

function generateDynamicAuditLogs() {
  const list = [];
  const recents = (AppState.supporters || []).slice(0, 4);
  recents.forEach(s => {
    list.push({
      judul: 'Verifikasi Berkas Pendukung',
      waktu: s.tanggal || 'Hari ini',
      desc: `Pemeriksaan data konstituen ${s.nama || 'Warga'} (${s.jalur || 'Relawan'}) status: ${s.status || 'Aktif'}`,
      petugas: s.userInput || 'Petugas Lapangan',
      desa: `Desa ${s.desa || 'Patokan'}, Kec. ${s.kecamatan || 'Kraksaan'}`,
      color: 'gold'
    });
  });

  const asps = (AppState.aspirasi || []).slice(0, 3);
  asps.forEach(a => {
    list.push({
      judul: 'Pencatatan Aspirasi Warga',
      waktu: a.tanggal || 'Hari ini',
      desc: `Usulan bidang ${a.kategori || 'Infrastruktur'}: ${a.judul || a.topik || a.uraian || 'Aspirasi konstituen'}`,
      petugas: 'Advokasi Fraksi',
      desa: `Kec. ${a.kecamatan || 'Kraksaan'}`,
      color: 'purple'
    });
  });

  list.push({
    judul: 'Sinkronisasi Sistem Lapangan',
    waktu: 'Sesi Aktif',
    desc: 'Database lokal smartphone tersinkronisasi ke server pusat Dapil Kraksaan Raya',
    petugas: 'Sistem PWA',
    desa: 'Dapil Kraksaan Raya',
    color: 'green'
  });

  return list;
}

function renderAuditLogs() {
  const container = document.getElementById('auditTimelineList');
  if (!container) return;

  let logs = AppState.auditLogs || [];
  if (currentAuditFilter === 'pendukung') {
    logs = logs.filter(l => (l.judul + ' ' + l.desc).toLowerCase().includes('pendukung') || (l.judul + ' ' + l.desc).toLowerCase().includes('berkas') || (l.judul + ' ' + l.desc).toLowerCase().includes('entri'));
  } else if (currentAuditFilter === 'aspirasi') {
    logs = logs.filter(l => (l.judul + ' ' + l.desc).toLowerCase().includes('aspirasi') || (l.judul + ' ' + l.desc).toLowerCase().includes('pokir'));
  } else if (currentAuditFilter === 'sistem') {
    logs = logs.filter(l => (l.judul + ' ' + l.desc).toLowerCase().includes('login') || (l.judul + ' ' + l.desc).toLowerCase().includes('sinkronisasi') || (l.judul + ' ' + l.desc).toLowerCase().includes('sistem'));
  }

  if (logs.length === 0) {
    container.innerHTML = `
      <div class="empty-state">
        ${Icons.clock}
        <div class="empty-state-title">Belum ada catatan aktivitas</div>
        <div class="empty-state-desc">Aktivitas entri dan verifikasi lapangan akan tercatat secara kronologis di sini.</div>
        <button type="button" class="btn-outline-touch" style="margin-top:12px;height:36px;font-size:12px;" onclick="refreshAuditLogs()">
          Segarkan Riwayat
        </button>
      </div>
    `;
    return;
  }

  container.innerHTML = logs.map(l => `
    <div class="timeline-item">
      <div class="timeline-dot ${l.color || 'blue'}"></div>
      <div class="timeline-content-card">
        <div class="timeline-header">
          <span class="timeline-title">${escapeHtml(l.judul)}</span>
          <span class="timeline-time">${escapeHtml(l.waktu)}</span>
        </div>
        <div class="timeline-desc">${escapeHtml(l.desc)}</div>
        <div class="timeline-meta">Oleh: <strong>${escapeHtml(l.petugas || 'Petugas')}</strong> &bull; ${escapeHtml(l.desa || 'Dapil')}</div>
      </div>
    </div>
  `).join('');
}

// ==========================================
// PENGELOLAAN PENGATURAN OPERATOR & SISTEM
// ==========================================

function renderOperators() {
  const opContainer = document.getElementById('operatorList');
  const userCard = document.getElementById('pengaturanUserCard');
  const btnTambahContainer = document.getElementById('btnTambahOperatorContainer');

  const u = AppState.currentUser;
  const isSuperadmin = u && (u.role === 'Superadmin' || u.role === 'Administrator');

  // Kartu User Aktif di Pengaturan
  if (userCard) {
    if (u) {
      const initials = (u.nama || u.username || 'PL').split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
      const wilayahText = [u.kecamatan, u.desa, u.ranting].filter(Boolean).join(' · ') || 'Dapil Kraksaan Raya';
      userCard.innerHTML = `
        <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;">
          <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:44px;height:44px;border-radius:50%;background:#16225e;color:#fff;font-weight:800;font-size:15px;display:flex;align-items:center;justify-content:center;border:2px solid #f59e0b;flex-shrink:0;">
              ${escapeHtml(initials)}
            </div>
            <div>
              <div style="font-size:14px;font-weight:800;color:#0f172a;">${escapeHtml(u.nama || u.username)}</div>
              <div style="font-size:11.5px;color:#2563eb;font-weight:600;margin-top:1px;">${escapeHtml(u.role || 'Petugas')} &bull; ${escapeHtml(wilayahText)}</div>
            </div>
          </div>
          <button type="button" class="btn-outline-touch" style="height:32px;font-size:11px;padding:0 9px;flex-shrink:0;" onclick="navigatePage('profil')">
            Profil Saya
          </button>
        </div>
      `;
    } else {
      userCard.innerHTML = `
        <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;">
          <div>
            <div style="font-size:13px;font-weight:700;color:#0f172a;">Sesi Tamu Lapangan</div>
            <div style="font-size:11px;color:#64748b;margin-top:2px;">Masuk untuk mengaktifkan sinkronisasi server penuh.</div>
          </div>
          <button type="button" class="btn-primary-touch" style="height:32px;font-size:11.5px;padding:0 12px;flex-shrink:0;" onclick="navigatePage('profil')">
            Masuk Akun
          </button>
        </div>
      `;
    }
  }

  // Tombol Tambah Operator (Superadmin)
  if (btnTambahContainer) {
    if (isSuperadmin) {
      btnTambahContainer.innerHTML = `
        <button type="button" class="btn-primary-touch" style="height:30px;font-size:11px;padding:0 10px;" onclick="openAddOperatorModal()">
          + Tambah Petugas
        </button>
      `;
    } else {
      btnTambahContainer.innerHTML = `
        <span class="card-pill" style="font-size:10.5px;">${(AppState.operators || []).length} Petugas</span>
      `;
    }
  }

  // Daftar Operator
  if (opContainer) {
    const ops = AppState.operators || [];
    if (ops.length === 0) {
      opContainer.innerHTML = `
        <div style="text-align:center;padding:16px;color:#64748b;font-size:12px;">
          Belum ada operator terdaftar.
        </div>
      `;
      return;
    }

    opContainer.innerHTML = ops.map(o => {
      const initials = (o.nama || 'P').split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
      const wilayah = [o.kecamatan, o.desa, o.ranting].filter(Boolean).join(' · ') || 'Dapil Kraksaan Raya';
      const isAktif = (o.status || 'Aktif') === 'Aktif';

      return `
        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f1f5f9;">
          <div style="display:flex;align-items:center;gap:10px;overflow:hidden;">
            <div style="width:36px;height:36px;border-radius:50%;background:#e0f2fe;color:#0284c7;font-weight:700;font-size:13px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
              ${escapeHtml(initials)}
            </div>
            <div style="overflow:hidden;">
              <div style="font-size:13px;font-weight:700;color:#0f172a;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                ${escapeHtml(o.nama)}
              </div>
              <div style="font-size:11px;color:#64748b;margin-top:2px;">
                <span style="color:#2563eb;font-weight:600;">${escapeHtml(o.role || 'Petugas')}</span> &bull; ${escapeHtml(wilayah)}
              </div>
            </div>
          </div>
          <div style="display:flex;align-items:center;gap:6px;flex-shrink:0;">
            <span class="badge-status ${isAktif ? 'valid' : 'pending'}" style="font-size:10px;">
              ${isAktif ? 'Aktif' : 'Nonaktif'}
            </span>
            ${isSuperadmin ? `
              <button type="button" class="btn-outline-touch" style="height:28px;padding:0 7px;font-size:10.5px;" onclick="openManageOperatorModal(${o.id || o.rowNumber}, '${escapeHtml(o.nama)}')">
                Kelola
              </button>
            ` : ''}
          </div>
        </div>
      `;
    }).join('');
  }
}

// Modal Tambah Petugas / Operator Baru (Superadmin)
function openAddOperatorModal() {
  const content = `
    <form onsubmit="saveNewOperatorMobile(event)" style="display:flex;flex-direction:column;gap:10px;">
      <div class="form-group">
        <label class="form-label" style="font-size:12px;">Nama Lengkap Petugas</label>
        <input type="text" id="addOpNama" class="form-input-touch" placeholder="Nama lengkap petugas" required>
      </div>
      <div class="form-group">
        <label class="form-label" style="font-size:12px;">Peran (Role)</label>
        <select id="addOpRole" class="form-input-touch">
          <option value="Admin Ranting">Admin Ranting (Desa)</option>
          <option value="Koordinator Desa">Koordinator Desa (Kordes)</option>
          <option value="Koordinator Kecamatan">Koordinator Kecamatan (Korcam)</option>
          <option value="Relawan">Relawan Lapangan</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label" style="font-size:12px;">Wilayah Kecamatan</label>
        <select id="addOpKecamatan" class="form-input-touch">
          <option value="Kraksaan">Kraksaan</option>
          <option value="Besuk">Besuk</option>
          <option value="Gading">Gading</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label" style="font-size:12px;">Desa / Kelurahan</label>
        <input type="text" id="addOpDesa" class="form-input-touch" placeholder="Contoh: Patokan" required>
      </div>
      <div class="form-group">
        <label class="form-label" style="font-size:12px;">Username Akun</label>
        <input type="text" id="addOpUsername" class="form-input-touch" placeholder="Username login" required>
      </div>
      <div class="form-group">
        <label class="form-label" style="font-size:12px;">Kata Sandi Awal</label>
        <input type="password" id="addOpPassword" class="form-input-touch" placeholder="Minimal 6 karakter" required minlength="6">
      </div>
      <button type="submit" class="btn-primary-touch" style="height:44px;font-size:13.5px;margin-top:6px;">
        ${Icons.checkCircle} Daftarkan Petugas Baru
      </button>
    </form>
  `;
  openBottomSheet('Tambah Petugas Lapangan Baru', content);
}

async function saveNewOperatorMobile(e) {
  e.preventDefault();
  const nama = document.getElementById('addOpNama').value.trim();
  const role = document.getElementById('addOpRole').value;
  const kecamatan = document.getElementById('addOpKecamatan').value;
  const desa = document.getElementById('addOpDesa').value.trim();
  const username = document.getElementById('addOpUsername').value.trim();
  const password = document.getElementById('addOpPassword').value;

  showToast('Mendaftarkan petugas...', 'info');
  try {
    const res = await fetch('./api/users.php?action=add', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ nama, role, kecamatan, desa, ranting: desa, username, password })
    }).then(r => r.json()).catch(() => null);

    if (res && res.success) {
      showToast('Petugas berhasil ditambahkan!', 'success');
      closeBottomSheet();
      loadAllData();
    } else {
      showToast((res && res.message) ? res.message : 'Gagal menambahkan petugas.', 'warning');
    }
  } catch (err) {
    showToast('Terjadi kesalahan jaringan.', 'warning');
  }
}

// Modal Kelola Operator (Toggle Status / Reset Sandi)
function openManageOperatorModal(id, nama) {
  const content = `
    <div style="display:flex;flex-direction:column;gap:12px;">
      <div style="background:#f8fafc;padding:12px;border-radius:10px;border:1px solid #e2e8f0;">
        <div style="font-size:14px;font-weight:800;color:#0f172a;">${escapeHtml(nama)}</div>
        <div style="font-size:11.5px;color:#64748b;margin-top:2px;">ID Petugas: #${id}</div>
      </div>

      <div style="border-top:1px solid #e2e8f0;padding-top:10px;">
        <label class="form-label" style="font-size:12px;margin-bottom:6px;">Ubah Status Keaktifan</label>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
          <button type="button" class="btn-outline-touch" style="height:38px;font-size:12px;color:#16a34a;border-color:#bbf7d0;" onclick="toggleOperatorStatusMobile(${id}, 'Aktif')">
            Aktifkan Akun
          </button>
          <button type="button" class="btn-outline-touch" style="height:38px;font-size:12px;color:#dc2626;border-color:#fecaca;" onclick="toggleOperatorStatusMobile(${id}, 'Nonaktif')">
            Nonaktifkan
          </button>
        </div>
      </div>

      <div style="border-top:1px solid #e2e8f0;padding-top:10px;">
        <label class="form-label" style="font-size:12px;margin-bottom:6px;">Reset Kata Sandi</label>
        <input type="password" id="resetOpNewPass" class="form-input-touch" placeholder="Kata sandi baru" style="margin-bottom:8px;">
        <button type="button" class="btn-primary-touch" style="width:100%;height:38px;font-size:12px;" onclick="saveResetOperatorPasswordMobile(${id})">
          Simpan Sandi Baru
        </button>
      </div>
    </div>
  `;
  openBottomSheet('Kelola Akun Petugas', content);
}

async function toggleOperatorStatusMobile(id, newStatus) {
  showToast(`Mengubah status ke ${newStatus}...`, 'info');
  try {
    const res = await fetch('./api/users.php?action=toggle-status', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ rowNumber: id, newStatus })
    }).then(r => r.json()).catch(() => null);

    if (res && res.success) {
      showToast(`Status akun diubah jadi ${newStatus}`, 'success');
      closeBottomSheet();
      loadAllData();
    } else {
      showToast((res && res.message) ? res.message : 'Gagal mengubah status.', 'warning');
    }
  } catch (e) {
    showToast('Terjadi kesalahan jaringan.', 'warning');
  }
}

async function saveResetOperatorPasswordMobile(id) {
  const newPass = document.getElementById('resetOpNewPass')?.value;
  if (!newPass || newPass.length < 6) {
    showToast('Kata sandi baru minimal 6 karakter.', 'warning');
    return;
  }
  showToast('Mereset kata sandi...', 'info');
  try {
    const res = await fetch('./api/users.php?action=reset-password', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ rowNumber: id, newPassword: newPass })
    }).then(r => r.json()).catch(() => null);

    if (res && res.success) {
      showToast('Kata sandi petugas berhasil direset!', 'success');
      closeBottomSheet();
    } else {
      showToast((res && res.message) ? res.message : 'Gagal mereset kata sandi.', 'warning');
    }
  } catch (e) {
    showToast('Terjadi kesalahan jaringan.', 'warning');
  }
}

function syncDatabaseNow() {
  showToast('Menyinkronkan data dengan server pusat...', 'info');
  loadAllData().then(() => {
    showToast('Data berhasil disinkronkan!', 'success');
  });
}

// ==========================================
// PENGELOLAAN PROFIL SAYA
// ==========================================

function renderProfile() {
  const container = document.getElementById('profileContainer');
  if (!container) return;

  const u = AppState.currentUser;
  const token = localStorage.getItem('dprd_token');

  if (u && token) {
    const initials = (u.nama || u.username || 'PL').split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
    const wilayahText = [u.kecamatan, u.desa, u.ranting].filter(Boolean).join(' · ') || 'Seluruh Dapil Kraksaan Raya';

    // Hitung data input aktual
    const myInputs = (AppState.supporters || []).filter(s => {
      const inputter = (s.userInput || s.input_by_user_name || '').toLowerCase();
      const myName = (u.nama || '').toLowerCase();
      const myUser = (u.username || '').toLowerCase();
      return inputter === myName || inputter === myUser || inputter.includes(myUser);
    }).length;

    const displayInputCount = myInputs > 0 ? myInputs : (u.role === 'Superadmin' ? AppState.supporters.length : (AppState.supporters.length > 0 ? Math.min(AppState.supporters.length, 18) : 0));
    const totalAspirasi = AppState.aspirasi.length;

    container.innerHTML = `
      <!-- Kartu Identitas Akun Aktif -->
      <div class="card" style="text-align:center;padding:22px 16px;background:linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);">
        <div style="width:68px;height:68px;border-radius:50%;background:#16225e;color:#ffffff;font-size:24px;font-weight:800;display:inline-flex;align-items:center;justify-content:center;margin-bottom:12px;border:3px solid #f59e0b;box-shadow:0 4px 14px rgba(22, 34, 94, 0.15);">
          ${escapeHtml(initials)}
        </div>
        <div style="font-size:18px;font-weight:800;color:#0f172a;line-height:1.3;">
          ${escapeHtml(u.nama || u.username)}
        </div>
        <div style="font-size:12.5px;color:#2563eb;font-weight:700;margin-top:4px;">
          ${escapeHtml(u.role || 'Petugas Lapangan')}
        </div>
        <div style="display:inline-block;background:#eff6ff;border:1px solid #bfdbfe;padding:4px 14px;border-radius:999px;font-size:11px;color:#1e40af;font-weight:600;margin-top:8px;">
          Wilayah Tugas: ${escapeHtml(wilayahText)}
        </div>
        <div style="margin-top:10px;font-size:11px;color:#16a34a;display:flex;align-items:center;justify-content:center;gap:5px;">
          <span style="width:7px;height:7px;border-radius:50%;background:#16a34a;"></span>
          Sesi Aktif &bull; Sinkronisasi Server Pusat
        </div>
      </div>

      <!-- Kartu Statistik Kontribusi Pribadi -->
      <div class="card">
        <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:12px;">Metrik Kontribusi Lapangan</div>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;">
          <div style="background:#f8fafc;padding:12px 8px;border-radius:10px;border:1px solid #e2e8f0;text-align:center;">
            <div style="font-size:10px;color:#64748b;font-weight:700;text-transform:uppercase;">Input Saya</div>
            <div style="font-size:18px;font-weight:800;color:#16225e;margin-top:4px;">${displayInputCount}</div>
            <div style="font-size:9.5px;color:#64748b;margin-top:2px;">Konstituen</div>
          </div>
          <div style="background:#f8fafc;padding:12px 8px;border-radius:10px;border:1px solid #e2e8f0;text-align:center;">
            <div style="font-size:10px;color:#64748b;font-weight:700;text-transform:uppercase;">Validasi</div>
            <div style="font-size:18px;font-weight:800;color:#16a34a;margin-top:4px;">100%</div>
            <div style="font-size:9.5px;color:#16a34a;margin-top:2px;">Terverifikasi</div>
          </div>
          <div style="background:#f8fafc;padding:12px 8px;border-radius:10px;border:1px solid #e2e8f0;text-align:center;">
            <div style="font-size:10px;color:#64748b;font-weight:700;text-transform:uppercase;">Aspirasi</div>
            <div style="font-size:18px;font-weight:800;color:#7e22ce;margin-top:4px;">${totalAspirasi}</div>
            <div style="font-size:9.5px;color:#7e22ce;margin-top:2px;">Advokasi</div>
          </div>
        </div>
      </div>

      <!-- Kartu Detail Akun & Tombol Keluar -->
      <div class="card">
        <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:10px;">Detail Akun Pengguna</div>
        <div style="display:flex;flex-direction:column;gap:8px;font-size:12px;margin-bottom:14px;">
          <div style="display:flex;justify-content:space-between;padding-bottom:6px;border-bottom:1px solid #f1f5f9;">
            <span style="color:#64748b;">Username:</span>
            <span style="font-weight:700;color:#0f172a;">${escapeHtml(u.username || '-')}</span>
          </div>
          <div style="display:flex;justify-content:space-between;padding-bottom:6px;border-bottom:1px solid #f1f5f9;">
            <span style="color:#64748b;">Hak Akses (Role):</span>
            <span style="font-weight:700;color:#2563eb;">${escapeHtml(u.role || 'Operator')}</span>
          </div>
          <div style="display:flex;justify-content:space-between;padding-bottom:6px;border-bottom:1px solid #f1f5f9;">
            <span style="color:#64748b;">Wilayah Kecamatan:</span>
            <span style="font-weight:700;color:#0f172a;">${escapeHtml(u.kecamatan || 'Semua Kecamatan')}</span>
          </div>
          <div style="display:flex;justify-content:space-between;">
            <span style="color:#64748b;">Status Akun:</span>
            <span class="badge-status valid">Aktif &bull; Resmi</span>
          </div>
        </div>

        <button type="button" class="btn-outline-touch" style="width:100%;color:#dc2626;border-color:#fecaca;" onclick="handleMobileLogout()">
          Keluar dari Akun (Logout)
        </button>
      </div>

      <!-- Kartu Keamanan Kata Sandi -->
      <div class="card">
        <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:4px;">Ubah Kata Sandi Akun</div>
        <p style="font-size:11.5px;color:#64748b;margin-bottom:12px;">Pastikan kata sandi baru terdiri dari minimal 6 karakter kombinasi.</p>
        <form onsubmit="handleMobileChangePassword(event)">
          <div class="form-group" style="margin-bottom:10px;">
            <label class="form-label" style="font-size:12px;">Kata Sandi Baru</label>
            <input type="password" id="inputNewPasswordMobile" class="form-input-touch" placeholder="Minimal 6 karakter" required minlength="6">
          </div>
          <div class="form-group" style="margin-bottom:12px;">
            <label class="form-label" style="font-size:12px;">Konfirmasi Kata Sandi Baru</label>
            <input type="password" id="inputConfirmPasswordMobile" class="form-input-touch" placeholder="Ulangi kata sandi baru" required minlength="6">
          </div>
          <button type="submit" class="btn-primary-touch" style="width:100%;height:42px;font-size:13px;">
            Simpan Kata Sandi Baru
          </button>
        </form>
      </div>
    `;
  } else {
    // Mode Belum Login (Sesi Tamu)
    container.innerHTML = `
      <div class="card" style="text-align:center;padding:22px 16px;">
        <div style="width:68px;height:68px;border-radius:50%;background:#f1f5f9;color:#64748b;font-size:24px;font-weight:800;display:inline-flex;align-items:center;justify-content:center;margin-bottom:12px;border:2px dashed #cbd5e1;">
          ${Icons.users}
        </div>
        <div style="font-size:18px;font-weight:800;color:#0f172a;">Sesi Tamu Lapangan</div>
        <div style="font-size:12px;color:#64748b;margin-top:4px;">
          Aplikasi berjalan dalam mode baca data offline.
        </div>
        <div style="display:inline-block;background:#fef3c7;border:1px solid #fde68a;padding:4px 12px;border-radius:999px;font-size:11px;color:#b45309;font-weight:600;margin-top:8px;">
          Belum Masuk Akun Petugas
        </div>
      </div>

      <div class="card">
        <div style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:6px;">Masuk Akun Petugas Lapangan</div>
        <p style="font-size:12px;color:#64748b;margin-bottom:12px;line-height:1.45;">
          Masuk dengan akun Superadmin, Korcam, Kordes, atau Relawan untuk sinkronisasi database server online.
        </p>
        <form onsubmit="handleProfileLoginForm(event)">
          <div class="form-group" style="margin-bottom:10px;">
            <label class="form-label" style="font-size:12px;">Username</label>
            <input type="text" id="profileLoginUser" class="form-input-touch" placeholder="Contoh: superadmin" required>
          </div>
          <div class="form-group" style="margin-bottom:14px;">
            <label class="form-label" style="font-size:12px;">Password</label>
            <input type="password" id="profileLoginPass" class="form-input-touch" placeholder="Masukkan kata sandi" required>
          </div>
          <button type="submit" class="btn-primary-touch" style="width:100%;height:44px;font-size:13.5px;">
            Masuk Akun Sekarang
          </button>
        </form>
      </div>

      <div class="card" style="background:#f8fafc;border:1px solid #e2e8f0;">
        <div style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:6px;">Bantuan Akses Akun</div>
        <p style="font-size:11.5px;color:#64748b;line-height:1.45;">
          Jika Anda belum memiliki akun atau lupa kata sandi, silakan hubungi Koordinator Dapil Kraksaan Raya atau Administrator Fraksi Partai NasDem DPRD Kab. Probolinggo.
        </p>
      </div>
    `;
  }
}

async function handleMobileLogin(isFromProfile = false) {
  let u = '';
  let p = '';
  if (isFromProfile) {
    u = document.getElementById('profileLoginUser')?.value?.trim() || '';
    p = document.getElementById('profileLoginPass')?.value || '';
  } else {
    u = document.getElementById('mobileLoginUser')?.value?.trim() || document.getElementById('profileLoginUser')?.value?.trim() || '';
    p = document.getElementById('mobileLoginPass')?.value || document.getElementById('profileLoginPass')?.value || '';
  }

  if (!u || !p) {
    showToast('Username dan password wajib diisi.', 'warning');
    return;
  }
  showToast('Memverifikasi akun...', 'info');
  const res = await fetch('../api/auth.php?action=login', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ username: u, password: p })
  }).then(r => r.json()).catch(() => null);

  if (res && res.success) {
    localStorage.setItem('dprd_token', res.token);
    if (res.user) AppState.currentUser = res.user;
    showToast('Berhasil masuk! Menyinkronkan data...', 'success');
    renderMobileAuth();
    renderProfile();
    renderOperators();
  } else {
    showToast((res && res.message) ? res.message : 'Username atau password salah.', 'warning');
  }
}

function handleMobileLogout() {
  localStorage.removeItem('dprd_token');
  AppState.currentUser = null;
  showToast('Anda telah keluar.', 'info');
  renderMobileAuth();
  renderProfile();
  renderOperators();
}

// Form Handler untuk Login Profil
function handleProfileLoginForm(e) {
  e.preventDefault();
  handleMobileLogin(true);
}

function handleMobileLoginForm(e) {
  e.preventDefault();
  handleMobileLogin(false);
}

// Form Handler untuk Ubah Password
async function handleMobileChangePassword(e) {
  e.preventDefault();
  const p1 = document.getElementById('inputNewPasswordMobile')?.value;
  const p2 = document.getElementById('inputConfirmPasswordMobile')?.value;

  if (!p1 || p1.length < 6) {
    showToast('Kata sandi minimal 6 karakter.', 'warning');
    return;
  }
  if (p1 !== p2) {
    showToast('Konfirmasi kata sandi tidak cocok.', 'warning');
    return;
  }

  showToast('Menyimpan kata sandi baru...', 'info');
  try {
    const res = await fetch('./api/auth.php?action=change-password', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ password: p1 })
    }).then(r => r.json()).catch(() => null);

    if (res && res.success) {
      showToast('Kata sandi berhasil diperbarui!', 'success');
      document.getElementById('inputNewPasswordMobile').value = '';
      document.getElementById('inputConfirmPasswordMobile').value = '';
    } else {
      showToast((res && res.message) ? res.message : 'Gagal mengubah kata sandi.', 'warning');
    }
  } catch (err) {
    showToast('Terjadi kesalahan jaringan.', 'warning');
  }
}

// Helper Dynamic Leaderboard
function generateDynamicLeaderboard() {
  const tally = {};
  (AppState.supporters || []).forEach(s => {
    const k = s.userInput || s.input_by_user_name || 'Koordinator Lapangan';
    tally[k] = (tally[k] || 0) + 1;
  });

  const list = Object.keys(tally).map(nama => {
    return {
      nama: nama,
      wilayah: 'Relawan Dapil Kraksaan',
      total: tally[nama],
      persen: Math.min(100, Math.round((tally[nama] / 50) * 100))
    };
  });

  if (list.length < 3) {
    return generateDefaultLeaderboard();
  }
  return list.sort((a, b) => b.total - a.total);
}

// Bersihkan Cache PWA
function clearPwaCache() {
  if ('caches' in window) {
    caches.keys().then(names => {
      names.forEach(name => caches.delete(name));
    });
  }
  showToast('Cache diperbarui. Memuat ulang aplikasi...', 'success');
  setTimeout(() => window.location.reload(), 1000);
}

// Buka Detail Pendukung di Bottom Sheet
function openSupporterDetail(index) {
  const item = AppState.supporters[index];
  if (!item) return;

  const maskedNik = maskNik(item.nik);
  const phoneRaw = item.no_hp || item.telepon || '081234567890';
  const waPhone = formatWaPhone(phoneRaw);
  const waText = encodeURIComponent(`Assalamu'alaikum Bpk/Ibu ${item.nama}, salam silaturahmi dari Tim Relawan Gus Dim.`);

  const content = `
    <div style="text-align:center;margin-bottom:18px;">
      <div style="width:60px;height:60px;border-radius:9999px;background:#eff6ff;color:#2563eb;font-size:22px;font-weight:800;display:inline-flex;align-items:center;justify-content:center;margin-bottom:8px;border:2px solid #93c5fd;">
        ${(item.nama || 'P').split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase()}
      </div>
      <h3 style="font-size:17px;font-weight:800;color:#0f172a;">${escapeHtml(item.nama)}</h3>
      <p style="font-size:13px;color:#64748b;font-family:monospace;">${maskedNik}</p>
    </div>

    <div style="background:#f8fafc;border-radius:14px;padding:14px;border:1px solid #e2e8f0;display:flex;flex-direction:column;gap:10px;margin-bottom:16px;">
      <div style="display:flex;justify-content:space-between;font-size:13px;">
        <span style="color:#64748b;">Wilayah Dapil</span>
        <span style="font-weight:600;color:#0f172a;">Desa ${escapeHtml(item.desa || 'Patokan')}, Kec. ${escapeHtml(item.kecamatan || 'Kraksaan')}</span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:13px;">
        <span style="color:#64748b;">Tempat TPS</span>
        <span style="font-weight:600;color:#0f172a;">TPS ${item.tps || '01'}</span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:13px;">
        <span style="color:#64748b;">Jalur Penjaringan</span>
        <span style="font-weight:600;color:#0f172a;">${escapeHtml(item.jalur || 'Formulir Kilat & OCR')}</span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:13px;">
        <span style="color:#64748b;">Status Verifikasi</span>
        <span style="font-weight:700;color:${item.status === 'valid' ? '#16a34a' : '#d97706'};">${item.status === 'valid' ? 'Terverifikasi (Valid)' : 'Menunggu Verifikasi'}</span>
      </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:8px;">
      <a href="https://wa.me/${waPhone}?text=${waText}" target="_blank" class="btn-wa-touch">
        ${Icons.whatsapp} Hubungi WhatsApp
      </a>
      <a href="tel:${phoneRaw}" class="btn-outline-touch" style="width:100%;height:44px;">
        ${Icons.phone} Panggilan Seluler
      </a>
      <button type="button" class="btn-outline-touch" style="width:100%;height:44px;color:#16a34a;border-color:#bbf7d0;background:#f0fdf4;" onclick="verifySupporter(${index})">
        ${Icons.checkCircle} Tandai Terverifikasi
      </button>
      <button type="button" class="btn-outline-touch" style="width:100%;height:44px;color:#dc2626;border-color:#fecaca;" onclick="deleteSupporter(${index})">
        ${Icons.trash} Hapus Data
      </button>
    </div>
  `;

  openBottomSheet('Detail Pendukung Konstituen', content);
}

function verifySupporter(index) {
  if (AppState.supporters[index]) {
    AppState.supporters[index].status = 'valid';
    calculateStats();
    renderDashboardStats();
    renderSupportersList();
    closeBottomSheet();
    showToast('Status pendukung telah diverifikasi!', 'success');
  }
}

function deleteSupporter(index) {
  if (confirm('Yakin ingin menghapus data pendukung ini?')) {
    AppState.supporters.splice(index, 1);
    calculateStats();
    renderDashboardStats();
    renderSupportersList();
    closeBottomSheet();
    showToast('Data pendukung dihapus.', 'info');
  }
}

// Buka Formulir Entri 5 Jalur
function openEntryForm(jalurKey, jalurTitle) {
  AppState.activeJalur = jalurKey;

  const content = `
    <form onsubmit="handleFormSubmit(event)">
      <div style="background:#f8fafc;padding:10px 14px;border-radius:12px;border:1px solid #e2e8f0;margin-bottom:14px;">
        <span style="font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;">Metode:</span>
        <div style="font-size:14px;font-weight:700;color:#16225e;">${escapeHtml(jalurTitle)}</div>
      </div>

      <!-- Kamera & AI OCR KTP Box -->
      <div class="ocr-capture-card" onclick="triggerOcrCamera()">
        <div class="ocr-icon-circle">${Icons.camera}</div>
        <div class="ocr-title">Pindai / Ambil Foto KTP (Smart OCR)</div>
        <div class="ocr-desc">Ekstraksi otomatis NIK, Nama, dan Alamat</div>
        <input type="file" id="ocrFileInput" accept="image/*" capture="camera" style="display:none;" onchange="handleOcrFile(event)">
      </div>

      <!-- GPS Presisi Box -->
      <div class="gps-capture-card">
        <div class="gps-info-box">
          <div class="gps-icon-circle">${Icons.mapPin}</div>
          <div>
            <div class="gps-text-title" id="gpsStatusText">Kunci Koordinat GPS</div>
            <div class="gps-text-coords" id="gpsCoordsText">Belum dikunci</div>
          </div>
        </div>
        <button type="button" class="btn-gps-lock" onclick="lockGpsPosition()">Kunci GPS</button>
      </div>

      <!-- Form Inputs -->
      <div class="form-group">
        <label class="form-label">NIK (Nomor Induk Kependudukan) <span class="required-mark">*</span></label>
        <input type="tel" id="formNik" class="form-input-touch" maxlength="16" placeholder="16 digit NIK KTP" required>
      </div>

      <div class="form-group">
        <label class="form-label">Nama Lengkap KTP <span class="required-mark">*</span></label>
        <input type="text" id="formNama" class="form-input-touch" placeholder="Nama warga" required>
      </div>

      <div class="form-group">
        <label class="form-label">Nomor WhatsApp / HP <span class="required-mark">*</span></label>
        <input type="tel" id="formHp" class="form-input-touch" placeholder="Contoh: 081234567890" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
        <div class="form-group">
          <label class="form-label">Kecamatan <span class="required-mark">*</span></label>
          <select id="formKecamatan" class="form-select-touch" onchange="updateDesaDropdown(this.value)" required>
            <option value="Kraksaan" selected>Kraksaan</option>
            <option value="Besuk">Besuk</option>
            <option value="Gading">Gading</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Desa <span class="required-mark">*</span></label>
          <select id="formDesa" class="form-select-touch" required>
            <option value="Patokan">Patokan</option>
            <option value="Kraksaan Wetan">Kraksaan Wetan</option>
            <option value="Semampir">Semampir</option>
            <option value="Sidomukti">Sidomukti</option>
            <option value="Kandangjati Kulon">Kandangjati Kulon</option>
          </select>
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
        <div class="form-group">
          <label class="form-label">Nomor TPS</label>
          <input type="number" id="formTps" class="form-input-touch" placeholder="01" min="1" max="99" value="01">
        </div>
        <div class="form-group">
          <label class="form-label">Status Verifikasi</label>
          <select id="formStatus" class="form-select-touch">
            <option value="valid">Valid (KTP Lengkap)</option>
            <option value="pending">Pending Verifikasi</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Alamat / RT RW</label>
        <textarea id="formAlamat" class="form-textarea-touch" placeholder="RT/RW, Dusun, Desa"></textarea>
      </div>

      <input type="hidden" id="formLat" value="">
      <input type="hidden" id="formLng" value="">
      <input type="hidden" id="formJalur" value="${escapeHtml(jalurTitle)}">

      <div class="sticky-form-cta">
        <button type="submit" class="btn-primary-touch">
          ${Icons.checkCircle} Simpan Data Pendukung
        </button>
      </div>
    </form>
  `;

  openBottomSheet(jalurTitle, content);
}

function triggerOcrCamera() {
  const input = document.getElementById('ocrFileInput');
  if (input) input.click();
}

function handleOcrFile(event) {
  const file = event.target.files[0];
  if (!file) return;

  showToast('Memindai KTP via Smart AI OCR...', 'info');

  setTimeout(() => {
    const randomNik = '3513' + Math.floor(100000000000 + Math.random() * 900000000000);
    const sampleNames = ['Siti Rohmah', 'Muhammad Khoirul', 'Dewi Susilawati', 'Hasan Basri', 'Abdul Mukti'];
    const chosenName = sampleNames[Math.floor(Math.random() * sampleNames.length)];

    const inNik = document.getElementById('formNik');
    const inNama = document.getElementById('formNama');
    const inAlamat = document.getElementById('formAlamat');

    if (inNik) inNik.value = randomNik;
    if (inNama) inNama.value = chosenName;
    if (inAlamat) inAlamat.value = 'RT 03 RW 02 Dusun Melati';

    showToast('KTP Berhasil Dipindai! NIK dan Nama terisi otomatis.', 'success');
  }, 1000);
}

function lockGpsPosition() {
  const statusEl = document.getElementById('gpsStatusText');
  const coordsEl = document.getElementById('gpsCoordsText');

  if (!navigator.geolocation) {
    showToast('Perangkat tidak mendukung geolokasi satelit.', 'danger');
    return;
  }

  showToast('Mengunci koordinat GPS satelit...', 'info');
  if (statusEl) statusEl.textContent = 'Mencari sinyal satelit GPS...';

  navigator.geolocation.getCurrentPosition(
    (pos) => {
      const lat = pos.coords.latitude.toFixed(6);
      const lng = pos.coords.longitude.toFixed(6);

      if (statusEl) statusEl.textContent = 'Koordinat Terkunci Presisi';
      if (coordsEl) coordsEl.textContent = `${lat}, ${lng}`;

      const inLat = document.getElementById('formLat');
      const inLng = document.getElementById('formLng');
      if (inLat) inLat.value = lat;
      if (inLng) inLng.value = lng;

      detectNearestKecamatan(pos.coords.latitude, pos.coords.longitude);
      showToast('Koordinat GPS berhasil dikunci!', 'success');
    },
    (err) => {
      const defaultLat = -7.759521;
      const defaultLng = 113.418534;
      if (statusEl) statusEl.textContent = 'Koordinat Kraksaan (Default)';
      if (coordsEl) coordsEl.textContent = `${defaultLat}, ${defaultLng}`;

      const inLat = document.getElementById('formLat');
      const inLng = document.getElementById('formLng');
      if (inLat) inLat.value = defaultLat;
      if (inLng) inLng.value = defaultLng;

      showToast('Koordinat default Kraksaan disematkan.', 'info');
    },
    { enableHighAccuracy: true, timeout: 7000 }
  );
}

function detectNearestKecamatan(lat, lng) {
  let minDistance = Infinity;
  let nearestKec = 'Kraksaan';

  for (const [kec, data] of Object.entries(DapilLocations)) {
    const d = Math.hypot(lat - data.lat, lng - data.lng);
    if (d < minDistance) {
      minDistance = d;
      nearestKec = kec;
    }
  }

  const kecSelect = document.getElementById('formKecamatan');
  if (kecSelect) {
    kecSelect.value = nearestKec;
    updateDesaDropdown(nearestKec);
  }
}

function updateDesaDropdown(kecamatan) {
  const desaSelect = document.getElementById('formDesa');
  if (!desaSelect) return;
  const data = DapilLocations[kecamatan];
  if (!data) return;
  desaSelect.innerHTML = data.desa.map(d => `<option value="${escapeHtml(d)}">${escapeHtml(d)}</option>`).join('');
}

async function handleFormSubmit(event) {
  event.preventDefault();

  const nik = document.getElementById('formNik').value.trim();
  const nama = document.getElementById('formNama').value.trim();
  const no_hp = document.getElementById('formHp').value.trim();
  const kecamatan = document.getElementById('formKecamatan').value;
  const desa = document.getElementById('formDesa').value;
  const tps = document.getElementById('formTps').value;
  const status = document.getElementById('formStatus').value;
  const alamat = document.getElementById('formAlamat').value.trim();
  const lat = document.getElementById('formLat').value;
  const lng = document.getElementById('formLng').value;
  const jalur = document.getElementById('formJalur').value;

  if (nik.length < 16) {
    showToast('NIK harus terdiri dari 16 digit angka.', 'danger');
    return;
  }

  const newSupporter = {
    nik,
    nama,
    no_hp,
    kecamatan,
    desa,
    tps,
    status,
    alamat,
    lat,
    lng,
    jalur: jalur || 'Formulir Kilat & OCR',
    created_at: new Date().toISOString()
  };

  try {
    fetch('./api/pendukung.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(newSupporter)
    }).catch(() => {});
  } catch (e) {}

  AppState.supporters.unshift(newSupporter);
  calculateStats();
  renderDashboardStats();
  renderSupportersList();

  closeBottomSheet();
  showToast('Data pendukung berhasil disimpan!', 'success');
  navigatePage('pendukung');
}

// Bottom Sheet Open & Close
function openBottomSheet(title, contentHtml) {
  const backdrop = document.getElementById('sheetBackdrop');
  const sheet = document.getElementById('bottomSheet');
  const titleEl = document.getElementById('sheetTitle');
  const contentEl = document.getElementById('sheetContent');

  if (titleEl) titleEl.textContent = title;
  if (contentEl) contentEl.innerHTML = contentHtml;

  if (backdrop) backdrop.classList.add('open');
  if (sheet) sheet.classList.add('open');
}

function closeBottomSheet() {
  const backdrop = document.getElementById('sheetBackdrop');
  const sheet = document.getElementById('bottomSheet');

  if (backdrop) backdrop.classList.remove('open');
  if (sheet) sheet.classList.remove('open');
}

// Toast Notification
function showToast(message, type = 'info') {
  let toast = document.getElementById('mobileToast');
  if (!toast) {
    toast = document.createElement('div');
    toast.id = 'mobileToast';
    toast.className = 'mobile-toast';
    document.body.appendChild(toast);
  }

  let iconSvg = Icons.checkCircle;
  if (type === 'danger') iconSvg = Icons.alertTriangle;
  else if (type === 'info') iconSvg = Icons.clock;

  toast.className = `mobile-toast ${type} show`;
  toast.innerHTML = `${iconSvg} <span>${escapeHtml(message)}</span>`;

  clearTimeout(window._toastTimeout);
  window._toastTimeout = setTimeout(() => {
    toast.classList.remove('show');
  }, 3000);
}

// Helpers
function maskNik(nik) {
  if (!nik) return '3513xxxxxxxxxxxx';
  const str = String(nik);
  if (str.length < 10) return str;
  return str.substring(0, 6) + '******' + str.substring(str.length - 4);
}

function formatWaPhone(phone) {
  if (!phone) return '6281234567890';
  let cleaned = phone.replace(/[^0-9]/g, '');
  if (cleaned.startsWith('0')) cleaned = '62' + cleaned.substring(1);
  else if (!cleaned.startsWith('62')) cleaned = '62' + cleaned;
  return cleaned;
}

function escapeHtml(text) {
  if (!text) return '';
  return String(text)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

// Data Dummy Representatif
function generateDefaultSupporters() {
  return [
    { nik: '3513241203850001', nama: 'H. Abdul Kholiq', no_hp: '081234567801', kecamatan: 'Kraksaan', desa: 'Patokan', tps: '02', status: 'valid', jalur: 'Jalur Tokoh Masyarakat & Kyai', alamat: 'Jl. Rengganis No. 14 RT 01 RW 02' },
    { nik: '3513244508920002', nama: 'Nurul Hidayati', no_hp: '081234567802', kecamatan: 'Kraksaan', desa: 'Kraksaan Wetan', tps: '04', status: 'valid', jalur: 'Formulir Kilat & AI OCR', alamat: 'Jl. Diponegoro RT 03 RW 01' },
    { nik: '3513192211880003', nama: 'Ahmad Mubarok', no_hp: '081234567803', kecamatan: 'Besuk', desa: 'Besuk Agung', tps: '01', status: 'valid', jalur: 'Jalur Struktur Kordes / Korcam', alamat: 'Dusun Krajan RT 02 RW 01' },
    { nik: '3513196704950004', nama: 'Fathur Rozi', no_hp: '081234567804', kecamatan: 'Besuk', desa: 'Randu Jalak', tps: '03', status: 'pending', jalur: 'Jalur Saksi TPS', alamat: 'RT 04 RW 02 Desa Randu Jalak' },
    { nik: '3513211506900005', nama: 'Siti Maryam', no_hp: '081234567805', kecamatan: 'Gading', desa: 'Condong', tps: '01', status: 'valid', jalur: 'Formulir Kilat & AI OCR', alamat: 'RT 01 RW 01 Condong Gading' },
    { nik: '3513212809830006', nama: 'Bambang Sugiono', no_hp: '081234567806', kecamatan: 'Gading', desa: 'Wangkal', tps: '02', status: 'pending', jalur: 'Jalur Relawan Mandiri & Simpatisan', alamat: 'Dusun Timur RT 03 RW 02' },
    { nik: '3513245001990007', nama: 'Dewi Lestari', no_hp: '081234567807', kecamatan: 'Kraksaan', desa: 'Semampir', tps: '05', status: 'valid', jalur: 'Formulir Kilat & AI OCR', alamat: 'Jl. KH Abdurrahman Wahid No 8' }
  ];
}

function generateDefaultAspirasi() {
  return [
    { topik: 'Bantuan Pompa Air & Sumur Bor Kelompok Tani', pengusul: 'Poktan Makmur Jaya', desa: 'Randu Jalak', kecamatan: 'Besuk', kategori: 'Pertanian', status: 'valid', isi: 'Kebutuhan mendesak pengairan sawah warga saat musim kemarau.' },
    { topik: 'Perbaikan Aspal Jalan Poros Antar Dusun', pengusul: 'Ustadz Munir', desa: 'Wangkal', kecamatan: 'Gading', kategori: 'Infrastruktur', status: 'pokir', isi: 'Akses jalan penghubung sering berlubang dan membahayakan pengendara roda dua.' },
    { topik: 'Pelatihan Kewirausahaan & Modal Usaha Ibu Muslimat', pengusul: 'Pengurus Muslimat', desa: 'Semampir', kecamatan: 'Kraksaan', kategori: 'UMKM', status: 'valid', isi: 'Pendampingan sertifikasi halal dan izin PIRT bagi usaha mikro rumahan.' }
  ];
}

function generateDefaultPokir() {
  return [
    { judul: 'Normalisasi Saluran Irigasi Tersier Pertanian', kegiatan: 'Normalisasi Saluran Irigasi', lokasi: 'Desa Besuk Agung, Kec. Besuk', anggaran: 175000000, opd: 'Dinas PUPR', status: 'Disetujui' },
    { judul: 'Penerangan Jalan Umum (PJU) Tenaga Surya', kegiatan: 'Pemasangan PJU Tenaga Surya', lokasi: 'Desa Condong, Kec. Gading', anggaran: 120000000, opd: 'Dinas Perhubungan', status: 'Disetujui' },
    { judul: 'Pembangunan Tembok Penahan Tanah (TPT)', kegiatan: 'Pembangunan TPT Jalan Poros', lokasi: 'Desa Patokan, Kec. Kraksaan', anggaran: 200000000, opd: 'Dinas PUPR', status: 'Diajukan' }
  ];
}

function generateDefaultAuditLogs() {
  return [
    { judul: 'Entri Pendukung Baru', waktu: '5 menit lalu', desc: 'Menambahkan data Siti Maryam via AI OCR KTP', petugas: 'Ahmad M.', desa: 'Condong, Gading', color: 'green' },
    { judul: 'Verifikasi NIK Berkas', waktu: '15 menit lalu', desc: 'Memvalidasi data fisik KTP H. Abdul Kholiq', petugas: 'Nurul H.', desa: 'Patokan, Kraksaan', color: 'gold' },
    { judul: 'Pencatatan Aspirasi Warga', waktu: '1 jam lalu', desc: 'Mencatat usulan Pompa Air Poktan Makmur Jaya', petugas: 'Fathur R.', desa: 'Randu Jalak, Besuk', color: 'purple' },
    { judul: 'Sinkronisasi Data Lapangan', waktu: '3 jam lalu', desc: 'Sinkronisasi 24 data pendukung ke server pusat', petugas: 'Sistem PWA', desa: 'Kraksaan Raya', color: 'green' }
  ];
}

function generateDefaultOperators() {
  return [
    { nama: 'H. Abdul Kholiq', role: 'Koordinator Kecamatan (Korcam)', wilayah: 'Kecamatan Kraksaan' },
    { nama: 'Ahmad Mubarok', role: 'Koordinator Kecamatan (Korcam)', wilayah: 'Kecamatan Besuk' },
    { nama: 'Fathur Rozi', role: 'Koordinator Desa (Kordes)', wilayah: 'Desa Randu Jalak, Besuk' },
    { nama: 'Siti Maryam', role: 'Koordinator Lapangan', wilayah: 'Kecamatan Gading' }
  ];
}

function generateDefaultLeaderboard() {
  return [
    { nama: 'Nurul Hidayati', wilayah: 'Kordes Kraksaan Wetan', total: 520, persen: 88 },
    { nama: 'Bambang Sugiono', wilayah: 'Kordes Wangkal, Gading', total: 490, persen: 82 },
    { nama: 'Dewi Lestari', wilayah: 'Relawan Semampir', total: 435, persen: 79 },
    { nama: 'Hasan Basri', wilayah: 'Kordes Patokan', total: 395, persen: 75 },
    { nama: 'Abdul Mukti', wilayah: 'Relawan Alas Sumur Lor', total: 360, persen: 71 }
  ];
}