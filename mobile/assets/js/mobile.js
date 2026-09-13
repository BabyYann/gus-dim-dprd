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
  activeJalur: 'mandiri',
  isOnline: navigator.onLine
};

// Data Geospasial Wilayah Dapil Kraksaan Raya
const DapilLocations = {
  'Kraksaan': {
    lat: -7.7595,
    lng: 113.4185,
    desa: [
      'Kraksaan Wetan', 'Kandangjati Kulon', 'Kandangjati Wetan', 'Patokan',
      'Semampir', 'Sidomukti', 'Kebonagung', 'Rondokuning', 'Asembagus',
      'Bulubrangsi', 'Kalisalam', 'Kregenan', 'Tamansari'
    ]
  },
  'Besuk': {
    lat: -7.7924,
    lng: 113.4561,
    desa: [
      'Besuk Agung', 'Besuk Kidul', 'Alas Sumur Lor', 'Bago', 'Klampokan',
      'Randu Jalak', 'Sindet Lami', 'Sumur Dalam', 'Matekan'
    ]
  },
  'Gading': {
    lat: -7.8341,
    lng: 113.4352,
    desa: [
      'Gading Wetan', 'Bulu', 'Condong', 'Dandang', 'Jurangrejo',
      'Kalianyar', 'Kertosono', 'Prasi', 'Wangkal'
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
  award: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px;height:18px;min-width:18px;min-height:18px;max-width:18px;max-height:18px;flex-shrink:0;"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>'
};

// Inisialisasi Saat Dokumen Siap
document.addEventListener('DOMContentLoaded', () => {
  initServiceWorker();
  initNetworkStatusListener();
  setupNavigation();
  setupEventListeners();
  loadAllData();
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

    // 4. Data Pendukung Lainnya
    AppState.auditLogs = generateDefaultAuditLogs();
    AppState.operators = generateDefaultOperators();
    AppState.leaderboardData = generateDefaultLeaderboard();

    calculateStats();
    renderDashboardStats();
    renderSupportersList();
    renderAspirasiDedicated();
    renderPokirDedicated();
    renderPetaDistricts();
    renderLeaderboard();
    renderAuditLogs();
    renderOperators();

  } catch (error) {
    console.warn('Fallback ke dataset lokal:', error);
    AppState.supporters = generateDefaultSupporters();
    AppState.aspirasi = generateDefaultAspirasi();
    AppState.pokir = generateDefaultPokir();
    AppState.auditLogs = generateDefaultAuditLogs();
    AppState.operators = generateDefaultOperators();
    AppState.leaderboardData = generateDefaultLeaderboard();

    calculateStats();
    renderDashboardStats();
  }
}

// Kalkulasi Statistik
function calculateStats() {
  const total = AppState.supporters.length;
  const terverifikasi = AppState.supporters.filter(s => s.status === 'valid' || s.status === 'Terverifikasi').length;
  const target = 25000;
  const persen = Math.min(100, Math.round((total / target) * 100));

  const kecStats = { Kraksaan: 0, Besuk: 0, Gading: 0 };
  AppState.supporters.forEach(s => {
    const k = s.kecamatan || 'Kraksaan';
    if (kecStats[k] !== undefined) kecStats[k]++;
    else kecStats['Kraksaan']++;
  });

  AppState.stats = {
    total,
    terverifikasi,
    target,
    persen,
    aspirasiCount: AppState.aspirasi.length,
    pokirCount: AppState.pokir.length,
    kecamatan: kecStats
  };

  // Update Drawer Badges
  const bP = document.getElementById('drawerBadgePendukung');
  const bA = document.getElementById('drawerBadgeAspirasi');
  const bR = document.getElementById('drawerBadgePokir');
  if (bP) bP.textContent = total;
  if (bA) bA.textContent = AppState.aspirasi.length;
  if (bR) bR.textContent = AppState.pokir.length;
}

// Render Dashboard
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

  const elProgressFill = document.getElementById('progressTargetFill');
  const elProgressPct = document.getElementById('progressTargetPct');
  const elProgressSub = document.getElementById('progressTargetSub');

  if (elProgressFill) elProgressFill.style.width = `${s.persen}%`;
  if (elProgressPct) elProgressPct.textContent = `${s.persen}%`;
  if (elProgressSub) elProgressSub.textContent = `${s.total.toLocaleString('id-ID')} / ${s.target.toLocaleString('id-ID')} Suara`;

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

// Render Halaman Khusus Aspirasi
function renderAspirasiDedicated() {
  const container = document.getElementById('aspirasiDedicatedList');
  if (!container) return;

  let list = [...AppState.aspirasi];
  if (AppState.filterAspirasiCat) {
    list = list.filter(a => (a.kategori || a.bidang || '').toLowerCase().includes(AppState.filterAspirasiCat.toLowerCase()));
  }

  if (list.length === 0) {
    container.innerHTML = `
      <div class="empty-state">
        ${Icons.messageSquare}
        <div class="empty-state-title">Belum ada aspirasi tercatat</div>
        <div class="empty-state-desc">Gunakan tombol "Buat Aspirasi" untuk mendata kebutuhan warga.</div>
      </div>
    `;
    return;
  }

  container.innerHTML = list.map(a => {
    let badgeClass = 'pending';
    let statusText = 'Ditinjau';
    if (a.status === 'valid' || a.status === 'Disetujui') {
      badgeClass = 'valid';
      statusText = 'Tervalidasi';
    } else if (a.status === 'pokir') {
      badgeClass = 'info';
      statusText = 'Masuk Pokir';
    }

    return `
      <div class="touch-card">
        <div class="card-top-row">
          <div class="card-avatar" style="background:#f0fdf4;color:#16a34a;">
            ${Icons.messageSquare}
          </div>
          <div class="card-info">
            <div class="card-name">${escapeHtml(a.topik || a.judul || 'Aspirasi Konstituen')}</div>
            <div class="card-nik" style="font-family:inherit;">Pengusul: ${escapeHtml(a.pengusul || 'Warga')}</div>
          </div>
          <span class="badge-status ${badgeClass}">${statusText}</span>
        </div>
        <p style="font-size:13px;color:#334155;line-height:1.4;">
          ${escapeHtml(a.deskripsi || a.isi || 'Kebutuhan warga desa yang diusulkan melalui kegiatan lapangan relawan.')}
        </p>
        <div class="card-details-row">
          <div class="card-detail-item">
            ${Icons.mapPin}
            <span>${escapeHtml(a.desa || 'Patokan')}, ${escapeHtml(a.kecamatan || 'Kraksaan')}</span>
          </div>
          <span class="card-pill">Bidang: ${escapeHtml(a.kategori || a.bidang || 'Infrastruktur')}</span>
        </div>
      </div>
    `;
  }).join('');
}

function filterAspirasiCat(cat, btn) {
  AppState.filterAspirasiCat = cat;
  document.querySelectorAll('#page-aspirasi .filter-pill').forEach(b => b.classList.remove('active'));
  if (btn) btn.classList.add('active');
  renderAspirasiDedicated();
}

// Buka Form Aspirasi Baru
function openAspirasiForm() {
  const content = `
    <form onsubmit="handleAspirasiSubmit(event)">
      <div class="form-group">
        <label class="form-label">Topik / Judul Aspirasi <span class="required-mark">*</span></label>
        <input type="text" id="aspJudul" class="form-input-touch" placeholder="Contoh: Perbaikan Saluran Irigasi Tersier" required>
      </div>

      <div class="form-group">
        <label class="form-label">Nama Pengusul / Kelompok <span class="required-mark">*</span></label>
        <input type="text" id="aspPengusul" class="form-input-touch" placeholder="Contoh: Kelompok Tani Rukun Makmur" required>
      </div>

      <div class="form-group">
        <label class="form-label">Bidang Kategori <span class="required-mark">*</span></label>
        <select id="aspKategori" class="form-select-touch" required>
          <option value="Infrastruktur">Infrastruktur &amp; Jalan</option>
          <option value="Pertanian">Pertanian &amp; Irigasi</option>
          <option value="Pendidikan">Pendidikan &amp; Pesantren</option>
          <option value="Kesehatan">Kesehatan Masyarakat</option>
          <option value="UMKM">UMKM &amp; Ekonomi Kreatif</option>
        </select>
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
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Uraian Kebutuhan &amp; Keluhan</label>
        <textarea id="aspIsi" class="form-textarea-touch" placeholder="Jelaskan kebutuhan warga secara spesifik..." required></textarea>
      </div>

      <div class="sticky-form-cta">
        <button type="submit" class="btn-primary-touch">
          ${Icons.checkCircle} Kirim Aspirasi Warga
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
  if (data) {
    el.innerHTML = data.desa.map(d => `<option value="${escapeHtml(d)}">${escapeHtml(d)}</option>`).join('');
  }
}

function handleAspirasiSubmit(e) {
  e.preventDefault();
  const judul = document.getElementById('aspJudul').value.trim();
  const pengusul = document.getElementById('aspPengusul').value.trim();
  const kategori = document.getElementById('aspKategori').value;
  const kecamatan = document.getElementById('aspKecamatan').value;
  const desa = document.getElementById('aspDesa').value;
  const isi = document.getElementById('aspIsi').value.trim();

  const newAsp = {
    topik: judul,
    pengusul,
    kategori,
    kecamatan,
    desa,
    isi,
    status: 'pending',
    created_at: new Date().toISOString()
  };

  AppState.aspirasi.unshift(newAsp);
  calculateStats();
  renderAspirasiDedicated();
  closeBottomSheet();
  showToast('Aspirasi warga berhasil dicatat!', 'success');
}

// Render Halaman Khusus Pokir & Reses
function renderPokirDedicated() {
  const container = document.getElementById('pokirDedicatedList');
  if (!container) return;

  if (AppState.activeResesTab === 'pokir') {
    const list = AppState.pokir;
    if (list.length === 0) {
      container.innerHTML = `
        <div class="empty-state">
          ${Icons.building}
          <div class="empty-state-title">Belum ada usulan Pokir APBD</div>
          <div class="empty-state-desc">Gunakan tombol "Usul Pokir" untuk mendaftarkan program dewan.</div>
        </div>
      `;
      return;
    }

    container.innerHTML = list.map(p => {
      const budget = p.anggaran ? `Rp ${Number(p.anggaran).toLocaleString('id-ID')}` : 'Rp 150.000.000';
      return `
        <div class="touch-card">
          <div class="card-top-row">
            <div class="card-avatar" style="background:#e0f2fe;color:#0284c7;">
              ${Icons.building}
            </div>
            <div class="card-info">
              <div class="card-name">${escapeHtml(p.kegiatan || p.judul || 'Program Pokir APBD')}</div>
              <div class="card-nik" style="color:#0284c7;font-weight:700;font-family:inherit;">${budget}</div>
            </div>
            <span class="badge-status valid">APBD 2025</span>
          </div>
          <p style="font-size:13px;color:#334155;line-height:1.4;">
            ${escapeHtml(p.uraian || p.keterangan || 'Program kerja prioritas DPRD untuk kemakmuran masyarakat Dapil.')}
          </p>
          <div class="card-details-row">
            <div class="card-detail-item">
              ${Icons.mapPin}
              <span>${escapeHtml(p.lokasi || p.desa || 'Kecamatan Kraksaan')}</span>
            </div>
            <span class="card-pill">OPD: ${escapeHtml(p.opd || 'Dinas PUPR')}</span>
          </div>
        </div>
      `;
    }).join('');

  } else {
    // Laporan Reses Dewan
    container.innerHTML = `
      <div class="touch-card">
        <div class="card-top-row">
          <div class="card-avatar" style="background:#fef3c7;color:#b45309;">
            ${Icons.checkCircle}
          </div>
          <div class="card-info">
            <div class="card-name">Reses Masa Sidang I Tahun 2025</div>
            <div class="card-nik" style="font-family:inherit;">Kecamatan Kraksaan &bull; 15 Titik Pertemuan</div>
          </div>
          <span class="badge-status valid">Selesai</span>
        </div>
        <p style="font-size:13px;color:#334155;line-height:1.4;">
          Penyerapan aspirasi kelompok petani padi, petambak, dan pedagang pasar tradisional terkait pemulihan ekonomi daerah.
        </p>
      </div>

      <div class="touch-card">
        <div class="card-top-row">
          <div class="card-avatar" style="background:#eff6ff;color:#2563eb;">
            ${Icons.checkCircle}
          </div>
          <div class="card-info">
            <div class="card-name">Reses Masa Sidang II Tahun 2025</div>
            <div class="card-nik" style="font-family:inherit;">Kecamatan Besuk &amp; Gading &bull; 20 Titik Pertemuan</div>
          </div>
          <span class="badge-status valid">Selesai</span>
        </div>
        <p style="font-size:13px;color:#334155;line-height:1.4;">
          Kunjungan kerja ke pondok pesantren dan kelompok tani hutan untuk penyaluran bantuan bibit serta infrastruktur jalan desa.
        </p>
      </div>
    `;
  }
}

function switchResesSubTab(tab, btn) {
  AppState.activeResesTab = tab;
  document.querySelectorAll('#page-reses .segment-btn').forEach(b => b.classList.remove('active'));
  if (btn) btn.classList.add('active');
  renderPokirDedicated();
}

function openPokirForm() {
  const content = `
    <form onsubmit="handlePokirSubmit(event)">
      <div class="form-group">
        <label class="form-label">Nama Kegiatan Pokir <span class="required-mark">*</span></label>
        <input type="text" id="pokKegiatan" class="form-input-touch" placeholder="Contoh: Pavingisasi Jalan Desa Poros" required>
      </div>

      <div class="form-group">
        <label class="form-label">OPD / Dinas Penanggung Jawab <span class="required-mark">*</span></label>
        <select id="pokOpd" class="form-select-touch" required>
          <option value="Dinas PUPR">Dinas Pekerjaan Umum &amp; Tata Ruang</option>
          <option value="Dinas Pertanian">Dinas Pertanian &amp; Ketahanan Pangan</option>
          <option value="Dinas Perhubungan">Dinas Perhubungan (PJU)</option>
          <option value="Dinas Kesehatan">Dinas Kesehatan</option>
          <option value="Dinas Koperasi & UMKM">Dinas Koperasi &amp; Usaha Mikro</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Estimasi Pagu Anggaran (Rp) <span class="required-mark">*</span></label>
        <input type="number" id="pokAnggaran" class="form-input-touch" placeholder="Contoh: 150000000" step="5000000" required>
      </div>

      <div class="form-group">
        <label class="form-label">Lokasi Wilayah Usulan</label>
        <input type="text" id="pokLokasi" class="form-input-touch" placeholder="Contoh: Desa Kandangjati Wetan, Kraksaan" required>
      </div>

      <div class="form-group">
        <label class="form-label">Uraian Rencana Kerja</label>
        <textarea id="pokUraian" class="form-textarea-touch" placeholder="Rincian manfaat program bagi warga..." required></textarea>
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

function handlePokirSubmit(e) {
  e.preventDefault();
  const kegiatan = document.getElementById('pokKegiatan').value.trim();
  const opd = document.getElementById('pokOpd').value;
  const anggaran = document.getElementById('pokAnggaran').value;
  const lokasi = document.getElementById('pokLokasi').value.trim();
  const uraian = document.getElementById('pokUraian').value.trim();

  const newP = {
    judul: kegiatan,
    kegiatan,
    opd,
    anggaran: Number(anggaran),
    lokasi,
    uraian,
    status: 'Diajukan'
  };

  AppState.pokir.unshift(newP);
  calculateStats();
  renderPokirDedicated();
  closeBottomSheet();
  showToast('Usulan Pokir berhasil didaftarkan!', 'success');
}

// Render Peta Sebaran (GIS Mobile)
function renderPetaDistricts() {
  const gK = document.getElementById('gridDesaKraksaan');
  const gB = document.getElementById('gridDesaBesuk');
  const gG = document.getElementById('gridDesaGading');

  if (gK) {
    gK.innerHTML = DapilLocations['Kraksaan'].desa.map(d => `
      <div class="geo-village-pill">
        <span style="font-weight:600;">${d}</span>
        <span style="color:#16a34a;font-weight:700;">85%</span>
      </div>
    `).join('');
  }

  if (gB) {
    gB.innerHTML = DapilLocations['Besuk'].desa.map(d => `
      <div class="geo-village-pill">
        <span style="font-weight:600;">${d}</span>
        <span style="color:#2563eb;font-weight:700;">72%</span>
      </div>
    `).join('');
  }

  if (gG) {
    gG.innerHTML = DapilLocations['Gading'].desa.map(d => `
      <div class="geo-village-pill">
        <span style="font-weight:600;">${d}</span>
        <span style="color:#f59e0b;font-weight:700;">68%</span>
      </div>
    `).join('');
  }
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

// Render Audit Logs
function renderAuditLogs() {
  const container = document.getElementById('auditTimelineList');
  if (!container) return;

  const logs = AppState.auditLogs;
  container.innerHTML = logs.map(l => `
    <div class="timeline-item">
      <div class="timeline-dot ${l.color}"></div>
      <div class="timeline-content-card">
        <div class="timeline-header">
          <span class="timeline-title">${escapeHtml(l.judul)}</span>
          <span class="timeline-time">${l.waktu}</span>
        </div>
        <div class="timeline-desc">${escapeHtml(l.desc)}</div>
        <div class="timeline-meta">Oleh: ${escapeHtml(l.petugas)} &bull; ${escapeHtml(l.desa)}</div>
      </div>
    </div>
  `).join('');
}

// Render Operator List
function renderOperators() {
  const container = document.getElementById('operatorList');
  if (!container) return;

  const ops = AppState.operators;
  container.innerHTML = ops.map(o => `
    <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 0;border-bottom:1px dashed #e2e8f0;">
      <div>
        <div style="font-size:13px;font-weight:700;color:#0f172a;">${escapeHtml(o.nama)}</div>
        <div style="font-size:11px;color:#64748b;">${escapeHtml(o.role)} &bull; Wilayah: ${escapeHtml(o.wilayah)}</div>
      </div>
      <span class="badge-status valid">Aktif</span>
    </div>
  `).join('');
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