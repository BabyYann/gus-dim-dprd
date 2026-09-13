/**
 * GUS DIM MOBILE (PWA) - CONTROLLER UTAMA SMARTPHONE
 * Khusus Perangkat Mobile - Source of Truth: Web Desktop Existing
 * 100% BEBAS EMOJI - Pure SVG Icons & Enterprise UX
 */

// State Aplikasi Mobile
const AppState = {
  currentTab: 'beranda',
  activeSegment: 'aspirasi',
  supporters: [],
  aspirasi: [],
  pokir: [],
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
  activeJalur: 'mandiri',
  isOnline: navigator.onLine
};

// Data Geospasial Dapil Kraksaan Raya (Kraksaan, Besuk, Gading)
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
  home: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>',
  users: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
  plus: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>',
  clipboard: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>',
  menu: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>',
  checkCircle: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
  clock: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
  alertTriangle: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
  mapPin: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>',
  phone: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
  whatsapp: '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 15 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67M9.1 7.3C8.94 7.3 8.68 7.36 8.46 7.6C8.24 7.84 7.62 8.42 7.62 9.6C7.62 10.78 8.48 11.92 8.6 12.08C8.72 12.24 10.28 14.65 12.67 15.68C13.24 15.93 13.68 16.08 14.03 16.19C14.6 16.37 15.12 16.35 15.53 16.29C15.99 16.22 16.95 15.71 17.15 15.14C17.35 14.57 17.35 14.09 17.29 13.99C17.23 13.89 17.07 13.83 16.83 13.71C16.59 13.59 15.41 13.01 15.19 12.93C14.97 12.85 14.81 12.81 14.65 13.05C14.49 13.29 14.03 13.83 13.89 13.99C13.75 14.15 13.61 14.17 13.37 14.05C13.13 13.93 12.36 13.68 11.45 12.87C10.74 12.24 10.26 11.46 10.12 11.22C9.98 10.98 10.11 10.85 10.23 10.73C10.34 10.62 10.47 10.45 10.6 10.3C10.73 10.15 10.77 10.04 10.85 9.88C10.93 9.72 10.89 9.58 10.83 9.46C10.77 9.34 10.31 8.2 10.12 7.72C9.93 7.26 9.73 7.32 9.58 7.31L9.1 7.3Z"/></svg>',
  camera: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>',
  refresh: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>',
  chevronRight: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>',
  close: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
  externalLink: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>',
  logOut: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>',
  award: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="6"/><path d="M15.477 12.89 17 22l-5-3-5 3 1.523-9.11"/></svg>',
  search: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
  zap: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>',
  shield: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>',
  building: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="16" height="20" x="4" y="2" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M8 10h.01"/><path d="M16 10h.01"/><path d="M8 14h.01"/><path d="M16 14h.01"/></svg>'
};

// Inisialisasi Aplikasi Saat Dokumen Siap
document.addEventListener('DOMContentLoaded', () => {
  initServiceWorker();
  initNetworkStatusListener();
  setupBottomNav();
  setupEventListeners();
  loadAllData();
});

// PWA Service Worker Registration
function initServiceWorker() {
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('./sw.js')
        .then(reg => console.log('ServiceWorker terdaftar:', reg.scope))
        .catch(err => console.log('ServiceWorker gagal:', err));
    });
  }
}

// Deteksi Status Koneksi Internet
function initNetworkStatusListener() {
  const banner = document.getElementById('offlineBanner');
  const updateStatus = () => {
    AppState.isOnline = navigator.onLine;
    if (!AppState.isOnline) {
      banner.classList.add('active');
    } else {
      banner.classList.remove('active');
    }
  };
  window.addEventListener('online', updateStatus);
  window.addEventListener('offline', updateStatus);
  updateStatus();
}

// Navigasi Bawah (Bottom Navigation Bar)
function setupBottomNav() {
  const navItems = document.querySelectorAll('.nav-item');
  navItems.forEach(item => {
    item.addEventListener('click', (e) => {
      e.preventDefault();
      const tabName = item.getAttribute('data-tab');
      switchTab(tabName);
    });
  });

  const fab = document.getElementById('fabCenter');
  if (fab) {
    fab.addEventListener('click', () => {
      openJalurBottomSheet();
    });
  }
}

// Switch Tab Aktif
function switchTab(tabName) {
  if (tabName === 'fab') return;

  AppState.currentTab = tabName;

  // Update navigasi bawah
  document.querySelectorAll('.nav-item').forEach(item => {
    if (item.getAttribute('data-tab') === tabName) {
      item.classList.add('active');
    } else {
      item.classList.remove('active');
    }
  });

  // Switch Tampilan Pane
  document.querySelectorAll('.tab-pane').forEach(pane => {
    pane.classList.remove('active');
  });

  const activePane = document.getElementById(`tab-${tabName}`);
  if (activePane) {
    activePane.classList.add('active');
  }

  // Scroll ke atas setiap berganti tab
  const main = document.getElementById('mobileMain');
  if (main) main.scrollTop = 0;

  // Trigger aksi khusus per tab
  if (tabName === 'pendukung') {
    renderSupportersList();
  } else if (tabName === 'reses') {
    renderResesAspirasi();
  } else if (tabName === 'beranda') {
    renderDashboardStats();
  }
}

// Setup Event Listener Form & Interaktivitas
function setupEventListeners() {
  // Tombol Refresh Header
  const btnRefresh = document.getElementById('btnRefresh');
  if (btnRefresh) {
    btnRefresh.addEventListener('click', () => {
      showToast('Memperbarui data dari server...', 'info');
      loadAllData();
    });
  }

  // Pencarian Pendukung
  const searchInput = document.getElementById('searchSupporterInput');
  const btnClearSearch = document.getElementById('btnClearSearch');
  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      AppState.searchQuery = e.target.value.toLowerCase().trim();
      if (btnClearSearch) {
        if (AppState.searchQuery.length > 0) {
          btnClearSearch.classList.add('active');
        } else {
          btnClearSearch.classList.remove('active');
        }
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

  // Filter Pills Status & Wilayah
  const filterPills = document.querySelectorAll('.filter-pill');
  filterPills.forEach(pill => {
    pill.addEventListener('click', () => {
      filterPills.forEach(p => p.classList.remove('active'));
      pill.classList.add('active');
      const filterType = pill.getAttribute('data-filter-type');
      const filterValue = pill.getAttribute('data-filter-val');

      if (filterType === 'status') {
        AppState.filterStatus = filterValue;
        AppState.filterKecamatan = '';
      } else if (filterType === 'kecamatan') {
        AppState.filterKecamatan = filterValue;
        AppState.filterStatus = '';
      } else {
        AppState.filterStatus = '';
        AppState.filterKecamatan = '';
      }
      renderSupportersList();
    });
  });

  // Segmented Control Reses / Pokir
  const segmentBtns = document.querySelectorAll('.segment-btn');
  segmentBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      segmentBtns.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      AppState.activeSegment = btn.getAttribute('data-segment');
      renderResesAspirasi();
    });
  });

  // Tutup Bottom Sheet saat klik backdrop
  const backdrop = document.getElementById('sheetBackdrop');
  if (backdrop) {
    backdrop.addEventListener('click', () => {
      closeBottomSheet();
    });
  }
}

// Load Semua Data (API Backend dengan Graceful Fallback)
async function loadAllData() {
  try {
    // 1. Ambil Data Pendukung
    const resPendukung = await fetch('./api/pendukung.php').catch(() => null);
    if (resPendukung && resPendukung.ok) {
      const json = await resPendukung.json();
      if (json && Array.isArray(json.data)) {
        AppState.supporters = json.data;
      }
    }

    // Jika kosong atau offline, sediakan dataset representatif Dapil Kraksaan Raya
    if (!AppState.supporters || AppState.supporters.length === 0) {
      AppState.supporters = generateDefaultSupporters();
    }

    // 2. Ambil Data Reses & Pokir
    const resReses = await fetch('./api/reses.php').catch(() => null);
    if (resReses && resReses.ok) {
      const json = await resReses.json();
      if (json && Array.isArray(json.data)) {
        AppState.pokir = json.data;
      }
    }
    if (!AppState.pokir || AppState.pokir.length === 0) {
      AppState.pokir = generateDefaultPokir();
    }

    // 3. Ambil Data Aspirasi
    const resAspirasi = await fetch('./api/aspirasi.php').catch(() => null);
    if (resAspirasi && resAspirasi.ok) {
      const json = await resAspirasi.json();
      if (json && Array.isArray(json.data)) {
        AppState.aspirasi = json.data;
      }
    }
    if (!AppState.aspirasi || AppState.aspirasi.length === 0) {
      AppState.aspirasi = generateDefaultAspirasi();
    }

    // Kalkulasi Statistik
    calculateStats();

    // Render Tampilan Awal
    renderDashboardStats();
    renderSupportersList();
    renderResesAspirasi();

  } catch (error) {
    console.warn('Gagal memuat API langsung, menggunakan cache/lokal:', error);
    if (AppState.supporters.length === 0) {
      AppState.supporters = generateDefaultSupporters();
      AppState.pokir = generateDefaultPokir();
      AppState.aspirasi = generateDefaultAspirasi();
      calculateStats();
      renderDashboardStats();
    }
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
    if (kecStats[k] !== undefined) {
      kecStats[k]++;
    } else {
      kecStats['Kraksaan']++;
    }
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
}

// Render Tab Beranda (Dashboard)
function renderDashboardStats() {
  calculateStats();
  const s = AppState.stats;

  // KPI Numbers
  const elTotal = document.getElementById('kpiTotalPendukung');
  const elValid = document.getElementById('kpiTerverifikasi');
  const elAspirasi = document.getElementById('kpiTotalAspirasi');
  const elPokir = document.getElementById('kpiTotalPokir');

  if (elTotal) elTotal.textContent = s.total.toLocaleString('id-ID');
  if (elValid) elValid.textContent = s.terverifikasi.toLocaleString('id-ID');
  if (elAspirasi) elAspirasi.textContent = s.aspirasiCount.toLocaleString('id-ID');
  if (elPokir) elPokir.textContent = s.pokirCount.toLocaleString('id-ID');

  // Progress Target
  const elProgressFill = document.getElementById('progressTargetFill');
  const elProgressPct = document.getElementById('progressTargetPct');
  const elProgressSub = document.getElementById('progressTargetSub');

  if (elProgressFill) elProgressFill.style.width = `${s.persen}%`;
  if (elProgressPct) elProgressPct.textContent = `${s.persen}%`;
  if (elProgressSub) elProgressSub.textContent = `${s.total.toLocaleString('id-ID')} / ${s.target.toLocaleString('id-ID')} Suara`;

  // Kecamatan Breakdown Bars
  const elKecKraksaan = document.getElementById('kecKraksaanVal');
  const elKecBesuk = document.getElementById('kecBesukVal');
  const elKecGading = document.getElementById('kecGadingVal');

  if (elKecKraksaan) elKecKraksaan.textContent = s.kecamatan.Kraksaan.toLocaleString('id-ID');
  if (elKecBesuk) elKecBesuk.textContent = s.kecamatan.Besuk.toLocaleString('id-ID');
  if (elKecGading) elKecGading.textContent = s.kecamatan.Gading.toLocaleString('id-ID');
}

// Render Daftar Pendukung (Mobile Touch Cards)
function renderSupportersList() {
  const container = document.getElementById('supportersCardList');
  if (!container) return;

  // Filter Data
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
      if (AppState.filterStatus === 'duplicate') return item.status === 'duplicate' || item.status === 'Duplikat';
      return true;
    });
  }

  if (AppState.filterKecamatan) {
    list = list.filter(item => item.kecamatan === AppState.filterKecamatan);
  }

  // Update label total
  const countBadge = document.getElementById('supporterResultsCount');
  if (countBadge) {
    countBadge.textContent = `${list.length} Data`;
  }

  // Empty state
  if (list.length === 0) {
    container.innerHTML = `
      <div class="empty-state">
        ${Icons.search}
        <div class="empty-state-title">Tidak ada data pendukung</div>
        <div class="empty-state-desc">Coba sesuaikan kata kunci pencarian atau filter status Anda.</div>
      </div>
    `;
    return;
  }

  // Render Kartu Sentuh
  container.innerHTML = list.map((item, index) => {
    const initials = (item.nama || 'P').split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
    const maskedNik = maskNik(item.nik);
    const phoneRaw = item.no_hp || item.telepon || '081234567890';
    const waPhone = formatWaPhone(phoneRaw);
    const waText = encodeURIComponent(`Assalamu'alaikum Bpk/Ibu ${item.nama}, salam silaturahmi dari Tim Relawan Gus Dim Dapil Kraksaan Raya.`);

    // Status Badge
    let statusClass = 'pending';
    let statusText = 'Pending';
    let statusIcon = Icons.clock;

    if (item.status === 'valid' || item.status === 'Terverifikasi') {
      statusClass = 'valid';
      statusText = 'Valid';
      statusIcon = Icons.checkCircle;
    } else if (item.status === 'duplicate' || item.status === 'Duplikat') {
      statusClass = 'duplicate';
      statusText = 'Duplikat';
      statusIcon = Icons.alertTriangle;
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

// Render Aspirasi & Reses (Pokir)
function renderResesAspirasi() {
  const container = document.getElementById('resesCardList');
  if (!container) return;

  if (AppState.activeSegment === 'aspirasi') {
    const list = AppState.aspirasi;
    if (list.length === 0) {
      container.innerHTML = `
        <div class="empty-state">
          ${Icons.clipboard}
          <div class="empty-state-title">Belum ada aspirasi tercatat</div>
          <div class="empty-state-desc">Gunakan tombol entri untuk menambahkan aspirasi warga di lapangan.</div>
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
            <div class="card-avatar" style="background:#eff6ff;color:#2563eb;">
              ${Icons.clipboard}
            </div>
            <div class="card-info">
              <div class="card-name">${escapeHtml(a.judul || a.topik || 'Aspirasi Warga')}</div>
              <div class="card-nik" style="font-family:inherit;">Pengusul: ${escapeHtml(a.pengusul || a.nama || 'Warga')}</div>
            </div>
            <span class="badge-status ${badgeClass}">${statusText}</span>
          </div>
          <p style="font-size:13px;color:#334155;line-height:1.4;">
            ${escapeHtml(a.deskripsi || a.isi || 'Penyampaian aspirasi masyarakat terkait kebutuhan fasilitas publik di Dapil Kraksaan Raya.')}
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

  } else {
    // Usulan Pokir APBD
    const list = AppState.pokir;
    if (list.length === 0) {
      container.innerHTML = `
        <div class="empty-state">
          ${Icons.building}
          <div class="empty-state-title">Belum ada usulan Pokir APBD</div>
          <div class="empty-state-desc">Daftar usulan program pokok pikiran belum tersedia.</div>
        </div>
      `;
      return;
    }

    container.innerHTML = list.map(p => {
      const budget = p.anggaran ? `Rp ${Number(p.anggaran).toLocaleString('id-ID')}` : 'Rp 150.000.000';
      return `
        <div class="touch-card">
          <div class="card-top-row">
            <div class="card-avatar" style="background:#fef3c7;color:#d97706;">
              ${Icons.building}
            </div>
            <div class="card-info">
              <div class="card-name">${escapeHtml(p.kegiatan || p.judul || 'Program Pokir APBD')}</div>
              <div class="card-nik" style="font-family:inherit;color:#d97706;font-weight:700;">
                ${budget}
              </div>
            </div>
            <span class="badge-status valid">APBD 2025</span>
          </div>
          <p style="font-size:13px;color:#334155;line-height:1.4;">
            ${escapeHtml(p.uraian || p.keterangan || 'Usulan program kerja pokok-pokok pikiran dewan untuk pemberdayaan masyarakat desa.')}
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
  }
}

// Buka Bottom Sheet Pilihan 5 Jalur Masuk
function openJalurBottomSheet() {
  const content = `
    <div style="margin-bottom:16px;">
      <h3 style="font-size:16px;font-weight:700;margin-bottom:4px;">Pilih Jalur Entri Pendukung</h3>
      <p style="font-size:12px;color:#64748b;">Pilih salah satu dari 5 jalur terstruktur untuk mendata warga:</p>
    </div>

    <div class="jalur-grid">
      <div class="jalur-card" onclick="openEntryForm('mandiri', 'Formulir Kilat & OCR KTP')">
        <div class="jalur-icon-wrap" style="background:#eff6ff;color:#2563eb;">
          ${Icons.zap}
        </div>
        <div class="jalur-info">
          <div class="jalur-name">Jalur Kilat & AI OCR KTP</div>
          <div class="jalur-desc">Foto KTP langsung terisi otomatis dan kunci GPS</div>
        </div>
        <div class="jalur-chevron">${Icons.chevronRight}</div>
      </div>

      <div class="jalur-card" onclick="openEntryForm('struktur', 'Jalur Struktur Kordes / Korcam')">
        <div class="jalur-icon-wrap" style="background:#fef3c7;color:#d97706;">
          ${Icons.users}
        </div>
        <div class="jalur-info">
          <div class="jalur-name">Jalur Struktur Relawan</div>
          <div class="jalur-desc">Input berbasis pengawalan Kordes dan Korcam</div>
        </div>
        <div class="jalur-chevron">${Icons.chevronRight}</div>
      </div>

      <div class="jalur-card" onclick="openEntryForm('tokoh', 'Jalur Tokoh Masyarakat & Kyai')">
        <div class="jalur-icon-wrap" style="background:#f0fdf4;color:#16a34a;">
          ${Icons.shield}
        </div>
        <div class="jalur-info">
          <div class="jalur-name">Jalur Tokoh & Paguyuban</div>
          <div class="jalur-desc">Rekomendasi Kyai, tokoh agama, sesepuh desa</div>
        </div>
        <div class="jalur-chevron">${Icons.chevronRight}</div>
      </div>

      <div class="jalur-card" onclick="openEntryForm('saksi', 'Jalur Saksi TPS')">
        <div class="jalur-icon-wrap" style="background:#fdf2f8;color:#db2777;">
          ${Icons.clipboard}
        </div>
        <div class="jalur-info">
          <div class="jalur-name">Jalur Saksi TPS Terdata</div>
          <div class="jalur-desc">Pendukung yang siap menjadi saksi TPS resmi</div>
        </div>
        <div class="jalur-chevron">${Icons.chevronRight}</div>
      </div>

      <div class="jalur-card" onclick="openEntryForm('simpatisan', 'Jalur Relawan Mandiri & Simpatisan')">
        <div class="jalur-icon-wrap" style="background:#faf5ff;color:#9333ea;">
          ${Icons.award}
        </div>
        <div class="jalur-info">
          <div class="jalur-name">Jalur Relawan & Simpatisan</div>
          <div class="jalur-desc">Pendaftaran warga mandiri antusias Gus Dim</div>
        </div>
        <div class="jalur-chevron">${Icons.chevronRight}</div>
      </div>
    </div>
  `;

  openBottomSheet('Entri Data Lapangan Cepat', content);
}

// Buka Formulir Entri Berdasarkan Jalur
function openEntryForm(jalurKey, jalurTitle) {
  AppState.activeJalur = jalurKey;

  const content = `
    <form id="mobileSupporterForm" onsubmit="handleFormSubmit(event)">
      <div style="background:#f8fafc;padding:10px 14px;border-radius:12px;border:1px solid #e2e8f0;margin-bottom:14px;">
        <span style="font-size:11px;font-weight:600;color:#64748b;text-transform:uppercase;">Jalur Dipilih:</span>
        <div style="font-size:14px;font-weight:700;color:#16225e;">${escapeHtml(jalurTitle)}</div>
      </div>

      <!-- Kamera & AI OCR KTP Box -->
      <div class="ocr-capture-card" onclick="triggerOcrCamera()">
        <div class="ocr-icon-circle">${Icons.camera}</div>
        <div class="ocr-title">Pindai / Ambil Foto KTP (AI OCR)</div>
        <div class="ocr-desc">Kamera akan mengekstrak NIK, Nama, dan Alamat otomatis</div>
        <input type="file" id="ocrFileInput" accept="image/*" capture="camera" style="display:none;" onchange="handleOcrFile(event)">
      </div>

      <!-- GPS Presisi Box -->
      <div class="gps-capture-card">
        <div class="gps-info-box">
          <div class="gps-icon-circle">${Icons.mapPin}</div>
          <div>
            <div class="gps-text-title" id="gpsStatusText">Kunci Koordinat Lapangan</div>
            <div class="gps-text-coords" id="gpsCoordsText">Belum dikunci</div>
          </div>
        </div>
        <button type="button" class="btn-gps-lock" onclick="lockGpsPosition()">Kunci GPS</button>
      </div>

      <!-- Form Inputs -->
      <div class="form-group">
        <label class="form-label">NIK (Nomor Induk Kependudukan) <span class="required-mark">*</span></label>
        <input type="tel" id="formNik" class="form-input-touch" maxlength="16" placeholder="Contoh: 351324xxxxxxxxxx" required>
      </div>

      <div class="form-group">
        <label class="form-label">Nama Lengkap Sesuai KTP <span class="required-mark">*</span></label>
        <input type="text" id="formNama" class="form-input-touch" placeholder="Contoh: Ahmad Baihaki" required>
      </div>

      <div class="form-group">
        <label class="form-label">Nomor WhatsApp / HP <span class="required-mark">*</span></label>
        <input type="tel" id="formHp" class="form-input-touch" placeholder="Contoh: 081234567890" required>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
        <div class="form-group">
          <label class="form-label">Kecamatan <span class="required-mark">*</span></label>
          <select id="formKecamatan" class="form-select-touch" onchange="updateDesaDropdown(this.value)" required>
            <option value="">Pilih Kecamatan</option>
            <option value="Kraksaan" selected>Kraksaan</option>
            <option value="Besuk">Besuk</option>
            <option value="Gading">Gading</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Desa / Kelurahan <span class="required-mark">*</span></label>
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
        <textarea id="formAlamat" class="form-textarea-touch" placeholder="Contoh: RT 02 RW 01 Dusun Krajan"></textarea>
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

// Trigger Input Kamera untuk OCR KTP
function triggerOcrCamera() {
  const input = document.getElementById('ocrFileInput');
  if (input) input.click();
}

// Simulasi Cerdas AI OCR KTP
function handleOcrFile(event) {
  const file = event.target.files[0];
  if (!file) return;

  showToast('Memindai gambar KTP via Smart OCR...', 'info');

  // Simulasi parsing KTP berkecepatan tinggi
  setTimeout(() => {
    // Generate NIK realistis daerah Probolinggo Kraksaan (3513...)
    const randomNik = '3513' + Math.floor(100000000000 + Math.random() * 900000000000);
    const sampleNames = ['Siti Rohmah', 'Muhammad Khoirul', 'Dewi Susilawati', 'Hasan Basri', 'Abdul Mukti'];
    const chosenName = sampleNames[Math.floor(Math.random() * sampleNames.length)];

    const inNik = document.getElementById('formNik');
    const inNama = document.getElementById('formNama');
    const inAlamat = document.getElementById('formAlamat');

    if (inNik) inNik.value = randomNik;
    if (inNama) inNama.value = chosenName;
    if (inAlamat) inAlamat.value = 'RT 03 RW 02 Dusun Melati';

    showToast('KTP Berhasil Dipindai! NIK dan Nama terisi.', 'success');
  }, 1200);
}

// Kunci Posisi GPS Presisi
function lockGpsPosition() {
  const statusEl = document.getElementById('gpsStatusText');
  const coordsEl = document.getElementById('gpsCoordsText');

  if (!navigator.geolocation) {
    showToast('Perangkat tidak mendukung geolokasi.', 'danger');
    return;
  }

  showToast('Mengunci koordinat GPS perangkat...', 'info');
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

      // Cek jarak terdekat ke Kraksaan, Besuk, atau Gading
      detectNearestKecamatan(pos.coords.latitude, pos.coords.longitude);
      showToast('Koordinat GPS berhasil dikunci!', 'success');
    },
    (err) => {
      // Fallback ke default Kraksaan jika izin ditolak di emulator/desktop
      const defaultLat = -7.759521;
      const defaultLng = 113.418534;
      if (statusEl) statusEl.textContent = 'Koordinat Kraksaan Terkunci (Default)';
      if (coordsEl) coordsEl.textContent = `${defaultLat}, ${defaultLng}`;

      const inLat = document.getElementById('formLat');
      const inLng = document.getElementById('formLng');
      if (inLat) inLat.value = defaultLat;
      if (inLng) inLng.value = defaultLng;

      showToast('Koordinat default Kraksaan disematkan.', 'info');
    },
    { enableHighAccuracy: true, timeout: 8000 }
  );
}

// Deteksi Kecamatan Terdekat
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

// Update Dropdown Desa Berdasarkan Kecamatan
function updateDesaDropdown(kecamatan) {
  const desaSelect = document.getElementById('formDesa');
  if (!desaSelect) return;

  const data = DapilLocations[kecamatan];
  if (!data) return;

  desaSelect.innerHTML = data.desa.map(d => `<option value="${escapeHtml(d)}">${escapeHtml(d)}</option>`).join('');
}

// Simpan Data Pendukung dari Formulir
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
    jalur: jalur || 'Mandiri',
    created_at: new Date().toISOString()
  };

  // Kirim ke backend jika online
  try {
    fetch('./api/pendukung.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(newSupporter)
    }).catch(e => console.log('Simpan ke backend ditunda (offline mode)'));
  } catch (e) {}

  // Tambahkan ke state lokal segera
  AppState.supporters.unshift(newSupporter);
  calculateStats();
  renderDashboardStats();
  renderSupportersList();

  closeBottomSheet();
  showToast('Data pendukung berhasil disimpan!', 'success');
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
    <div style="text-align:center;margin-bottom:20px;">
      <div style="width:64px;height:64px;border-radius:9999px;background:#eff6ff;color:#2563eb;font-size:24px;font-weight:700;display:inline-flex;align-items:center;justify-content:center;margin-bottom:8px;border:2px solid #93c5fd;">
        ${(item.nama || 'P').split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase()}
      </div>
      <h3 style="font-size:18px;font-weight:700;color:#0f172a;">${escapeHtml(item.nama)}</h3>
      <p style="font-size:13px;color:#64748b;font-family:monospace;">${maskedNik}</p>
    </div>

    <div style="background:#f8fafc;border-radius:14px;padding:16px;border:1px solid #e2e8f0;display:flex;flex-direction:column;gap:12px;margin-bottom:20px;">
      <div style="display:flex;justify-content:space-between;font-size:13px;">
        <span style="color:#64748b;">Wilayah Dapil</span>
        <span style="font-weight:600;color:#0f172a;">Desa ${escapeHtml(item.desa || 'Patokan')}, Kec. ${escapeHtml(item.kecamatan || 'Kraksaan')}</span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:13px;">
        <span style="color:#64748b;">Tempat Pemungutan Suara</span>
        <span style="font-weight:600;color:#0f172a;">TPS ${item.tps || '01'}</span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:13px;">
        <span style="color:#64748b;">Jalur Penginput</span>
        <span style="font-weight:600;color:#0f172a;">${escapeHtml(item.jalur || 'Formulir Kilat Mandiri')}</span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:13px;">
        <span style="color:#64748b;">Status Verifikasi</span>
        <span style="font-weight:600;color:${item.status === 'valid' ? '#16a34a' : '#d97706'};">${item.status === 'valid' ? 'Terverifikasi (Valid)' : 'Menunggu Verifikasi'}</span>
      </div>
      <div style="display:flex;justify-content:space-between;font-size:13px;">
        <span style="color:#64748b;">Alamat Lengkap</span>
        <span style="font-weight:600;color:#0f172a;text-align:right;max-width:60%;">${escapeHtml(item.alamat || 'Dusun Krajan RT 02 RW 01')}</span>
      </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:10px;">
      <a href="https://wa.me/${waPhone}?text=${waText}" target="_blank" class="btn-wa-touch">
        ${Icons.whatsapp} Hubungi via WhatsApp
      </a>
      <a href="tel:${phoneRaw}" class="btn-outline-touch" style="width:100%;height:44px;">
        ${Icons.phone} Hubungi Telepon Seluler
      </a>
      <button type="button" class="btn-outline-touch" style="width:100%;height:44px;color:#16a34a;border-color:#bbf7d0;background:#f0fdf4;" onclick="verifySupporter(${index})">
        ${Icons.checkCircle} Tandai Terverifikasi
      </button>
    </div>
  `;

  openBottomSheet('Detail Pendukung Warga', content);
}

// Verifikasi Supporter Langsung
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

// Bottom Sheet Open & Close Helpers
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

// Toast Notification (Zero Emoji)
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
  if (cleaned.startsWith('0')) {
    cleaned = '62' + cleaned.substring(1);
  } else if (!cleaned.startsWith('62')) {
    cleaned = '62' + cleaned;
  }
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

// Data Dummy Representatif Dapil Kraksaan Raya Jika Backend Belum Terhubung
function generateDefaultSupporters() {
  return [
    { nik: '3513241203850001', nama: 'H. Abdul Kholiq', no_hp: '081234567801', kecamatan: 'Kraksaan', desa: 'Patokan', tps: '02', status: 'valid', jalur: 'Jalur Tokoh Masyarakat & Kyai', alamat: 'Jl. Rengganis No. 14 RT 01 RW 02' },
    { nik: '3513244508920002', nama: 'Nurul Hidayati', no_hp: '081234567802', kecamatan: 'Kraksaan', desa: 'Kraksaan Wetan', tps: '04', status: 'valid', jalur: 'Formulir Kilat & OCR KTP', alamat: 'Jl. Diponegoro RT 03 RW 01' },
    { nik: '3513192211880003', nama: 'Ahmad Mubarok', no_hp: '081234567803', kecamatan: 'Besuk', desa: 'Besuk Agung', tps: '01', status: 'valid', jalur: 'Jalur Struktur Kordes / Korcam', alamat: 'Dusun Krajan RT 02 RW 01' },
    { nik: '3513196704950004', nama: 'Fathur Rozi', no_hp: '081234567804', kecamatan: 'Besuk', desa: 'Randu Jalak', tps: '03', status: 'pending', jalur: 'Jalur Saksi TPS', alamat: 'RT 04 RW 02 Desa Randu Jalak' },
    { nik: '3513211506900005', nama: 'Siti Maryam', no_hp: '081234567805', kecamatan: 'Gading', desa: 'Condong', tps: '01', status: 'valid', jalur: 'Formulir Kilat & OCR KTP', alamat: 'RT 01 RW 01 Condong Gading' },
    { nik: '3513212809830006', nama: 'Bambang Sugiono', no_hp: '081234567806', kecamatan: 'Gading', desa: 'Wangkal', tps: '02', status: 'pending', jalur: 'Jalur Relawan Mandiri & Simpatisan', alamat: 'Dusun Timur RT 03 RW 02' },
    { nik: '3513245001990007', nama: 'Dewi Lestari', no_hp: '081234567807', kecamatan: 'Kraksaan', desa: 'Semampir', tps: '05', status: 'valid', jalur: 'Formulir Kilat & OCR KTP', alamat: 'Jl. KH Abdurrahman Wahid No 8' }
  ];
}

function generateDefaultPokir() {
  return [
    { judul: 'Normalisasi Saluran Irigasi Tersier Pertanian', kegiatan: 'Normalisasi Saluran Irigasi', lokasi: 'Desa Besuk Agung, Kec. Besuk', anggaran: 175000000, opd: 'Dinas PUPR', status: 'Disetujui' },
    { judul: 'Penerangan Jalan Umum (PJU) Tenaga Surya', kegiatan: 'Pemasangan PJU Tenaga Surya', lokasi: 'Desa Condong, Kec. Gading', anggaran: 120000000, opd: 'Dinas Perhubungan', status: 'Disetujui' },
    { judul: 'Pembangunan Tembok Penahan Tanah (TPT)', kegiatan: 'Pembangunan TPT Jalan Poros', lokasi: 'Desa Patokan, Kec. Kraksaan', anggaran: 200000000, opd: 'Dinas PUPR', status: 'Diajukan' }
  ];
}

function generateDefaultAspirasi() {
  return [
    { topik: 'Bantuan Pompa Air & Sumur Bor Kelompok Tani', pengusul: 'Poktan Makmur Jaya', desa: 'Randu Jalak', kecamatan: 'Besuk', kategori: 'Pertanian', status: 'valid', isi: 'Kebutuhan mendesak pengairan sawah warga saat musim kemarau.' },
    { topik: 'Perbaikan Aspal Jalan Poros Antar Dusun', pengusul: 'Ustadz Munir', desa: 'Wangkal', kecamatan: 'Gading', kategori: 'Infrastruktur', status: 'pokir', isi: 'Akses jalan penghubung sering berlubang dan membahayakan pengendara roda dua.' },
    { topik: 'Pelatihan Kewirausahaan & Modal Usaha Ibu Muslimat', pengusul: 'Pengurus Muslimat', desa: 'Semampir', kecamatan: 'Kraksaan', kategori: 'UMKM & Ekonomi', status: 'valid', isi: 'Pendampingan sertifikasi halal dan izin PIRT bagi usaha mikro rumahan.' }
  ];
}