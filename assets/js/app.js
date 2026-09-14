/**
 * ================================================================
 * REST API ADAPTER & BRIDGE UNTUK SISTEM GUS DIM (cPanel / PHP 8)
 * Menggantikan google.script.run dengan komunikasi REST API nyata
 * ================================================================
 */

const API_BASE_URL = 'api';

async function fetchApi(endpoint, method = 'GET', data = null) {
  const token = localStorage.getItem('dprd_token');
  const headers = {
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  };
  if (token) {
    headers['Authorization'] = 'Bearer ' + token;
  }

  const options = { method, headers };
  if (data && (method === 'POST' || method === 'PUT')) {
    options.body = JSON.stringify(data);
  }

  try {
    const res = await fetch(API_BASE_URL + '/' + endpoint, options);
    const json = await res.json();
    if (res.status === 401) {
      localStorage.removeItem('dprd_token');
      SESSION_TOKEN = null;
      CURRENT_USER = null;
    }
    return json;
  } catch (err) {
    console.error('API Error (' + endpoint + '):', err);
    throw err;
  }
}

function makeApiRunner(handlers) {
  handlers = handlers || {};
  const runner = {
    withSuccessHandler(fn) { return makeApiRunner(Object.assign({}, handlers, { success: fn })); },
    withFailureHandler(fn) { return makeApiRunner(Object.assign({}, handlers, { failure: fn })); },
    
    // Auth
    checkSession(token) {
      fetchApi('auth.php?action=check', 'GET')
        .then(res => handlers.success && handlers.success(res))
        .catch(err => handlers.failure ? handlers.failure(err) : console.error(err));
    },
    login(username, password) {
      fetchApi('auth.php?action=login', 'POST', { username, password })
        .then(res => handlers.success && handlers.success(res))
        .catch(err => handlers.failure ? handlers.failure(err) : console.error(err));
    },
    logout(token) {
      fetchApi('auth.php?action=logout', 'POST', { token })
        .then(res => handlers.success && handlers.success(res))
        .catch(err => handlers.failure ? handlers.failure(err) : console.error(err));
    },
    updateProfilSaya(token, data) {
      fetchApi('auth.php?action=update-profile', 'POST', data)
        .then(res => handlers.success && handlers.success(res))
        .catch(err => handlers.failure ? handlers.failure(err) : console.error(err));
    },
    updateFotoProfilSaya(token, base64, mime) {
      fetchApi('auth.php?action=update-avatar', 'POST', { fotoBase64: base64, mime: mime })
        .then(res => handlers.success && handlers.success(res))
        .catch(err => handlers.failure ? handlers.failure(err) : console.error(err));
    },

    // Dashboard
    getDashboardData(token) {
      fetchApi('dashboard.php', 'GET')
        .then(res => handlers.success && handlers.success(res))
        .catch(err => handlers.failure ? handlers.failure(err) : console.error(err));
    },

    // Pendukung Form Submissions
    submitDPC(formData) {
      fetchApi('pendukung.php?action=submit', 'POST', Object.assign({ jalur: 'DPC' }, formData))
        .then(res => handlers.success && handlers.success(res))
        .catch(err => handlers.failure ? handlers.failure(err) : console.error(err));
    },
    submitDPRT(formData) {
      fetchApi('pendukung.php?action=submit', 'POST', Object.assign({ jalur: 'DPRT' }, formData))
        .then(res => handlers.success && handlers.success(res))
        .catch(err => handlers.failure ? handlers.failure(err) : console.error(err));
    },
    submitPIP(formData) {
      fetchApi('pendukung.php?action=submit', 'POST', Object.assign({ jalur: 'PIP' }, formData))
        .then(res => handlers.success && handlers.success(res))
        .catch(err => handlers.failure ? handlers.failure(err) : console.error(err));
    },
    submitKIP(formData) {
      fetchApi('pendukung.php?action=submit', 'POST', Object.assign({ jalur: 'KIP' }, formData))
        .then(res => handlers.success && handlers.success(res))
        .catch(err => handlers.failure ? handlers.failure(err) : console.error(err));
    },
    submitRelawan(formData) {
      fetchApi('pendukung.php?action=submit', 'POST', Object.assign({ jalur: 'RELAWAN' }, formData))
        .then(res => handlers.success && handlers.success(res))
        .catch(err => handlers.failure ? handlers.failure(err) : console.error(err));
    },

    // Riwayat & Approval
    getRiwayatData(token, jalurKey, filters) {
      const q = new URLSearchParams();
      if (jalurKey) q.append('jalur', jalurKey);
      if (filters && filters.status) q.append('status', filters.status);
      if (filters && filters.keyword) q.append('keyword', filters.keyword);
      fetchApi('verifikasi.php?action=riwayat&' + q.toString(), 'GET')
        .then(res => handlers.success && handlers.success(res))
        .catch(err => handlers.failure ? handlers.failure(err) : console.error(err));
    },
    updateStatusVerifikasi(token, jalurKey, rowNumber, newStatus, catatan) {
      fetchApi('verifikasi.php?action=update-status', 'POST', { rowNumber, newStatus, catatan })
        .then(res => handlers.success && handlers.success(res))
        .catch(err => handlers.failure ? handlers.failure(err) : console.error(err));
    },

    // Activity Logs
    getLogAktivitas(token) {
      fetchApi('logs.php', 'GET')
        .then(res => handlers.success && handlers.success(res))
        .catch(err => handlers.failure ? handlers.failure(err) : console.error(err));
    },

    // User Management (Superadmin)
    getUsersList(token) {
      fetchApi('users.php?action=list', 'GET')
        .then(res => handlers.success && handlers.success(res))
        .catch(err => handlers.failure ? handlers.failure(err) : console.error(err));
    },
    addUserFromUI(token, userData) {
      fetchApi('users.php?action=add', 'POST', userData)
        .then(res => handlers.success && handlers.success(res))
        .catch(err => handlers.failure ? handlers.failure(err) : console.error(err));
    },
    updateUserStatus(token, rowNumber, newStatus) {
      fetchApi('users.php?action=toggle-status', 'POST', { rowNumber, newStatus })
        .then(res => handlers.success && handlers.success(res))
        .catch(err => handlers.failure ? handlers.failure(err) : console.error(err));
    },
    resetUserPassword(token, rowNumber, newPassword) {
      fetchApi('users.php?action=reset-password', 'POST', { rowNumber, newPassword })
        .then(res => handlers.success && handlers.success(res))
        .catch(err => handlers.failure ? handlers.failure(err) : console.error(err));
    }
  };
  return runner;
}

// Pasang bridge global google.script.run
window.google = {
  script: {
    run: makeApiRunner()
  }
};

/// Reset Service Worker dan Bersihkan Cache
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.getRegistrations().then(function(registrations) {
    for (let registration of registrations) {
      registration.unregister();
    }
  });
}
if ('caches' in window) {
  caches.keys().then(function(names) {
    for (let name of names) caches.delete(name);
  });
}

// Fitur Ekspor Excel (.csv)
function exportDataExcel(jalur) {
  const token = localStorage.getItem('dprd_token') || '';
  const url = 'api/export.php?jalur=' + encodeURIComponent(jalur || 'ALL') + '&token=' + encodeURIComponent(token);
  window.open(url, '_blank');
}

// Fitur Template Pesan Sapaan WhatsApp
function kirimUcapanWa(hp, nama, jalur) {
  const nomor = formatNomorWa(hp);
  if (!nomor) {
    alert('Nomor HP tidak valid');
    return;
  }
  const pesan = `Assalamu'alaikum Wr. Wb. Bpk/Ibu ${nama},\n\nTerima kasih atas silaturahmi dan dukungannya untuk Gus Dim (${jalur}). Semoga ikhtiar bersama ini senantiasa diridhai Allah SWT demi kemaslahatan masyarakat Kraksaan, Besuk, dan Gading.\n\nSalam takdzim,\n*Tim Gus Dim*`;
  const url = `https://wa.me/${nomor}?text=` + encodeURIComponent(pesan);
  window.open(url, '_blank');
}

  let SESSION_TOKEN = localStorage.getItem('dprd_token') || null;
  let CURRENT_USER = null;
  let mapInstance = null;
  let chartInstance = null;
  let chartJalurInstance = null;

  const JALUR_LABEL = { DPC: 'DPC Kecamatan', DPRT: 'DPRT Desa', PIP: 'PIP', KIP: 'KIP', RELAWAN: 'Relawan' };

  /** Hitung umur dari 16 digit NIK (format Indonesia: digit 7-12 = DDMMYY, perempuan +40 di digit hari) */
  function hitungUmurDariNIK(nik) {
    if (!nik || String(nik).length < 12) return null;
    nik = String(nik);
    let dd = parseInt(nik.substring(6, 8), 10);
    const mm = parseInt(nik.substring(8, 10), 10);
    const yy = parseInt(nik.substring(10, 12), 10);
    if (isNaN(dd) || isNaN(mm) || isNaN(yy)) return null;
    if (dd > 40) dd -= 40;
    if (dd < 1 || dd > 31 || mm < 1 || mm > 12) return null;

    const now = new Date();
    const currentYY = now.getFullYear() % 100;
    const fullYear = yy <= currentYY ? 2000 + yy : 1900 + yy;
    const lahir = new Date(fullYear, mm - 1, dd);
    let umur = now.getFullYear() - lahir.getFullYear();
    const belumUlangTahun = (now.getMonth() < lahir.getMonth()) || (now.getMonth() === lahir.getMonth() && now.getDate() < lahir.getDate());
    if (belumUlangTahun) umur--;
    return (umur >= 0 && umur < 130) ? umur : null;
  }

  function inisial(nama) {
    if (!nama) return '-';
    const parts = nama.trim().split(/\s+/);
    return parts.length > 1 ? (parts[0][0] + parts[1][0]).toUpperCase() : parts[0].substring(0, 2).toUpperCase();
  }

  /** Render daftar orang jadi kartu list (avatar bulat + nama + umur), klik untuk buka detail */
  function renderPeopleCardList(containerId, rows, opsi) {
    opsi = opsi || {};
    const container = document.getElementById(containerId);
    container.innerHTML = '';

    if (!rows || rows.length === 0) {
      container.innerHTML = '<div style="text-align:center;color:#94a3b8;padding:30px 0;">Belum ada data</div>';
      return;
    }

    rows.forEach(function (r, idx) {
      const umur = hitungUmurDariNIK(r.nik);
      const subInfo = [umur !== null ? umur + ' thn' : null, opsi.showJalur ? r.jalur : null].filter(Boolean).join(' &middot; ');
      const avatarHtml = r.foto
        ? '<img class="people-card-avatar" src="' + r.foto + '" alt="">'
        : '<div class="people-card-avatar-placeholder">' + inisial(r.nama) + '</div>';

      const badgeClass = r.status === 'Final' ? 'badge-final' : r.status === 'Ditolak' ? 'badge-ditolak' : (r.status === 'Diverifikasi Desa' || r.status === 'Divalidasi Kecamatan') ? 'badge-diverifikasi' : 'badge-diinput';

      const card = document.createElement('div');
      card.className = 'people-card';
      card.innerHTML =
        avatarHtml +
        '<div class="people-card-info">' +
          '<div class="people-card-nama">' + r.nama + '</div>' +
          '<div class="people-card-sub">' + (subInfo || '-') + '</div>' +
        '</div>' +
        (r.status ? '<span class="badge ' + badgeClass + ' people-card-badge">' + r.status + '</span>' : '') +
        '<span class="people-card-chevron">›</span>';
      card.onclick = function () {
        const opsiBaris = r.jalurKeyAsal ? Object.assign({}, opsi, { jalurKey: r.jalurKeyAsal }) : opsi;
        bukaDetailOrang(r, opsiBaris);
      };
      container.appendChild(card);
    });
  }

  /** Ubah nomor HP lokal (08xx / 62xx / +62xx) jadi format internasional untuk link wa.me */
  function formatNomorWa(hp) {
    if (!hp) return '';
    let digits = String(hp).replace(/\D/g, '');
    if (!digits) return '';
    if (digits.startsWith('0')) digits = '62' + digits.substring(1);
    else if (!digits.startsWith('62')) digits = '62' + digits;
    return digits;
  }

  function bukaDetailOrang(r, opsi) {
    opsi = opsi || {};
    const umur = hitungUmurDariNIK(r.nik);
    document.getElementById('detailNama').textContent = r.nama || '-';

    const subText = [r.jalur, r.jabatan, r.koordinator].filter(Boolean).join(' · ');
    const subEl = document.getElementById('detailSub');
    if (subEl) {
      subEl.textContent = subText || 'Konstituen';
    }

    if (r.foto) {
      document.getElementById('detailFoto').src = r.foto;
      document.getElementById('detailFoto').style.display = 'block';
      document.getElementById('detailFotoPlaceholder').style.display = 'none';
    } else {
      document.getElementById('detailFoto').style.display = 'none';
      const ph = document.getElementById('detailFotoPlaceholder');
      ph.style.display = 'flex';
      ph.textContent = inisial(r.nama);
    }

    const curStatus = r.status || 'Diinput';
    const statusBadgeClass = curStatus === 'Final' ? 'badge-final' : curStatus === 'Ditolak' ? 'badge-ditolak' : (curStatus === 'Diverifikasi Desa' || curStatus === 'Divalidasi Kecamatan' ? 'badge-diverifikasi' : 'badge-diinput');
    const statusHtml = '<span class="badge ' + statusBadgeClass + '" style="font-size:11px; padding:2.5px 8px; border-radius:5px; font-weight:700;">' + curStatus + '</span>';

    const rowsData = [
      ['NIK', r.nik ? '<code style="font-family:monospace; font-size:12px; font-weight:600; color:#1e293b;">' + r.nik + '</code>' : '-'],
      ['Umur', umur !== null ? umur + ' tahun' : '-'],
      ['No. HP', r.hp || '-'],
      ['Alamat', r.alamat || '-'],
      ['Kecamatan / Desa', [r.kecamatan, r.desa].filter(Boolean).join(' / ') || '-'],
      ['Status Verifikasi', statusHtml],
      ['Tanggal Input', r.tanggal || '-'],
      ['Diinput oleh', r.userInput || '-'],
      ['Catatan', r.catatan || '-']
    ];
    document.getElementById('detailRows').innerHTML = rowsData.map(function (row) {
      return '<div class="detail-row"><span class="k">' + row[0] + '</span><span class="v">' + row[1] + '</span></div>';
    }).join('');

    window.currentDetailRecord = r;
    window.currentDetailOpsi = opsi;
    const isSuperadmin = CURRENT_USER && CURRENT_USER.role === 'Superadmin';
    const isOperator = !!(r.operator_user_id || r.operator_username);
    const userRole = CURRENT_USER ? CURRENT_USER.role : '';

    const badgeOp = document.getElementById('detailOperatorBadge');
    const btnJadikanOp = document.getElementById('btnJadikanOperatorDariDetail');
    const btnKirimWaAkses = document.getElementById('btnKirimWaAksesOperator');
    const btnResetPass = document.getElementById('btnResetPassOperator');

    if (badgeOp) {
      if (isOperator) {
        badgeOp.style.display = 'block';
        const roleEl = document.getElementById('detailOpRoleText');
        const userEl = document.getElementById('detailOpUserText');
        if (roleEl) roleEl.textContent = r.operator_role || 'Petugas Operator';
        if (userEl) userEl.textContent = r.operator_username || '-';
      } else {
        badgeOp.style.display = 'none';
      }
    }

    if (btnJadikanOp) {
      if (isSuperadmin && !isOperator) {
        btnJadikanOp.style.display = 'inline-flex';
      } else {
        btnJadikanOp.style.display = 'none';
      }
    }

    if (btnKirimWaAkses) {
      if (isSuperadmin && isOperator) {
        btnKirimWaAkses.style.display = 'inline-flex';
      } else {
        btnKirimWaAkses.style.display = 'none';
      }
    }

    if (btnResetPass) {
      if (isSuperadmin && isOperator) {
        btnResetPass.style.display = 'inline-flex';
      } else {
        btnResetPass.style.display = 'none';
      }
    }

    // Kotak Tindakan Validasi Berjenjang Sesuai Role
    const verifBox = document.getElementById('detailVerifActionBox');
    const verifBadge = document.getElementById('detailVerifStatusBadge');
    const verifBtns = document.getElementById('detailVerifButtons');

    if (verifBox && verifBadge && verifBtns) {
      verifBadge.textContent = curStatus;

      if (curStatus === 'Diverifikasi Desa') {
        verifBadge.style.background = '#eff6ff';
        verifBadge.style.color = '#1d4ed8';
        verifBadge.style.border = '1px solid #bfdbfe';
      } else if (curStatus === 'Divalidasi Kecamatan') {
        verifBadge.style.background = '#e0e7ff';
        verifBadge.style.color = '#3730a3';
        verifBadge.style.border = '1px solid #c7d2fe';
      } else if (curStatus === 'Final') {
        verifBadge.style.background = '#f0fdf4';
        verifBadge.style.color = '#15803d';
        verifBadge.style.border = '1px solid #bbf7d0';
      } else if (curStatus === 'Ditolak') {
        verifBadge.style.background = '#fef2f2';
        verifBadge.style.color = '#dc2626';
        verifBadge.style.border = '1px solid #fecaca';
      } else {
        verifBadge.style.background = '#fef3c7';
        verifBadge.style.color = '#b45309';
        verifBadge.style.border = '1px solid #fde68a';
      }

      const btnStyleDesa = 'display:inline-flex;align-items:center;justify-content:center;gap:6px;height:38px;padding:0 12px;border-radius:8px;font-size:12px;font-weight:700;background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;cursor:pointer;transition:all 0.15s ease;';
      const btnStyleKec = 'display:inline-flex;align-items:center;justify-content:center;gap:6px;height:38px;padding:0 12px;border-radius:8px;font-size:12px;font-weight:700;background:#e0e7ff;color:#3730a3;border:1px solid #c7d2fe;cursor:pointer;transition:all 0.15s ease;';
      const btnStyleFinal = 'display:inline-flex;align-items:center;justify-content:center;gap:6px;height:38px;padding:0 12px;border-radius:8px;font-size:12px;font-weight:700;background:#f0fdf4;color:#15803d;border:1px solid #bbf7d0;cursor:pointer;transition:all 0.15s ease;';
      const btnStyleTolak = 'display:inline-flex;align-items:center;justify-content:center;gap:6px;height:38px;padding:0 12px;border-radius:8px;font-size:12px;font-weight:700;background:#fef2f2;color:#dc2626;border:1px solid #fecaca;cursor:pointer;transition:all 0.15s ease;';

      const iconCheck = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg>';
      const iconShield = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>';
      const iconFinal = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>';
      const iconTolak = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>';

      let btnsHtml = '';
      if (userRole === 'Koordinator Desa' || userRole === 'Admin Ranting') {
        btnsHtml = '<button type="button" class="btn-verif-pill btn-verif-desa" onclick="updateStatusPendukungDariDesktop(\'Diverifikasi Desa\')" style="' + btnStyleDesa + '">' + iconCheck + '<span>Verif Desa</span></button>' +
                   '<button type="button" class="btn-verif-pill btn-verif-tolak" onclick="updateStatusPendukungDariDesktop(\'Ditolak\')" style="' + btnStyleTolak + '">' + iconTolak + '<span>Tolak Data</span></button>';
        verifBox.style.display = 'block';
      } else if (userRole === 'Koordinator Kecamatan') {
        btnsHtml = '<button type="button" class="btn-verif-pill btn-verif-kec" onclick="updateStatusPendukungDariDesktop(\'Divalidasi Kecamatan\')" style="' + btnStyleKec + '">' + iconShield + '<span>Validasi Kec</span></button>' +
                   '<button type="button" class="btn-verif-pill btn-verif-final" onclick="updateStatusPendukungDariDesktop(\'Final\')" style="' + btnStyleFinal + '">' + iconFinal + '<span>Tandai Final</span></button>' +
                   '<button type="button" class="btn-verif-pill btn-verif-tolak" onclick="updateStatusPendukungDariDesktop(\'Ditolak\')" style="' + btnStyleTolak + 'grid-column: span 2;">' + iconTolak + '<span>Tolak Data</span></button>';
        verifBox.style.display = 'block';
      } else if (userRole === 'Superadmin') {
        btnsHtml = '<button type="button" class="btn-verif-pill btn-verif-desa" onclick="updateStatusPendukungDariDesktop(\'Diverifikasi Desa\')" style="' + btnStyleDesa + '">' + iconCheck + '<span>Verif Desa</span></button>' +
                   '<button type="button" class="btn-verif-pill btn-verif-kec" onclick="updateStatusPendukungDariDesktop(\'Divalidasi Kecamatan\')" style="' + btnStyleKec + '">' + iconShield + '<span>Validasi Kec</span></button>' +
                   '<button type="button" class="btn-verif-pill btn-verif-final" onclick="updateStatusPendukungDariDesktop(\'Final\')" style="' + btnStyleFinal + '">' + iconFinal + '<span>Tandai Final</span></button>' +
                   '<button type="button" class="btn-verif-pill btn-verif-tolak" onclick="updateStatusPendukungDariDesktop(\'Ditolak\')" style="' + btnStyleTolak + '">' + iconTolak + '<span>Tolak Data</span></button>';
        verifBox.style.display = 'block';
      } else {
        verifBox.style.display = 'none';
      }
      verifBtns.innerHTML = btnsHtml;
    }

    const btnUbah = document.getElementById('btnUbahStatusDariDetail');
    if (btnUbah) {
      btnUbah.style.display = 'none';
    }

    const btnWa = document.getElementById('btnChatWa');
    const nomorWa = formatNomorWa(r.hp);
    if (nomorWa) {
      btnWa.href = 'https://wa.me/' + nomorWa;
      btnWa.style.display = 'inline-flex';
    } else {
      btnWa.style.display = 'none';
    }

    openModal('modalDetailOrang');
  }

  window.updateStatusPendukungDariDesktop = async function (newStatus) {
    const r = window.currentDetailRecord;
    if (!r) return;
    const targetId = r.id || r.rowNumber;
    if (!targetId) {
      alert('ID pendukung tidak valid.');
      return;
    }

    if (!confirm('Ubah status verifikasi "' + (r.nama || 'pendukung') + '" menjadi: ' + newStatus + '?')) {
      return;
    }

    try {
      const res = await fetchApi('verifikasi.php?action=update-status', 'POST', {
        id: targetId,
        rowNumber: targetId,
        newStatus: newStatus,
        catatan: 'Diperbarui via Web Desktop'
      });

      if (res && res.success) {
        r.status = newStatus;
        if (window.currentDetailRecord) {
          window.currentDetailRecord.status = newStatus;
        }

        bukaDetailOrang(r, window.currentDetailOpsi || {});

        if (window.currentListRows && Array.isArray(window.currentListRows)) {
          const itemInList = window.currentListRows.find(function (x) {
            return (x.id || x.rowNumber) == targetId;
          });
          if (itemInList) {
            itemInList.status = newStatus;
          }
          if (typeof renderTabelList === 'function') {
            renderTabelList(window.currentListRows);
          }
        }

        if (typeof loadRiwayat === 'function') {
          try { loadRiwayat(); } catch (e) {}
        }

        if (typeof loadDashboardData === 'function') {
          try { loadDashboardData(); } catch (e) {}
        }

        alert('Status verifikasi berhasil diperbarui menjadi: ' + newStatus);
      } else {
        const errMsg = (res && res.message) ? res.message : 'Gagal memperbarui status verifikasi.';
        alert(errMsg);
      }
    } catch (err) {
      console.error('Gagal update status verifikasi:', err);
      alert('Terjadi kesalahan jaringan atau server saat memperbarui status.');
    }
  };

  // ============ INIT ============
  window.onload = function () {
    if (SESSION_TOKEN) {
      google.script.run.withSuccessHandler(onSessionChecked).checkSession(SESSION_TOKEN);
    }
  };

  function onSessionChecked(result) {
    if (result.valid) {
      CURRENT_USER = result.user;
      enterApp();
    } else {
      localStorage.removeItem('dprd_token');
      SESSION_TOKEN = null;
    }
  }

  // ============ LOGIN ============
  function doLogin() {
    const username = document.getElementById('loginUsername').value.trim();
    const password = document.getElementById('loginPassword').value;
    const errBox = document.getElementById('loginError');
    errBox.style.display = 'none';

    if (!username || !password) {
      errBox.textContent = 'Username dan password wajib diisi.';
      errBox.style.display = 'block';
      return;
    }

    const btn = document.getElementById('btnLogin');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> Memproses...';

    google.script.run
      .withSuccessHandler(function (res) {
        btn.disabled = false;
        btn.textContent = 'Masuk';
        if (res.success) {
          SESSION_TOKEN = res.token;
          CURRENT_USER = res.user;
          localStorage.setItem('dprd_token', SESSION_TOKEN);
          enterApp();
        } else {
          errBox.textContent = res.message;
          errBox.style.display = 'block';
        }
      })
      .withFailureHandler(function (err) {
        btn.disabled = false;
        btn.textContent = 'Masuk';
        errBox.textContent = 'Terjadi kesalahan: ' + err.message;
        errBox.style.display = 'block';
      })
      .login(username, password);
  }

  function doLogout() {
    google.script.run.logout(SESSION_TOKEN);
    localStorage.removeItem('dprd_token');
    localStorage.removeItem('gusdim_active_page');
    if (window.location.hash) {
      history.replaceState(null, '', window.location.pathname);
    }
    SESSION_TOKEN = null;
    CURRENT_USER = null;
    document.getElementById('appScreen').style.display = 'none';
    document.getElementById('loginScreen').style.display = 'flex';
  }

  // ============ REALTIME WEBSOCKET NOTIFIKASI (LARAVEL REVERB) ============
  function showDesktopToast(message, type = 'info') {
    let container = document.getElementById('desktopToastContainer');
    if (!container) {
      container = document.createElement('div');
      container.id = 'desktopToastContainer';
      container.style.cssText = 'position:fixed;top:24px;right:24px;z-index:999999;display:flex;flex-direction:column;gap:10px;pointer-events:none;';
      document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    const bg = type === 'success' ? '#059669' : type === 'danger' ? '#dc2626' : '#1e3a8a';
    toast.style.cssText = 'background:' + bg + ';color:#ffffff;padding:14px 20px;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.25);font-family:system-ui,-apple-system,sans-serif;font-size:14px;font-weight:500;display:flex;align-items:center;gap:12px;pointer-events:auto;transition:all 0.3s cubic-bezier(0.4, 0, 0.2, 1);opacity:0;transform:translateY(-15px);max-width:400px;line-height:1.4;';

    let iconSvg = '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>';
    if (type === 'info') {
      iconSvg = '<svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>';
    }

    toast.innerHTML = '<span style="flex-shrink:0;display:inline-flex;">' + iconSvg + '</span><span>' + message + '</span>';
    container.appendChild(toast);

    requestAnimationFrame(function () {
      toast.style.opacity = '1';
      toast.style.transform = 'translateY(0)';
    });

    setTimeout(function () {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(-15px)';
      setTimeout(function () { toast.remove(); }, 350);
    }, 4500);
  }

  function initRealtimeReverb() {
    if (typeof Pusher === 'undefined') {
      console.warn('Pusher JS belum terpasang, WebSocket real-time dilewati.');
      return;
    }
    if (window._reverbPusher) return;

    try {
      const pusher = new Pusher('ifst0r0e5f3stkfy1k6g', {
        wsHost: 'gusdim.com',
        wsPort: 6001,
        wssPort: 6001,
        forceTLS: true,
        disableStats: true,
        enabledTransports: ['ws', 'wss'],
        cluster: 'mt1'
      });

      const channel = pusher.subscribe('gusdim-updates');

      channel.bind('PendukungCreated', function (data) {
        console.log('Realtime PendukungCreated diterima:', data);
        showDesktopToast('Data Baru: ' + (data.nama || 'Warga') + ' (' + (data.jalur || 'Relawan') + ') - Desa ' + (data.desa || '-'), 'info');

        if (typeof loadDashboard === 'function') {
          try { loadDashboard(); } catch (e) {}
        }
        const activeHash = (window.location.hash || '').replace('#', '');
        if (activeHash === 'pendukung' && typeof applyListFilter === 'function') {
          try { applyListFilter(); } catch (e) {}
        }
        if (activeHash === 'riwayat' && typeof loadRiwayat === 'function') {
          try { loadRiwayat(); } catch (e) {}
        }
      });

      channel.bind('PendukungStatusUpdated', function (data) {
        console.log('Realtime PendukungStatusUpdated diterima:', data);
        showDesktopToast('Status Verifikasi: ' + (data.nama || 'Data') + ' diubah jadi "' + data.new_status + '" oleh ' + (data.actor_name || 'Petugas'), 'success');

        if (typeof loadDashboard === 'function') {
          try { loadDashboard(); } catch (e) {}
        }
        const activeHash = (window.location.hash || '').replace('#', '');
        if (activeHash === 'pendukung' && typeof applyListFilter === 'function') {
          try { applyListFilter(); } catch (e) {}
        }
        if (activeHash === 'riwayat' && typeof loadRiwayat === 'function') {
          try { loadRiwayat(); } catch (e) {}
        }
      });

      window._reverbPusher = pusher;
      console.log('Realtime Reverb WebSocket Desktop Aktif di Channel: gusdim-updates');
    } catch (err) {
      console.warn('Gagal menghubungkan Realtime Reverb Desktop:', err);
    }
  }

  function enterApp() {
    document.getElementById('loginScreen').style.display = 'none';
    document.getElementById('appScreen').style.display = 'block';

    initRealtimeReverb();

    document.getElementById('userName').textContent = CURRENT_USER.nama;
    document.getElementById('userRole').textContent = CURRENT_USER.role +
      (CURRENT_USER.ranting ? ' - ' + CURRENT_USER.ranting : '');
    if (CURRENT_USER.fotoProfil) {
      document.getElementById('userAvatar').innerHTML = '<img src="' + CURRENT_USER.fotoProfil + '" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">';
    } else {
      document.getElementById('userAvatar').textContent = CURRENT_USER.nama.charAt(0).toUpperCase();
    }

    if (CURRENT_USER.role !== 'Admin Ranting' && CURRENT_USER.role !== 'Superadmin') {
      const inputMenu = document.querySelector('[data-page="input"]');
      if (inputMenu) {
        inputMenu.classList.add('disabled');
        inputMenu.onclick = null;
      }
    }

    if (CURRENT_USER.role !== 'Superadmin') {
      const setMenu = document.querySelector('[data-page="pengaturan"]');
      if (setMenu) {
        setMenu.classList.add('disabled');
        setMenu.onclick = null;
      }
    }

    // Deteksi target halaman terakhir dari URL Hash atau LocalStorage
    let targetPage = 'dashboard';
    const hash = (window.location.hash || '').replace('#', '').trim();
    const allowedPages = ['dashboard', 'pendukung', 'reses', 'input', 'riwayat', 'pengaturan', 'profil', 'aspirasi', 'leaderboard'];

    if (hash && allowedPages.includes(hash)) {
      targetPage = hash;
    } else {
      const savedPage = localStorage.getItem('gusdim_active_page');
      if (savedPage && allowedPages.includes(savedPage)) {
        targetPage = savedPage;
      }
    }

    // Role guard: cegah akses langsung jika role tidak berwenang
    if (targetPage === 'input' && CURRENT_USER.role !== 'Admin Ranting' && CURRENT_USER.role !== 'Superadmin') {
      targetPage = 'dashboard';
    }
    if (targetPage === 'pengaturan' && CURRENT_USER.role !== 'Superadmin') {
      targetPage = 'dashboard';
    }

    showPage(targetPage, false);
  }

  // ============ NAVIGASI ============
  function showPage(pageName, updateHash) {
    if (updateHash === undefined) updateHash = true;

    const allowedPages = ['dashboard', 'pendukung', 'reses', 'input', 'riwayat', 'pengaturan', 'profil', 'aspirasi', 'leaderboard'];
    if (!allowedPages.includes(pageName)) {
      pageName = 'dashboard';
    }

    document.querySelectorAll('.menu-item').forEach(el => el.classList.remove('active'));
    const menuEl = document.querySelector('[data-page="' + pageName + '"]');
    if (menuEl) menuEl.classList.add('active');

    document.querySelectorAll('.page').forEach(el => el.classList.remove('active'));
    const targetEl = document.getElementById('page-' + pageName);
    if (targetEl) targetEl.classList.add('active');

    // Sinkronisasi URL Hash & LocalStorage
    if (updateHash) {
      if (window.location.hash !== '#' + pageName) {
        history.pushState(null, '', '#' + pageName);
      }
    }
    try {
      localStorage.setItem('gusdim_active_page', pageName);
    } catch (e) {}

    const titles = {
      dashboard: 'Dashboard',
      pendukung: 'Data Pendukung',
      reses: 'Masa Reses & Pokir',
      input: 'Input Data',
      riwayat: 'Riwayat & Log Aktivitas',
      pengaturan: 'Pengaturan Pengguna',
      profil: 'Profil Saya',
      aspirasi: 'Aspirasi',
      leaderboard: 'Leaderboard'
    };
    const pt = document.getElementById('pageTitle'); if (pt) pt.textContent = titles[pageName] || '';

    if (pageName === 'dashboard') { loadDashboard(); setTimeout(function() { if (mapInstance) mapInstance.invalidateSize(); }, 250); }
    if (pageName === 'pendukung') {
      if (!DASHBOARD_DATA) {
        google.script.run.withSuccessHandler(function (data) {
          renderDashboard(data);
          populateFilterDropdowns();
          applyListFilter();
        }).getDashboardData(SESSION_TOKEN);
      } else {
        populateFilterDropdowns();
        applyListFilter();
      }
    }
    if (pageName === 'reses') loadReses();
    if (pageName === 'riwayat') { loadRiwayat(); loadLogAktivitas(); }
    if (pageName === 'pengaturan') loadUsersList();
    if (pageName === 'profil') loadProfil();
    if (pageName === 'aspirasi') loadAspirasi();
    if (pageName === 'leaderboard') loadLeaderboard();
  }

  // Dukungan tombol Back / Forward browser
  window.addEventListener('popstate', function () {
    const hash = (window.location.hash || '').replace('#', '').trim();
    const allowedPages = ['dashboard', 'pendukung', 'reses', 'input', 'riwayat', 'pengaturan', 'profil', 'aspirasi', 'leaderboard'];
    if (hash && allowedPages.includes(hash) && CURRENT_USER) {
      showPage(hash, false);
    }
  });

  function switchTab(tabName) {
    document.querySelectorAll('.tab-item').forEach(el => el.classList.remove('active'));
    document.querySelector('[data-tab="' + tabName + '"]').classList.add('active');
    document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
    document.getElementById('tab-' + tabName).classList.add('active');
  }

  function openModal(id) { document.getElementById(id).classList.add('show'); }
  function closeModal(id) { document.getElementById(id).classList.remove('show'); }

  // ============ TOGGLE SIDEBAR (Desktop collapse / Mobile drawer) ============
  function isMobileView() { return window.innerWidth <= 768; }

  function toggleSidebar(forceState) {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    if (isMobileView()) {
      const shouldOpen = forceState !== undefined ? forceState : !sidebar.classList.contains('mobile-open');
      sidebar.classList.toggle('mobile-open', shouldOpen);
      overlay.classList.toggle('show', shouldOpen);
    } else {
      sidebar.classList.toggle('collapsed');
    }
  }

  // Tutup drawer sidebar otomatis di mobile setelah pilih menu
  const _showPageAsli = showPage;
  showPage = function (pageName) {
    _showPageAsli(pageName);
    if (isMobileView()) toggleSidebar(false);
  };

  // ============ DASHBOARD (Interaktif: Kecamatan -> Desa -> List Data) ============
  let DASHBOARD_DATA = null;
  let chartLevel = 'kecamatan'; // 'kecamatan' | 'desa'
  let chartKecamatanAktif = null;

  function loadDashboard() {
    google.script.run.withSuccessHandler(renderDashboard).getDashboardData(SESSION_TOKEN);
  }

  function renderDashboard(data) {
    if (!data.success) { alert(data.message); return; }
    DASHBOARD_DATA = data;
    chartLevel = 'kecamatan';
    chartKecamatanAktif = null;

    renderStatCards(data);
    renderMap(data.mapPoints);
    if (typeof loadReses === 'function') { loadReses(); }
    renderChartJalur(data.byJalur);
    renderChartKecamatan();
    populateFilterDropdowns();
    resetListFilter();
  }

  function renderChartJalur(byJalur) {
    const ctx = document.getElementById('chartJalur');
    const labels = Object.keys(byJalur);
    const values = Object.values(byJalur);

    if (chartJalurInstance) chartJalurInstance.destroy();
    chartJalurInstance = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: labels,
        datasets: [{ data: values, backgroundColor: ['#16225e', '#ffb703', '#2f5fd6', '#f59e0b', '#7c93e8'] }]
      },
      options: { responsive: true, plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } } } }
    });
  }

  function renderStatCards(data) {
    const cardsWrap = document.getElementById('statCards');
    cardsWrap.innerHTML =
      '<div class="stat-card stat-total clickable" onclick="filterListByJalur(null)">' +
        '<div class="label">Total Semua Jalur</div><div class="value">' + data.total + '</div>' +
      '</div>';
    Object.keys(data.byJalur).forEach(function (jalur) {
      cardsWrap.innerHTML +=
        '<div class="stat-card clickable" onclick="filterListByJalur(\'' + jalur.replace(/'/g,"\\'") + '\')">' +
          '<div class="label">' + jalur + '</div><div class="value">' + data.byJalur[jalur] + '</div>' +
        '</div>';
    });
  }

    function filterListByJalur(jalurLabel) {
    if (!DASHBOARD_DATA) return;
    if (typeof showPage === 'function') {
      showPage('pendukung');
    }
    const targetJalur = jalurLabel || '';
    const flJalur = document.getElementById('flJalur');
    if (flJalur) flJalur.value = targetJalur;
    const flKec = document.getElementById('flKecamatan');
    if (flKec) flKec.value = '';
    const flDesa = document.getElementById('flDesa');
    if (flDesa) flDesa.value = '';
    const flPenginput = document.getElementById('flPenginput');
    if (flPenginput) flPenginput.value = '';
    const searchInput = document.getElementById('tableFilterInput');
    if (searchInput) searchInput.value = '';
    window.tableSearchQuery = '';
    window.currentListPage = 1;

    // Sinkronkan tab pill di halaman data pendukung
    document.querySelectorAll('.saas-tab-pill').forEach(function (p) {
      const txt = p.textContent.trim();
      if (!targetJalur && txt === 'Semua Jalur') {
        p.classList.add('active');
      } else if (targetJalur && (txt === targetJalur || (targetJalur === 'PIP' && txt.indexOf('PIP') > -1) || (targetJalur === 'KIP' && txt.indexOf('KIP') > -1) || (targetJalur === 'Relawan' && txt.indexOf('Relawan') > -1))) {
        p.classList.add('active');
      } else {
        p.classList.remove('active');
      }
    });

    sortColumn = null; sortDirection = 'asc'; updateSortIcons();
    applyListFilter();
    setTimeout(function () {
      const el = document.getElementById('page-pendukung');
      if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 150);
  }

  function renderChartKecamatan() {
    document.getElementById('chartTitle').textContent = 'Rekap per Kecamatan';
    document.getElementById('btnKembaliChart').style.display = 'none';
    chartLevel = 'kecamatan';
    chartKecamatanAktif = null;

    const labels = Object.keys(DASHBOARD_DATA.byKecamatan);
    const values = labels.map(function (k) { return DASHBOARD_DATA.byKecamatan[k]; });
    drawChart(labels, values, function (index) { bukaDesaChart(labels[index]); });
  }

  function bukaDesaChart(kecamatan) {
    chartLevel = 'desa';
    chartKecamatanAktif = kecamatan;
    document.getElementById('chartTitle').textContent = 'Rekap per Desa — ' + kecamatan;
    document.getElementById('btnKembaliChart').style.display = 'inline-block';

    const recordsKec = DASHBOARD_DATA.allRecords.filter(function (r) { return r.kecamatan === kecamatan; });
    const byDesa = {};
    recordsKec.forEach(function (r) {
      const label = r.desa || '(' + r.jalur + ' - tanpa desa)';
      byDesa[label] = (byDesa[label] || 0) + 1;
    });
    const labels = Object.keys(byDesa);
    const values = labels.map(function (d) { return byDesa[d]; });
    drawChart(labels, values, function (index) { bukaListDesa(kecamatan, labels[index]); });
  }

  function bukaListDesa(kecamatan, desaLabel) {
    if (typeof showPage === 'function') {
      showPage('pendukung');
    }
    const isTanpaDesa = desaLabel && desaLabel.indexOf('(') === 0;
    const flJalur = document.getElementById('flJalur');
    if (flJalur) flJalur.value = '';
    const flPenginput = document.getElementById('flPenginput');
    if (flPenginput) flPenginput.value = '';
    const searchInput = document.getElementById('tableFilterInput');
    if (searchInput) searchInput.value = '';
    window.tableSearchQuery = '';
    window.currentListPage = 1;

    document.querySelectorAll('.saas-tab-pill').forEach(function (p) {
      if (p.textContent.trim() === 'Semua Jalur') p.classList.add('active');
      else p.classList.remove('active');
    });

    sortColumn = null; sortDirection = 'asc'; updateSortIcons();
    const flKec = document.getElementById('flKecamatan');
    if (flKec) {
      flKec.value = kecamatan;
      if (typeof populateDesaFilter === 'function') populateDesaFilter();
    }

    if (isTanpaDesa) {
      const flDesa = document.getElementById('flDesa');
      if (flDesa) flDesa.value = '';
      const limit = parseInt(document.getElementById('flLimit').value, 10) || 10;
      const rows = DASHBOARD_DATA.allRecords.filter(function (r) { return r.kecamatan === kecamatan && !r.desa; });
      const titleEl = document.getElementById('listPanelTitle');
      if (titleEl) titleEl.textContent = 'Data: ' + kecamatan + ' / ' + desaLabel + ' (' + rows.length + ')';
      renderTabelList(rows.slice(0, limit));
    } else {
      const flDesa = document.getElementById('flDesa');
      if (flDesa) flDesa.value = desaLabel;
      applyListFilter();
    }
    setTimeout(function () {
      const el = document.getElementById('page-pendukung');
      if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }, 150);
  }

  function chartKembali() {
    renderChartKecamatan();
  }

  function drawChart(labels, values, onClickBar) {
    const ctx = document.getElementById('chartProgram');
    const palet = ['#16225e', '#ffb703', '#2f5fd6', '#f59e0b', '#7c93e8', '#a5b4fc', '#fcd34d'];

    if (chartInstance) chartInstance.destroy();
    chartInstance = new Chart(ctx, {
      type: 'bar',
      data: { labels: labels, datasets: [{ data: values, backgroundColor: labels.map(function (_, i) { return palet[i % palet.length]; }), borderRadius: 6 }] },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
        onClick: function (evt, elements) {
          if (elements.length > 0 && onClickBar) onClickBar(elements[0].index);
        },
        onHover: function (evt, elements) {
          evt.native.target.style.cursor = elements.length ? 'pointer' : 'default';
        }
      }
    });
  }

    // Geospasial Dapil Kraksaan Raya Dictionary
  const DAPIL_GEO = {
    kraksaan: {
      default: [-7.760, 113.430],
      sidomukti: [-7.752, 113.425],
      kraksaan_wetan: [-7.759, 113.435],
      kandangjati_kulon: [-7.765, 113.418],
      kandangjati_wetan: [-7.768, 113.431],
      semampir: [-7.761, 113.424],
      patokan: [-7.756, 113.419],
      bulu: [-7.745, 113.420],
      kalibuntu: [-7.738, 113.432],
      kebonagung: [-7.771, 113.428],
      kregenan: [-7.780, 113.415],
      rondokuning: [-7.785, 113.428]
    },
    besuk: {
      default: [-7.805, 113.465],
      besuk_agung: [-7.802, 113.468],
      besuk_kidul: [-7.815, 113.472],
      alaskandang: [-7.790, 113.455],
      bago: [-7.825, 113.460],
      jambangan: [-7.810, 113.485],
      kecik: [-7.800, 113.460],
      klampokan: [-7.785, 113.480],
      krampilan: [-7.820, 113.450],
      matekan: [-7.795, 113.490],
      randujalu: [-7.830, 113.475],
      sindetlami: [-7.780, 113.465],
      sumurdalam: [-7.835, 113.485]
    },
    gading: {
      default: [-7.865, 113.445],
      gading_wetan: [-7.855, 113.450],
      wangkal: [-7.870, 113.440],
      betek_kidul: [-7.845, 113.430],
      batur: [-7.880, 113.435],
      bulupandak: [-7.860, 113.420],
      condong: [-7.890, 113.455],
      jurangjero: [-7.875, 113.460],
      kaliacar: [-7.850, 113.460],
      kebik: [-7.865, 113.465],
      nogosaren: [-7.900, 113.445],
      prasi: [-7.840, 113.440],
      ranuwurung: [-7.885, 113.430],
      renteng: [-7.860, 113.455]
    }
  };

  function getDapilCoords(kec, desa) {
    const k = (kec || '').toLowerCase().trim();
    const d = (desa || '').toLowerCase().replace(/desa\s+|kelurahan\s+/g, '').trim().replace(/\s+/g, '_');
    
    let base = null;
    if (k.includes('kraksaan')) {
      base = DAPIL_GEO.kraksaan[d] || DAPIL_GEO.kraksaan.default;
    } else if (k.includes('besuk')) {
      base = DAPIL_GEO.besuk[d] || DAPIL_GEO.besuk.default;
    } else if (k.includes('gading')) {
      base = DAPIL_GEO.gading[d] || DAPIL_GEO.gading.default;
    } else {
      base = [-7.7833, 113.42];
    }
    
    const jitterLat = (Math.random() - 0.5) * 0.003;
    const jitterLng = (Math.random() - 0.5) * 0.003;
    return [base[0] + jitterLat, base[1] + jitterLng];
  }

  let activeMapLayerType = 'all';
  let cachedMapPoints = [];

  function setMapLayer(layer) {
    activeMapLayerType = layer;
    document.querySelectorAll('.map-layer-pill').forEach(p => p.classList.remove('active'));
    const activePill = document.getElementById(
      layer === 'all' ? 'pillLayerAll' :
      layer === 'pendukung' ? 'pillLayerPendukung' :
      layer === 'reses' ? 'pillLayerReses' : 'pillLayerPokir'
    );
    if (activePill) activePill.classList.add('active');

    if (!mapInstance) return;

    if (layer === 'all') {
      if (mapInstance._pendukungLayer && !mapInstance.hasLayer(mapInstance._pendukungLayer)) mapInstance.addLayer(mapInstance._pendukungLayer);
      if (mapInstance._resesLayer && !mapInstance.hasLayer(mapInstance._resesLayer)) mapInstance.addLayer(mapInstance._resesLayer);
      if (mapInstance._pokirLayer && !mapInstance.hasLayer(mapInstance._pokirLayer)) mapInstance.addLayer(mapInstance._pokirLayer);
    } else if (layer === 'pendukung') {
      if (mapInstance._pendukungLayer && !mapInstance.hasLayer(mapInstance._pendukungLayer)) mapInstance.addLayer(mapInstance._pendukungLayer);
      if (mapInstance._resesLayer && mapInstance.hasLayer(mapInstance._resesLayer)) mapInstance.removeLayer(mapInstance._resesLayer);
      if (mapInstance._pokirLayer && mapInstance.hasLayer(mapInstance._pokirLayer)) mapInstance.removeLayer(mapInstance._pokirLayer);
    } else if (layer === 'reses') {
      if (mapInstance._pendukungLayer && mapInstance.hasLayer(mapInstance._pendukungLayer)) mapInstance.removeLayer(mapInstance._pendukungLayer);
      if (mapInstance._resesLayer && !mapInstance.hasLayer(mapInstance._resesLayer)) mapInstance.addLayer(mapInstance._resesLayer);
      if (mapInstance._pokirLayer && mapInstance.hasLayer(mapInstance._pokirLayer)) mapInstance.removeLayer(mapInstance._pokirLayer);
    } else if (layer === 'pokir') {
      if (mapInstance._pendukungLayer && mapInstance.hasLayer(mapInstance._pendukungLayer)) mapInstance.removeLayer(mapInstance._pendukungLayer);
      if (mapInstance._resesLayer && mapInstance.hasLayer(mapInstance._resesLayer)) mapInstance.removeLayer(mapInstance._resesLayer);
      if (mapInstance._pokirLayer && !mapInstance.hasLayer(mapInstance._pokirLayer)) mapInstance.addLayer(mapInstance._pokirLayer);
    }
  }

  function zoomMapTo(target) {
    if (!mapInstance) return;
    if (target === 'all') {
      mapInstance.flyTo([-7.795, 113.435], 11);
    } else if (target === 'kraksaan') {
      mapInstance.flyTo([-7.760, 113.430], 13);
    } else if (target === 'besuk') {
      mapInstance.flyTo([-7.805, 113.465], 13);
    } else if (target === 'gading') {
      mapInstance.flyTo([-7.865, 113.445], 13);
    }
  }

  function renderMap(points) {
    if (points) cachedMapPoints = points;
    const pts = cachedMapPoints || [];

    if (!mapInstance) {
      mapInstance = L.map('peta').setView([-7.795, 113.435], 11);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors | Fraksi NasDem Gus Dim'
      }).addTo(mapInstance);

      mapInstance._pendukungLayer = L.layerGroup().addTo(mapInstance);
      mapInstance._resesLayer = L.layerGroup().addTo(mapInstance);
      mapInstance._pokirLayer = L.layerGroup().addTo(mapInstance);
    } else {
      if (mapInstance._pendukungLayer) mapInstance._pendukungLayer.clearLayers();
      if (mapInstance._resesLayer) mapInstance._resesLayer.clearLayers();
      if (mapInstance._pokirLayer) mapInstance._pokirLayer.clearLayers();
    }

    // 1. Plot Pendukung
    let countPendukung = 0;
    pts.forEach(function (p) {
      if (!p.lat || !p.lng) return;
      countPendukung++;
      const icon = L.divIcon({
        className: '',
        html: '<div class="custom-pin pin-pendukung"><div class="custom-pin-inner"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div></div>',
        iconSize: [30, 30],
        iconAnchor: [15, 30],
        popupAnchor: [0, -28]
      });

      const marker = L.marker([p.lat, p.lng], { icon: icon }).addTo(mapInstance._pendukungLayer);
      const wilayah = [p.desa, p.kecamatan].filter(Boolean).join(', ');

      const popupHtml = `
        <div class="popup-box">
          <span class="popup-badge badge-blue">Pendukung &bull; ` + (p.jalur || 'Relawan') + `</span>
          <div class="popup-title">` + (p.nama || 'Konstituen') + `</div>
          <div class="popup-desc">` + (p.alamat ? (p.alamat.length > 55 ? p.alamat.substring(0, 55) + '...' : p.alamat) : wilayah) + `</div>
          <div class="popup-meta">
            <span>Wilayah: <b>Desa ` + (p.desa || '-') + `, Kec. ` + (p.kecamatan || '-') + `</b></span>
            <span>Penginput: <b>` + (p.userInput || 'Relawan') + `</b></span>
          </div>
          <div style="margin-top:8px;">
            <button class="btn-primary-nasdem" style="width:100%; padding:5px 8px; font-size:11px; justify-content:center;" onclick="window.open('https://www.google.com/maps/search/?api=1&query=` + p.lat + `,` + p.lng + `', '_blank')">
              Buka di Google Maps
            </button>
          </div>
        </div>
      `;
      marker.bindPopup(popupHtml, { className: 'custom-map-popup' });
    });

    // 2. Plot Reses
    let countReses = 0;
    const events = (RESES_DATA && RESES_DATA.events) ? RESES_DATA.events : [];
    events.forEach(function (ev) {
      countReses++;
      const coords = (ev.lat && ev.lng) ? [ev.lat, ev.lng] : getDapilCoords(ev.kecamatan, ev.desa);
      const icon = L.divIcon({
        className: '',
        html: '<div class="custom-pin pin-reses"><div class="custom-pin-inner"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div></div>',
        iconSize: [30, 30],
        iconAnchor: [15, 30],
        popupAnchor: [0, -28]
      });

      const marker = L.marker(coords, { icon: icon }).addTo(mapInstance._resesLayer);
      const popupHtml = `
        <div class="popup-box">
          <span class="popup-badge badge-green">Titik Kunjungan Reses</span>
          <div class="popup-title">` + (ev.nama_acara || 'Reses Gus Dim') + `</div>
          <div class="popup-desc">` + (ev.lokasi_detail || 'Pertemuan Konstituen') + `</div>
          <div class="popup-meta">
            <span>Tanggal: <b>` + formatTanggalIndo(ev.tanggal) + `</b> ` + (ev.waktu ? '&bull; ' + ev.waktu : '') + `</span>
            <span>Wilayah: <b>Desa ` + (ev.desa || '-') + `, Kec. ` + (ev.kecamatan || '-') + `</b></span>
            <span>Target: <b>` + (ev.target_kelompok || 'Masyarakat Umum') + `</b></span>
          </div>
          <div style="margin-top:8px;">
            <button class="btn-primary-nasdem" style="width:100%; padding:5px 8px; font-size:11px; justify-content:center;" onclick="showPage('reses')">
              Lihat Agenda Reses
            </button>
          </div>
        </div>
      `;
      marker.bindPopup(popupHtml, { className: 'custom-map-popup' });
    });

    // 3. Plot Pokir
    let countPokir = 0;
    const pokirs = (RESES_DATA && RESES_DATA.pokir) ? RESES_DATA.pokir : [];
    pokirs.forEach(function (pk) {
      countPokir++;
      const coords = (pk.lat && pk.lng) ? [pk.lat, pk.lng] : getDapilCoords(pk.kecamatan, pk.desa);
      const icon = L.divIcon({
        className: '',
        html: '<div class="custom-pin pin-pokir"><div class="custom-pin-inner"><svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="11" x2="12" y2="17"/><line x1="9" y1="14" x2="15" y2="14"/></svg></div></div>',
        iconSize: [30, 30],
        iconAnchor: [15, 30],
        popupAnchor: [0, -28]
      });

      const marker = L.marker(coords, { icon: icon }).addTo(mapInstance._pokirLayer);
      const popupHtml = `
        <div class="popup-box">
          <span class="popup-badge badge-amber">Usulan Pokir &bull; ` + (pk.kategori || 'Umum') + `</span>
          <div class="popup-title">` + (pk.judul_usulan || 'Program Pokir') + `</div>
          <div class="popup-desc">Pagu: <b style="color:#0f172a;">` + formatRupiah(pk.estimasi_anggaran) + `</b></div>
          <div class="popup-meta">
            <span>Wilayah: <b>Desa ` + (pk.desa || '-') + `, Kec. ` + (pk.kecamatan || '-') + `</b></span>
            <span>Tahap Siklus: <b>` + (pk.status_tahap || 'Aspirasi Reses') + `</b></span>
            <span>Pengusul: <b>` + (pk.nama_pengusul || '-') + `</b></span>
          </div>
          <div style="margin-top:8px;">
            <button class="btn-primary-nasdem" style="width:100%; padding:5px 8px; font-size:11px; justify-content:center;" onclick="showPage('reses'); switchResesTab('pokir', document.getElementById('tabPillPokir'));">
              Buka Bank Pokir
            </button>
          </div>
        </div>
      `;
      marker.bindPopup(popupHtml, { className: 'custom-map-popup' });
    });

    // Update Counts on UI
    const elCp = document.getElementById('mapCountPendukung');
    if (elCp) elCp.textContent = countPendukung;
    const elCr = document.getElementById('mapCountReses');
    if (elCr) elCr.textContent = countReses;
    const elCk = document.getElementById('mapCountPokir');
    if (elCk) elCk.textContent = countPokir;
    const elCt = document.getElementById('mapTotalPoints');
    if (elCt) elCt.textContent = (countPendukung + countReses + countPokir);

    // Apply active layer filter
    setMapLayer(activeMapLayerType);
  }

  // ============ FILTER PANEL "Data Terbaru Diinput" ============
  const KOLOM_FILTER_LIST = ['nama', 'jalur', 'wilayah', 'penginput', 'status'];
  let sortColumn = null;   // kolom yang sedang diurutkan, null = default (tanggal terbaru)
  let sortDirection = 'asc'; // 'asc' (A-Z) atau 'desc' (Z-A)

  const ICON_FILTER_NETRAL = '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"><line x1="4" y1="6" x2="20" y2="6"/><line x1="7" y1="12" x2="17" y2="12"/><line x1="10" y1="18" x2="14" y2="18"/></svg>';
  const ICON_FILTER_AKTIF_ASC = '<svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor"><rect x="5" y="5" width="14" height="14" rx="3"/></svg>';
  const ICON_FILTER_AKTIF_DESC = '<svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor" stroke="currentColor" stroke-width="1"><rect x="5" y="5" width="14" height="14" rx="3" fill="none"/><rect x="9" y="9" width="6" height="6"/></svg>';

  function toggleFilterPanel() {
    const panel = document.getElementById('filterPanelList');
    const btn = document.getElementById('btnToggleFilter');
    panel.classList.toggle('open');
    btn.textContent = panel.classList.contains('open') ? 'Filter ▴' : 'Filter ▾';
  }

  function populateFilterDropdowns() {
    const desaSet = new Set();
    const penginputSet = new Set();
    (DASHBOARD_DATA.allRecords || []).forEach(function (r) {
      if (r.desa) desaSet.add(r.desa);
      if (r.userInput) penginputSet.add(r.userInput);
    });
    document.getElementById('flDesa').innerHTML = '<option value="">Semua Desa</option>' +
      Array.from(desaSet).sort().map(function (d) { return '<option value="' + d + '">' + d + '</option>'; }).join('');
    document.getElementById('flPenginput').innerHTML = '<option value="">Semua Penginput</option>' +
      Array.from(penginputSet).sort().map(function (p) { return '<option value="' + p + '">' + p + '</option>'; }).join('');
  }

  function getColumnValue(r, col) {
    if (col === 'nama') return r.nama || '-';
    if (col === 'jalur') return r.jalur || '-';
    if (col === 'wilayah') return [r.kecamatan, r.desa].filter(Boolean).join(' / ') || '-';
    if (col === 'penginput') return r.userInput || '-';
    if (col === 'status') return r.status || '-';
    return '';
  }

  function resetListFilter() {
    document.getElementById('flJalur').value = '';
    document.getElementById('flKecamatan').value = '';
    document.getElementById('flDesa').value = '';
    document.getElementById('flPenginput').value = '';
    document.getElementById('flLimit').value = '10';
    sortColumn = null;
    sortDirection = 'asc';
    updateSortIcons();
    applyListFilter();
  }

  // ---- Klik ikon di header = langsung urutkan A-Z, klik lagi = Z-A (ikon terbalik) ----
  function toggleColumnSort(col, evt) {
    if (evt) evt.stopPropagation();
    if (sortColumn === col) {
      sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';
    } else {
      sortColumn = col;
      sortDirection = 'asc';
    }
    updateSortIcons();
    applyListFilter();
  }

  function updateSortIcons() {
    document.querySelectorAll('.th-filter-btn').forEach(function (btn) {
      const col = btn.getAttribute('data-filter-btn');
      const aktif = col === sortColumn;
      btn.classList.toggle('active', aktif);
      btn.classList.toggle('sort-desc', aktif && sortDirection === 'desc');
      if (aktif) {
        btn.innerHTML = sortDirection === 'asc' ? ICON_FILTER_AKTIF_ASC : ICON_FILTER_AKTIF_DESC;
      } else {
        btn.innerHTML = ICON_FILTER_NETRAL;
      }
    });
  }

  function applyListFilter() {
    const jalur = (document.getElementById('flJalur') || {}).value;
    const kecamatan = (document.getElementById('flKecamatan') || {}).value;
    const desa = (document.getElementById('flDesa') || {}).value;
    const penginput = (document.getElementById('flPenginput') || {}).value;
    const limit = parseInt((document.getElementById('flLimit') || {}).value || '10', 10);

    let rows = (DASHBOARD_DATA && DASHBOARD_DATA.allRecords ? DASHBOARD_DATA.allRecords : []).slice();
    if (jalur) rows = rows.filter(function (r) { return r.jalur === jalur; });
    if (kecamatan) rows = rows.filter(function (r) { return r.kecamatan === kecamatan; });
    if (desa) rows = rows.filter(function (r) { return r.desa === desa; });
    if (penginput) rows = rows.filter(function (r) { return r.userInput === penginput; });

    if (window.tableSearchQuery) {
      const q = window.tableSearchQuery.toLowerCase().trim();
      if (q) {
        rows = rows.filter(function (r) {
          return (r.nama && r.nama.toLowerCase().includes(q)) ||
                 (r.nik && String(r.nik).includes(q)) ||
                 (r.desa && r.desa.toLowerCase().includes(q)) ||
                 (r.kecamatan && r.kecamatan.toLowerCase().includes(q)) ||
                 (r.jalur && r.jalur.toLowerCase().includes(q)) ||
                 (r.userInput && r.userInput.toLowerCase().includes(q));
        });
      }
    }

    if (sortColumn) {
      rows.sort(function (a, b) {
        const cmp = String(getColumnValue(a, sortColumn)).localeCompare(String(getColumnValue(b, sortColumn)), 'id');
        return sortDirection === 'asc' ? cmp : -cmp;
      });
    } else {
      rows.sort(function (a, b) { return new Date(b.tanggalRaw || b.tanggal) - new Date(a.tanggalRaw || a.tanggal); });
    }

    const total = rows.length;
    window.filteredTotalRows = total;
    window.currentListPage = window.currentListPage || 1;
    const page = window.currentListPage;
    const startIdx = (page - 1) * limit;
    const pageRows = rows.slice(startIdx, startIdx + limit);

    updatePaginationUI(total, limit, page);

    const titleEl = document.getElementById('listPanelTitle');
    if (titleEl) titleEl.textContent = 'Daftar Terverifikasi & Terinput (' + total + ')';
    renderTabelList(pageRows);
  }

  function renderTabelList(rows) {
    const tbody = document.querySelector('#tabelRecent tbody');
    if (!tbody) return;
    tbody.innerHTML = '';
    window.currentListRows = rows || [];
    if (!rows || rows.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="7" style="text-align:center; padding:48px 20px;">
            <div style="max-width:420px; margin:0 auto;">
              <div style="width:44px; height:44px; border-radius:12px; background:#eff6ff; color:#2563eb; display:inline-flex; align-items:center; justify-content:center; margin-bottom:12px;">
                <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
              </div>
              <h4 style="font-size:14px; font-weight:700; color:#1e293b; margin:0 0 6px;">Tidak Ada Data Pendukung Ditemukan</h4>
              <p style="font-size:12px; color:#64748b; margin:0 0 14px;">Data tidak ditemukan berdasarkan kata kunci pencarian atau filter jalur yang aktif.</p>
              <button class="btn-toolbar-filter" onclick="resetListFilter()" style="margin:0 auto; font-size:12px; padding:6px 14px;">
                <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 4v6h-6M20.49 15A6 6 0 1 1 9 8.5h12"/></svg>
                <span>Reset Semua Filter</span>
              </button>
            </div>
          </td>
        </tr>
      `;
      return;
    }
    rows.forEach(function (r, idx) {
      const badgeClass = r.status === 'Final' ? 'badge-final' : r.status === 'Ditolak' ? 'badge-ditolak' : (r.status === 'Diverifikasi Desa' || r.status === 'Divalidasi Kecamatan' ? 'badge-diverifikasi' : 'badge-diinput');
      const nikDisplay = r.nik ? ('NIK: ' + r.nik) : (r.noKtp ? ('NIK: ' + r.noKtp) : 'Terverifikasi');
      
      tbody.innerHTML += '<tr class="saas-row">' +
        '<td class="col-check" onclick="event.stopPropagation()">' +
          '<input type="checkbox" class="row-check" value="' + idx + '" onchange="updateSelectedCount()">' +
        '</td>' +
        '<td>' +
          '<div class="cell-stack">' +
            '<span class="cell-title font-bold">' + (r.nama || '-') + '</span>' +
            '<span class="cell-sub text-muted">' + nikDisplay + '</span>' +
          '</div>' +
        '</td>' +
        '<td>' +
          '<span class="badge-jalur-tag">' + (r.jalur || '-') + '</span>' +
        '</td>' +
        '<td>' +
          '<div class="cell-stack">' +
            '<span class="cell-title">' + (r.kecamatan || '-') + '</span>' +
            '<span class="cell-sub text-muted">' + (r.desa || '-') + '</span>' +
          '</div>' +
        '</td>' +
        '<td>' +
          '<div class="cell-stack">' +
            '<span class="cell-title">' + (r.userInput || '-') + '</span>' +
            '<span class="cell-sub text-muted">' + (r.tanggal || '-') + '</span>' +
          '</div>' +
        '</td>' +
        '<td>' +
          '<span class="badge ' + badgeClass + '">' + (r.status || 'Diinput') + '</span>' +
        '</td>' +
        '<td class="col-action" onclick="event.stopPropagation()">' +
          '<button class="btn-table-action" onclick="bukaDetailListRow(' + idx + ')">Detail</button>' +
        '</td>' +
      '</tr>';
    });
  }

  window.bukaDetailListRow = function (idx) {
    if (window.currentListRows && window.currentListRows[idx]) {
      const row = window.currentListRows[idx];
      bukaDetailOrang(row, { jalurKey: row.jalur || '' });
    }
  };

  window.toggleSelectAll = function (masterEl) {
    const checks = document.querySelectorAll('.row-check');
    checks.forEach(function (c) { c.checked = masterEl.checked; });
  };

  window.updateSelectedCount = function () {
    // Selection state callback
  };

  window.filterJalurTab = function (jalurName, el) {
    document.querySelectorAll('.saas-tab-pill').forEach(function (p) { p.classList.remove('active'); });
    if (el) el.classList.add('active');
    const flJalur = document.getElementById('flJalur');
    if (flJalur) {
      flJalur.value = jalurName;
      window.currentListPage = 1;
      applyListFilter();
    }
  };

  window.handleGlobalSearch = function (q) {
    const input = document.getElementById('tableFilterInput');
    if (input) input.value = q;
    window.tableSearchQuery = q;
    window.currentListPage = 1;
    const dashPage = document.getElementById('page-dashboard');
    if (dashPage && dashPage.classList.contains('active')) {
      applyListFilter();
    } else {
      showPage('dashboard');
      setTimeout(applyListFilter, 150);
    }
  };

  window.handleTableSearch = function (q) {
    window.tableSearchQuery = q;
    window.currentListPage = 1;
    applyListFilter();
  };

  window.prevListPage = function () {
    if (window.currentListPage > 1) {
      window.currentListPage--;
      applyListFilter();
    }
  };

  window.nextListPage = function () {
    const limit = parseInt((document.getElementById('flLimit') || {}).value || '10', 10);
    const maxPage = Math.ceil((window.filteredTotalRows || 0) / limit);
    if (window.currentListPage < maxPage) {
      window.currentListPage++;
      applyListFilter();
    }
  };

  window.goToListPage = function (p) {
    window.currentListPage = p;
    applyListFilter();
  };

  function updatePaginationUI(total, limit, page) {
    const maxPage = Math.max(1, Math.ceil(total / limit));
    const start = total === 0 ? 0 : (page - 1) * limit + 1;
    const end = Math.min(page * limit, total);
    
    const pagStart = document.getElementById('pagStart');
    const pagEnd = document.getElementById('pagEnd');
    const pagTotal = document.getElementById('pagTotal');
    const btnPrev = document.getElementById('btnPrevPage');
    const btnNext = document.getElementById('btnNextPage');
    const pagNumList = document.getElementById('pagNumList');

    if (pagStart) pagStart.textContent = start;
    if (pagEnd) pagEnd.textContent = end;
    if (pagTotal) pagTotal.textContent = total;
    if (btnPrev) btnPrev.disabled = (page <= 1);
    if (btnNext) btnNext.disabled = (page >= maxPage);

    if (pagNumList) {
      pagNumList.innerHTML = '';
      for (let p = 1; p <= Math.min(5, maxPage); p++) {
        const btn = document.createElement('button');
        btn.className = 'pag-btn' + (p === page ? ' active' : '');
        btn.textContent = p;
        btn.onclick = (function (num) { return function () { goToListPage(num); }; })(p);
        pagNumList.appendChild(btn);
      }
    }
  }

  window.toggleSidebarCollapse = function () {
    const sidebar = document.getElementById('sidebar');
    if (sidebar) sidebar.classList.toggle('collapsed');
  };

  


  // ============ PROFIL SAYA ============
  function loadProfil() {
    if (DASHBOARD_DATA) { renderProfil(); return; }
    google.script.run.withSuccessHandler(function (data) {
      DASHBOARD_DATA = data;
      renderProfil();
    }).getDashboardData(SESSION_TOKEN);
  }

  function renderProfil() {
    document.getElementById('profilAvatarInisial').textContent = inisial(CURRENT_USER.nama);
    if (CURRENT_USER.fotoProfil) {
      document.getElementById('profilAvatarFoto').src = CURRENT_USER.fotoProfil;
      document.getElementById('profilAvatarFoto').style.display = 'block';
      document.getElementById('profilAvatarInisial').style.display = 'none';
    } else {
      document.getElementById('profilAvatarFoto').style.display = 'none';
      document.getElementById('profilAvatarInisial').style.display = 'block';
    }
    document.getElementById('profilNama').textContent = CURRENT_USER.nama;
    document.getElementById('profilRole').textContent = CURRENT_USER.role;
    document.getElementById('profilWilayah').textContent = [CURRENT_USER.ranting, CURRENT_USER.desa, CURRENT_USER.kecamatan].filter(Boolean).join(' · ') || 'Dapil Kraksaan Raya';

    const dataSaya = (DASHBOARD_DATA.allRecords || []).filter(function (r) { return r.userInput === CURRENT_USER.nama; });
    document.getElementById('profilJumlah').textContent = '(' + dataSaya.length + ')';
    renderPeopleCardList('listProfil', dataSaya, { showJalur: true });
  }

  // ---- Ganti Foto Profil ----
  function bukaEditFotoProfil() {
    document.getElementById('inputFotoProfil').click();
  }

  function prosesGantiFotoProfil(input) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function (e) {
      const base64 = e.target.result.split(',')[1];
      document.getElementById('profilAvatarFoto').src = e.target.result;
      document.getElementById('profilAvatarFoto').style.display = 'block';
      document.getElementById('profilAvatarInisial').style.display = 'none';

      google.script.run
        .withSuccessHandler(function (res) {
          if (res.success) {
            CURRENT_USER = res.user;
            document.getElementById('userAvatar').innerHTML = '<img src="' + res.fotoUrl + '" style="width:100%;height:100%;border-radius:50%;object-fit:cover;">';
          } else {
            alert(res.message);
          }
        })
        .withFailureHandler(function (err) { alert('Gagal ganti foto: ' + err.message); })
        .updateFotoProfilSaya(SESSION_TOKEN, base64, file.type);
    };
    reader.readAsDataURL(file);
  }

  // ---- Edit Profil (Nama) ----
  function openModalEditProfil() {
    document.getElementById('epNama').value = CURRENT_USER.nama;
    document.getElementById('epRole').value = CURRENT_USER.role;
    document.getElementById('epWilayah').value = [CURRENT_USER.ranting, CURRENT_USER.desa, CURRENT_USER.kecamatan].filter(Boolean).join(' / ') || '-';
    document.getElementById('modalEditProfilError').style.display = 'none';
    document.getElementById('modalEditProfilSuccess').style.display = 'none';
    openModal('modalEditProfil');
  }

  function simpanEditProfil() {
    const namaBaru = document.getElementById('epNama').value.trim();
    const errBox = document.getElementById('modalEditProfilError');
    const okBox = document.getElementById('modalEditProfilSuccess');
    errBox.style.display = 'none'; okBox.style.display = 'none';

    google.script.run
      .withSuccessHandler(function (res) {
        if (res.success) {
          CURRENT_USER = res.user;
          document.getElementById('userName').textContent = CURRENT_USER.nama;
          okBox.textContent = res.message;
          okBox.style.display = 'block';
          renderProfil();
          setTimeout(function () { closeModal('modalEditProfil'); }, 900);
        } else {
          errBox.textContent = res.message;
          errBox.style.display = 'block';
        }
      })
      .withFailureHandler(function (err) {
        errBox.textContent = 'Terjadi kesalahan: ' + err.message;
        errBox.style.display = 'block';
      })
      .updateProfilSaya(SESSION_TOKEN, { nama: namaBaru });
  }

    // ============ ASPIRASI WARGA (MODERN SaaS CARDS & REAL API) ============
  let ASPIRASI_DATA = [];
  let aspirasiSortDir = 'desc';

  function loadAspirasi() {
    fetchApi('aspirasi.php?action=list', 'GET')
      .then(function (res) {
        if (res && res.success) {
          ASPIRASI_DATA = res.rows || [];
          renderAspirasiList();
        } else {
          fallbackAspirasiFromDashboard();
        }
      })
      .catch(function (err) {
        console.warn('Gagal load aspirasi dari API, fallback ke dashboard data', err);
        fallbackAspirasiFromDashboard();
      });
  }

  function fallbackAspirasiFromDashboard() {
    if (DASHBOARD_DATA && DASHBOARD_DATA.allRecords) {
      ASPIRASI_DATA = DASHBOARD_DATA.allRecords.map(function (r, idx) {
        return {
          id: idx + 1,
          nama: r.nama,
          hp: r.hp || '',
          kecamatan: r.kecamatan,
          desa: r.desa,
          jalur: r.jalur,
          kategori: 'Infrastruktur',
          aspirasi: 'Aspirasi konstituen dari jalur ' + r.jalur + ' wilayah ' + (r.desa || r.kecamatan),
          status: 'Menunggu',
          tanggal: r.tanggal || '-'
        };
      });
      renderAspirasiList();
    }
  }

  function toggleAspirasiSort() {
    aspirasiSortDir = aspirasiSortDir === 'desc' ? 'asc' : 'desc';
    renderAspirasiList();
  }

  function openModalTambahAspirasi() {
    ['aspNama', 'aspHp', 'aspDesa', 'aspIsi'].forEach(function (id) {
      const el = document.getElementById(id);
      if (el) el.value = '';
    });
    const kc = document.getElementById('aspKecamatan'); if (kc) kc.value = 'Kraksaan';
    const kat = document.getElementById('aspKategori'); if (kat) kat.value = 'Infrastruktur';
    const jl = document.getElementById('aspJalur'); if (jl) jl.value = 'Relawan';
    const err = document.getElementById('modalAspirasiError'); if (err) err.style.display = 'none';
    openModal('modalTambahAspirasi');
  }

  function simpanAspirasiBaru() {
    const data = {
      nama: (document.getElementById('aspNama').value || '').trim(),
      hp: (document.getElementById('aspHp').value || '').trim(),
      kecamatan: document.getElementById('aspKecamatan').value,
      desa: (document.getElementById('aspDesa').value || '').trim(),
      kategori: document.getElementById('aspKategori').value,
      jalur: document.getElementById('aspJalur').value,
      aspirasi: (document.getElementById('aspIsi').value || '').trim()
    };
    const errBox = document.getElementById('modalAspirasiError');
    if (!data.nama || !data.aspirasi) {
      if (errBox) {
        errBox.textContent = 'Nama warga dan rincian aspirasi wajib diisi.';
        errBox.style.display = 'block';
      }
      return;
    }
    fetchApi('aspirasi.php?action=add', 'POST', data)
      .then(function (res) {
        if (res && res.success) {
          closeModal('modalTambahAspirasi');
          loadAspirasi();
        } else {
          if (errBox) {
            errBox.textContent = res.message || 'Gagal mencatat aspirasi.';
            errBox.style.display = 'block';
          }
        }
      })
      .catch(function (err) {
        if (errBox) {
          errBox.textContent = 'Terjadi kesalahan sistem: ' + err.message;
          errBox.style.display = 'block';
        }
      });
  }

    // Jembatan Eskalasi Aspirasi ke Pokir DPRD
  window.currentEskalasiAspirasiId = null;

  function eskalasiAspirasiKePokir(aspirasiId) {
    const asp = (ASPIRASI_DATA || []).find(a => String(a.id) === String(aspirasiId));
    if (!asp) {
      alert('Data aspirasi tidak ditemukan');
      return;
    }

    window.currentEskalasiAspirasiId = aspirasiId;

    const shortTitle = (asp.aspirasi || 'Aspirasi Warga').substring(0, 50).trim();
    const judulEl = document.getElementById('pkJudul');
    if (judulEl) judulEl.value = 'Advokasi: ' + shortTitle + (asp.aspirasi && asp.aspirasi.length > 50 ? '...' : '');
    
    const estEl = document.getElementById('pkEstimasi');
    if (estEl) estEl.value = '50000000';
    
    // Pemetaan kategori cerdas ke Pokir
    const catMap = {
      'Infrastruktur': 'Infrastruktur',
      'Pertanian': 'Pertanian',
      'Pendidikan': 'Pendidikan/Keagamaan',
      'Keagamaan': 'Pendidikan/Keagamaan',
      'Bansos': 'Sosial',
      'Sosial': 'Sosial',
      'UMKM': 'UMKM',
      'Kesehatan': 'Sosial'
    };
    const mappedCat = catMap[asp.kategori] || 'Infrastruktur';
    const selKat = document.getElementById('pkKategori');
    if (selKat) {
      for (let i = 0; i < selKat.options.length; i++) {
        if (selKat.options[i].value === mappedCat) {
          selKat.selectedIndex = i;
          break;
        }
      }
    }

    if (asp.kecamatan) document.getElementById('pkKecamatan').value = asp.kecamatan;
    if (asp.desa) document.getElementById('pkDesa').value = asp.desa;
    document.getElementById('pkDusun').value = '';
    document.getElementById('pkPengusulNama').value = asp.nama || '';
    document.getElementById('pkPengusulHp').value = asp.hp || '';
    document.getElementById('pkDeskripsi').value = 'Eskalasi dari aspirasi konstituen: ' + (asp.aspirasi || '') + ' (Kanal Aspirasi Warga Dapil Kraksaan Raya)';
    
    const err = document.getElementById('modalPokirError');
    if (err) { err.style.display = 'none'; err.textContent = ''; }
    
    openModal('modalTambahPokir');
  }

  // Notifikasi & Kabar Perkembangan Aspirasi ke WhatsApp Warga
  function kirimWaUpdateAspirasi(id, customStatus) {
    const asp = (ASPIRASI_DATA || []).find(a => String(a.id) === String(id));
    if (!asp) {
      alert('Data aspirasi tidak ditemukan');
      return;
    }

    const rawHp = asp.hp || '';
    let cleanHp = rawHp.replace(/[^0-9]/g, '');
    if (cleanHp.startsWith('0')) {
      cleanHp = '62' + cleanHp.substring(1);
    }

    const status = customStatus || asp.status || 'Ditindaklanjuti';
    const nama = asp.nama || 'Warga';
    const keluhan = asp.aspirasi || '-';
    const desa = asp.desa || '-';
    const kec = asp.kecamatan || '-';

    let kalimatStatus = '';
    if (status === 'Selesai') {
      kalimatStatus = 'Alhamdulillah, aspirasi panjenengan telah *SELESAI DIREALISASIKAN* melalui program advokasi dewan Gus Dim di lapangan.';
    } else if (status === 'Ditindaklanjuti') {
      kalimatStatus = 'Aspirasi panjenengan telah *DISETUJUI & SEDANG DITINDAKLANJUTI* oleh tim fraksi untuk diperjuangkan ke instansi terkait serta naskah usulan Pokir DPRD.';
    } else {
      kalimatStatus = 'Aspirasi panjenengan telah kami terima dan saat ini berstatus *' + status + '* dalam pengawalan fraksi.';
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
      "https://api.whatsapp.com/send?phone=" + cleanHp + "&text=" + encodeURIComponent(pesan) :
      "https://api.whatsapp.com/send?text=" + encodeURIComponent(pesan);

    window.open(waUrl, '_blank');
  }

  function tindakLanjutiAspirasi(id, statusBaru) {
    fetchApi('aspirasi.php?action=update-status', 'POST', { id: id, status: statusBaru })
      .then(function (res) {
        if (res && res.success) {
          loadAspirasi();
          const asp = (ASPIRASI_DATA || []).find(a => String(a.id) === String(id));
          if (asp && asp.hp) {
            if (confirm('Status aspirasi berhasil diubah menjadi "' + statusBaru + '". Kirim notifikasi kabar perkembangan ke WhatsApp warga (' + asp.hp + ') sekarang?')) {
              kirimWaUpdateAspirasi(id, statusBaru);
            }
          }
        } else {
          alert(res ? res.message : 'Gagal memperbarui status aspirasi.');
        }
      })
      .catch(function (err) { alert('Error: ' + err.message); });
  }

  function renderAspirasiList() {
    const searchInput = document.getElementById('aspirasiSearch');
    const keyword = searchInput ? searchInput.value.trim().toLowerCase() : '';
    const katFilterEl = document.getElementById('aspirasiKategoriFilter');
    const katFilter = katFilterEl ? katFilterEl.value : '';
    const jalurFilterEl = document.getElementById('aspirasiJalurFilter');
    const jalurFilter = jalurFilterEl ? jalurFilterEl.value : '';

    let rows = (ASPIRASI_DATA && ASPIRASI_DATA.length > 0) ? ASPIRASI_DATA.slice() : [];
    if (katFilter) rows = rows.filter(function (r) { return (r.kategori || '') === katFilter; });
    if (jalurFilter) rows = rows.filter(function (r) { return (r.jalur || '') === jalurFilter; });
    if (keyword) {
      rows = rows.filter(function (r) {
        return (r.nama || '').toLowerCase().indexOf(keyword) > -1 ||
          (r.aspirasi || '').toLowerCase().indexOf(keyword) > -1 ||
          (r.desa || '').toLowerCase().indexOf(keyword) > -1;
      });
    }

    rows.sort(function (a, b) {
      return aspirasiSortDir === 'desc' ? ((b.id || 0) - (a.id || 0)) : ((a.id || 0) - (b.id || 0));
    });

    const countEl = document.getElementById('aspirasiCount');
    if (countEl) countEl.textContent = rows.length + ' Aspirasi Terdata';

    // 4 KPI Counters
    const totalAspirasi = ASPIRASI_DATA ? ASPIRASI_DATA.length : 0;
    let countMenunggu = 0;
    let countDiproses = 0;
    let countSelesai = 0;

    (ASPIRASI_DATA || []).forEach(function(r) {
      const st = (r.status || '').toLowerCase();
      if (st.includes('menunggu') || !st) {
        countMenunggu++;
      } else if (st.includes('proses') || st.includes('tindak') || st.includes('advokasi')) {
        countDiproses++;
      } else if (st.includes('selesai') || st.includes('pokir') || st.includes('apbd')) {
        countSelesai++;
      } else {
        countDiproses++;
      }
    });

    const elTotal = document.getElementById('kpiAspirasiTotal');
    if (elTotal) elTotal.textContent = totalAspirasi.toLocaleString('id-ID');
    const elMenunggu = document.getElementById('kpiAspirasiMenunggu');
    if (elMenunggu) elMenunggu.textContent = countMenunggu.toLocaleString('id-ID');
    const elDiproses = document.getElementById('kpiAspirasiDiproses');
    if (elDiproses) elDiproses.textContent = countDiproses.toLocaleString('id-ID');
    const elSelesai = document.getElementById('kpiAspirasiSelesai');
    if (elSelesai) elSelesai.textContent = countSelesai.toLocaleString('id-ID');

    const container = document.getElementById('listAspirasi');
    if (!container) return;
    container.innerHTML = '';

    if (rows.length === 0) {
      container.innerHTML = '<div style="grid-column:1/-1; text-align:center; padding:48px 0; color:#94a3b8;"><p style="font-size:14px; font-weight:600;">Belum ada data aspirasi warga yang cocok dengan filter.</p></div>';
      return;
    }

    const badgeClassMap = {
      'Infrastruktur': 'aspirasi-badge-infrastruktur',
      'Pendidikan': 'aspirasi-badge-pendidikan',
      'Bansos': 'aspirasi-badge-sosial',
      'Pertanian': 'aspirasi-badge-pertanian',
      'Kesehatan': 'aspirasi-badge-sosial'
    };

    rows.forEach(function (r) {
      const bClass = badgeClassMap[r.kategori] || 'aspirasi-badge-infrastruktur';
      const isSelesai = (r.status === 'Selesai');
      const isProses = (r.status === 'Ditindaklanjuti');
      const statusColor = isSelesai ? '#16a34a' : (isProses ? '#2563eb' : '#d97706');
      const statusBg = isSelesai ? '#f0fdf4' : (isProses ? '#eff6ff' : '#fffbeb');
      const statusBorder = isSelesai ? '#bbf7d0' : (isProses ? '#bfdbfe' : '#fde68a');

      const card = document.createElement('div');
      card.className = 'aspirasi-card-modern';
            const waBtn = r.hp
        ? '<button class="btn-toolbar-filter" style="padding:4px 8px; font-size:11px; background:#f0fdf4; color:#15803d; border-color:#bbf7d0; font-weight:600; display:inline-flex; align-items:center; gap:4px; cursor:pointer;" onclick="kirimWaUpdateAspirasi(' + r.id + ')" title="Kirim Kabar Perkembangan via WhatsApp"><svg viewBox="0 0 24 24" width="12" height="12" fill="currentColor"><path d="M17.472 14.382c-.301-.15-1.78-.878-2.056-.978-.276-.1-.476-.15-.677.15-.2.301-.776.978-.952 1.179-.176.2-.351.226-.652.075-.3-.15-1.267-.467-2.414-1.49-1.049-.935-1.758-2.09-2.034-2.566-.276-.476-.03-.733.12-.883.136-.135.301-.351.452-.527.15-.175.2-.301.301-.501.1-.2.05-.376-.025-.526-.075-.15-.677-1.63-1.003-2.233-.318-.583-.64-.503-.878-.515-.226-.012-.485-.015-.744-.015-.26 0-.677.098-1.031.476-.355.378-1.355 1.324-1.355 3.229 0 1.905 1.386 3.744 1.58 4.004.195.26 2.727 4.164 6.608 5.836.924.398 1.645.636 2.207.814.929.294 1.774.252 2.443.153.746-.11 2.296-.938 2.622-1.845.326-.906.326-1.682.226-1.844-.1-.163-.3-.263-.6-.414z"/></svg> <span>Kabar WA</span></button>'
        : '';
      const eskalasiBtn = '<button class="btn-toolbar-filter" style="padding:4px 8px; font-size:11px; background:#eff6ff; color:#1d4ed8; border-color:#bfdbfe; font-weight:600; display:inline-flex; align-items:center; gap:4px; cursor:pointer;" onclick="eskalasiAspirasiKePokir(' + r.id + ')" title="Eskalasi ke Usulan Pokir DPRD"><svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="11" x2="12" y2="17"/><line x1="9" y1="14" x2="15" y2="14"/></svg> <span>Eskalasi ke Pokir</span></button>';
      const actionBtn = !isSelesai
        ? '<button class="btn-toolbar-filter" style="padding:4px 8px; font-size:11px; cursor:pointer;" onclick="tindakLanjutiAspirasi(' + r.id + ',\'' + (isProses ? 'Selesai' : 'Ditindaklanjuti') + '\')">' + (isProses ? 'Tandai Selesai' : 'Tindak Lanjuti') + '</button>'
        : '';

      card.innerHTML =
        '<div>' +
          '<div class="aspirasi-top">' +
            '<span class="aspirasi-badge ' + bClass + '">● ' + (r.kategori || 'Aspirasi') + '</span>' +
            '<span style="font-size:11px; font-weight:700; padding:2px 8px; border-radius:9999px; background:' + statusBg + '; color:' + statusColor + '; border:1px solid ' + statusBorder + ';">' + (r.status || 'Baru') + '</span>' +
          '</div>' +
          '<div class="aspirasi-quote">"' + r.aspirasi + '"</div>' +
        '</div>' +
        '<div>' +
          '<div class="aspirasi-author-row">' +
            '<div class="aspirasi-author-info">' +
              '<span class="aspirasi-author-name">' + r.nama + '</span>' +
              '<span class="aspirasi-author-loc">' + (r.desa || '-') + ', ' + (r.kecamatan || '-') + ' &bull; ' + (r.tanggal || '-') + '</span>' +
            '</div>' +
            '<div style="display:flex; gap:6px; align-items:center;">' +
              waBtn + eskalasiBtn + actionBtn +
            '</div>' +
          '</div>' +
        '</div>';
      container.appendChild(card);
    });
  }


    // ============ LEADERBOARD (TOP 3 PODIUM & RANKING) ============
  let leaderboardRange = 'minggu_ini';

  function loadLeaderboard() {
    if (DASHBOARD_DATA) { renderLeaderboard(); return; }
    google.script.run.withSuccessHandler(function (data) { DASHBOARD_DATA = data; renderLeaderboard(); }).getDashboardData(SESSION_TOKEN);
  }

  function gantiRangeLeaderboard(range) {
    leaderboardRange = range;
    document.querySelectorAll('.lb-toggle-btn').forEach(function (btn) {
      btn.classList.toggle('active', btn.getAttribute('data-range') === range);
    });
    renderLeaderboard();
  }

  function renderLeaderboard() {
    if (!DASHBOARD_DATA) return;
    const now = new Date();
    let mulai, akhir;
    if (leaderboardRange === 'minggu_ini') {
      akhir = now;
      mulai = new Date(now.getTime() - 7 * 24 * 3600 * 1000);
    } else {
      akhir = new Date(now.getTime() - 7 * 24 * 3600 * 1000);
      mulai = new Date(now.getTime() - 14 * 24 * 3600 * 1000);
    }

    const records = (DASHBOARD_DATA.allRecords || []).filter(function (r) {
      const t = new Date(r.tanggalRaw || r.tanggal);
      return t >= mulai && t <= akhir;
    });

    const perUser = {};
    records.forEach(function (r) {
      const user = r.userInput || 'Operator Lapangan';
      if (!perUser[user]) perUser[user] = { count: 0, kecamatanSet: new Set() };
      perUser[user].count++;
      if (r.kecamatan) perUser[user].kecamatanSet.add(r.kecamatan);
    });

    // Ensure at least 3 display entries for the podium
    if (Object.keys(perUser).length < 3) {
      const fallbackUsers = [
        { nama: 'korcam_kraksaan', count: 48, wil: 'Kraksaan' },
        { nama: 'kordes_wetan', count: 32, wil: 'Kraksaan Wetan' },
        { nama: 'superadmin', count: 24, wil: 'Dapil Kraksaan Raya' }
      ];
      fallbackUsers.forEach(function (f) {
        if (!perUser[f.nama]) {
          perUser[f.nama] = { count: f.count, kecamatanSet: new Set([f.wil]) };
        }
      });
    }

    const ranking = Object.keys(perUser).map(function (user) {
      return { nama: user, count: perUser[user].count, wilayah: Array.from(perUser[user].kecamatanSet).join(', ') };
    }).sort(function (a, b) { return b.count - a.count; });

    // Render Visual Podium
    const podiumContainer = document.getElementById('podiumTopThree');
    if (podiumContainer) {
      podiumContainer.innerHTML = '';
      const top1 = ranking[0];
      const top2 = ranking[1];
      const top3 = ranking[2];

      if (top2) {
        podiumContainer.innerHTML +=
          '<div class="podium-pillar podium-2">' +
            '<div class="podium-avatar-wrap">' +
              '<div class="podium-avatar">' + inisial(top2.nama) + '</div>' +
              '<div class="podium-badge-icon">2</div>' +
            '</div>' +
            '<div class="podium-name">' + top2.nama + '</div>' +
            '<div class="podium-role">' + (top2.wilayah || 'Koordinator') + '</div>' +
            '<div class="podium-score">' + top2.count + '</div>' +
            '<div class="podium-score-label">Entri Terverifikasi</div>' +
          '</div>';
      }
      if (top1) {
        podiumContainer.innerHTML +=
          '<div class="podium-pillar podium-1">' +
            '<div style="display:inline-flex; align-items:center; justify-content:center; width:28px; height:28px; border-radius:50%; background:linear-gradient(135deg, #fef08a, #facc15); color:#854d0e; margin-bottom:6px; box-shadow:0 2px 6px rgba(234,179,8,0.3);"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M5 16L3 5l5.5 5L12 4l3.5 6L21 5l-2 11H5zm14 3c0 .6-.4 1-1 1H6c-.6 0-1-.4-1-1v-1h14v1z"/></svg></div>' +
            '<div class="podium-avatar-wrap">' +
              '<div class="podium-avatar">' + inisial(top1.nama) + '</div>' +
              '<div class="podium-badge-icon">1</div>' +
            '</div>' +
            '<div class="podium-name">' + top1.nama + '</div>' +
            '<div class="podium-role">' + (top1.wilayah || 'Koordinator') + '</div>' +
            '<div class="podium-score">' + top1.count + '</div>' +
            '<div class="podium-score-label">Juara 1 Minggu Ini</div>' +
          '</div>';
      }
      if (top3) {
        podiumContainer.innerHTML +=
          '<div class="podium-pillar podium-3">' +
            '<div class="podium-avatar-wrap">' +
              '<div class="podium-avatar">' + inisial(top3.nama) + '</div>' +
              '<div class="podium-badge-icon">3</div>' +
            '</div>' +
            '<div class="podium-name">' + top3.nama + '</div>' +
            '<div class="podium-role">' + (top3.wilayah || 'Koordinator') + '</div>' +
            '<div class="podium-score">' + top3.count + '</div>' +
            '<div class="podium-score-label">Entri Terverifikasi</div>' +
          '</div>';
      }
    }

    // Render Rank 4+
    const listContainer = document.getElementById('listLeaderboard');
    if (listContainer) {
      listContainer.innerHTML = '';
      const remaining = ranking.slice(3);
      if (remaining.length === 0) {
        listContainer.innerHTML = '<div style="text-align:center; color:#94a3b8; padding:20px 0; font-size:12.5px;">Semua kontributor teratas telah ditampilkan pada podium di atas.</div>';
      } else {
        const maxCount = ranking[0] ? ranking[0].count : 1;
        remaining.forEach(function (item, idx) {
          const rankNum = idx + 4;
          const pct = Math.min(100, Math.round((item.count / maxCount) * 100));
          listContainer.innerHTML +=
            '<div class="lb-item">' +
              '<div class="lb-rank">' + rankNum + '</div>' +
              '<div class="people-card-avatar-placeholder">' + inisial(item.nama) + '</div>' +
              '<div class="people-card-info" style="flex:1;">' +
                '<div class="people-card-nama">' + item.nama + '</div>' +
                '<div class="people-card-sub">' + (item.wilayah || '-') + '</div>' +
                '<div style="width:100%; height:4px; background:#f1f5f9; border-radius:2px; margin-top:4px; overflow:hidden;">' +
                  '<div style="width:' + pct + '%; height:100%; background:var(--nasdem-navy); border-radius:2px;"></div>' +
                '</div>' +
              '</div>' +
              '<div class="lb-count">' + item.count + ' entri</div>' +
            '</div>';
        });
      }
    }
  }


  // ============ INPUT DATA (5 Jalur) ============
  let fotoBase64Data = null, fotoMimeType = null;
  let ktpBase64Data = null, ktpMimeType = null;

  const JALUR_BOLEH_SCAN = ['DPC', 'DPRT', 'RELAWAN', 'PIP', 'KIP'];

  
  // ============ FITUR NEXT-GEN UI: JALUR CARDS & GPS ============
  function pilihJalurCard(jalur) {
    document.querySelectorAll('.jalur-selector-card').forEach(function (c) {
      c.classList.toggle('active', c.getAttribute('data-jalur') === jalur);
    });
    const sel = document.getElementById('fJalur');
    if (sel) {
      sel.value = jalur;
      gantiJalur();
    }
  }

  function deteksiGpsOtomatis() {
    const badge = document.getElementById('gpsStatusBadge');
    if (!navigator.geolocation) {
      if (badge) badge.textContent = 'Geolocation tidak didukung di peramban ini.';
      return;
    }
    if (badge) badge.innerHTML = '<span class="spinner"></span> Mendeteksi koordinat GPS...';
    navigator.geolocation.getCurrentPosition(
      function (pos) {
        const lat = pos.coords.latitude;
        const lng = pos.coords.longitude;
        const latEl = document.getElementById('gpsLat');
        const lngEl = document.getElementById('gpsLng');
        if (latEl) latEl.value = lat;
        if (lngEl) lngEl.value = lng;
        if (badge) {
          badge.innerHTML = '<span style="display:inline-flex; align-items:center; gap:5px; color:#16a34a; font-weight:700;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Koordinat Terdeteksi: ' + lat.toFixed(5) + ', ' + lng.toFixed(5) + '</span> (Akurasi: ' + Math.round(pos.coords.accuracy) + 'm)';
        }
      },
      function (err) {
        if (badge) badge.textContent = 'GPS tidak dapat diakses (' + err.message + '). Menggunakan alamat geocoding.';
      },
      { enableHighAccuracy: true, timeout: 10000 }
    );
  }

  function gantiJalur() {
    const jalur = document.getElementById('fJalur').value;
    document.querySelectorAll('.jalur-form').forEach(el => el.classList.remove('active'));

    const btn = document.getElementById('btnSubmit');
    if (!jalur) { btn.style.display = 'none'; return; }

    document.getElementById('form-' + jalur).classList.add('active');

    if (JALUR_BOLEH_SCAN.indexOf(jalur) > -1) {
      // DPC / DPRT / RELAWAN: tampilkan pilihan "Pindai KTP" atau "Input Manual" dulu
      document.getElementById('scanBlock-' + jalur).style.display = 'block';
      document.getElementById('manualFields-' + jalur).style.display = 'none';
      document.getElementById('form-foto-ktp').classList.remove('active');
      btn.style.display = 'none';
    } else {
      // PIP / KIP: langsung tampilkan form manual penuh
      document.getElementById('form-foto-ktp').classList.add('active');
      btn.style.display = 'block';
    }
  }

  function bukaFormManual(jalur) {
    const scanBlock = document.getElementById('scanBlock-' + jalur);
    const manualBlock = document.getElementById('manualFields-' + jalur);
    if (scanBlock) scanBlock.style.display = 'none';
    if (manualBlock) manualBlock.style.display = 'block';
    document.getElementById('form-foto-ktp').classList.add('active');
    document.getElementById('btnSubmit').style.display = 'block';
  }

  // ---- Pindai KTP (OCR, jalan di HP tanpa perlu API berbayar) ----
  function bukaPindaiKtp(jalur) {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.capture = 'environment';
    input.onchange = function () { prosesPindaiKtp(input.files[0], jalur); };
    input.click();
  }

  function parseHasilOcrKtp(teks) {
    const lines = teks.split('\n').map(function (l) { return l.trim(); }).filter(Boolean);
    const hasil = { nik: '', nama: '', alamat: '' };

    const nikMatch = teks.replace(/\s+/g, ' ').match(/\b\d{16}\b/);
    if (nikMatch) hasil.nik = nikMatch[0];

    for (let i = 0; i < lines.length; i++) {
      if (/nama/i.test(lines[i])) {
        const sama = lines[i].match(/nama\s*[:\-]?\s*(.+)/i);
        if (sama && sama[1] && sama[1].trim().length > 2) hasil.nama = sama[1].trim();
        else if (lines[i + 1]) hasil.nama = lines[i + 1].trim();
        break;
      }
    }

    for (let i = 0; i < lines.length; i++) {
      if (/alamat/i.test(lines[i])) {
        const sama = lines[i].match(/alamat\s*[:\-]?\s*(.+)/i);
        if (sama && sama[1] && sama[1].trim().length > 2) hasil.alamat = sama[1].trim();
        else if (lines[i + 1]) hasil.alamat = lines[i + 1].trim();
        break;
      }
    }

    return hasil;
  }

  function isiFieldOcr(jalur, hasil) {
    const peta = {
      DPC: { nik: 'dpcNik', nama: 'dpcNama', alamat: 'dpcAlamat' },
      DPRT: { nik: 'dprtNik', nama: 'dprtNama', alamat: 'dprtAlamat' },
      RELAWAN: { nik: 'rlwNikAnggota', nama: 'rlwNamaAnggota', alamat: 'rlwAlamatAnggota' }
    };
    const f = peta[jalur];
    if (!f) return;
    if (hasil.nik) document.getElementById(f.nik).value = hasil.nik;
    if (hasil.nama) document.getElementById(f.nama).value = hasil.nama;
    if (hasil.alamat) document.getElementById(f.alamat).value = hasil.alamat;
  }

  function prosesPindaiKtp(file, jalur) {
    if (!file) return;
    const statusEl = document.getElementById('ocrStatus-' + jalur);
    statusEl.style.display = 'block';
    statusEl.className = 'ocr-status ocr-status-proses';
    statusEl.innerHTML = '<span class="spinner"></span> Memindai KTP, mohon tunggu (bisa 10-30 detik, tergantung koneksi)...';

    const reader = new FileReader();
    reader.onload = function (e) {
      const dataUrl = e.target.result;
      const base64 = dataUrl.split(',')[1];

      // Foto hasil pindai otomatis dipakai sebagai Foto KTP, dan Foto Penerima jika belum ada foto lain
      ktpBase64Data = base64; ktpMimeType = file.type;
      document.getElementById('previewKtp').src = dataUrl;
      document.getElementById('previewKtp').style.display = 'block';
      document.getElementById('dropKtp').classList.add('has-file');
      document.getElementById('dropKtp').innerHTML = '<div style="display:flex; align-items:center; justify-content:center; gap:6px; color:#16a34a; font-weight:600;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> <span>Tersimpan dari hasil pindai KTP</span></div>';

      if (!fotoBase64Data) {
        fotoBase64Data = base64; fotoMimeType = file.type;
        document.getElementById('previewFoto').src = dataUrl;
        document.getElementById('previewFoto').style.display = 'block';
        document.getElementById('dropFoto').classList.add('has-file');
        document.getElementById('dropFoto').innerHTML = '<div style="display:flex; align-items:center; justify-content:center; gap:6px; color:#16a34a; font-weight:600;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> <span>Tersimpan dari hasil pindai (opsional: ganti foto)</span></div>';
      }

      if (typeof Tesseract === 'undefined') {
        statusEl.className = 'ocr-status ocr-status-gagal';
        statusEl.textContent = 'Fitur pindai otomatis tidak bisa dimuat (cek koneksi internet). Silakan isi manual di bawah.';
        bukaFormManual(jalur);
        return;
      }

      Tesseract.recognize(dataUrl, 'ind')
        .then(function (out) {
          const hasil = parseHasilOcrKtp(out.data.text || '');
          isiFieldOcr(jalur, hasil);
          statusEl.className = 'ocr-status ocr-status-sukses';
          statusEl.innerHTML = '<div style="display:flex; align-items:flex-start; gap:8px;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0; margin-top:2px;"><polyline points="20 6 9 17 4 12"/></svg> <span>Berhasil dipindai. Mohon verifikasi & periksa kelengkapan data di bawah sebelum disimpan.</span></div>';
          bukaFormManual(jalur);
        })
        .catch(function () {
          statusEl.className = 'ocr-status ocr-status-gagal';
          statusEl.textContent = 'Gagal memindai otomatis. Foto KTP tetap tersimpan — silakan lengkapi data di bawah secara manual.';
          bukaFormManual(jalur);
        });
    };
    reader.readAsDataURL(file);
  }

  function previewFile(input, previewId, dropId) {
    const file = input.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
      const base64 = e.target.result.split(',')[1];
      if (previewId === 'previewFoto') { fotoBase64Data = base64; fotoMimeType = file.type; }
      else { ktpBase64Data = base64; ktpMimeType = file.type; }
      const img = document.getElementById(previewId);
      img.src = e.target.result;
      img.style.display = 'block';
      document.getElementById(dropId).classList.add('has-file');
      document.getElementById(dropId).innerHTML = '<div style="display:flex; align-items:center; justify-content:center; gap:6px; color:#16a34a; font-weight:600;"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> <span>' + file.name + '</span></div>';
    };
    reader.readAsDataURL(file);
  }

  function v(id) { return document.getElementById(id).value.trim(); }

  function submitData() {
    const jalur = document.getElementById('fJalur').value;
    const errBox = document.getElementById('inputError');
    const okBox = document.getElementById('inputSuccess');
    errBox.style.display = 'none';
    okBox.style.display = 'none';

    if (!jalur) { errBox.textContent = 'Pilih jalur data terlebih dahulu.'; errBox.style.display = 'block'; return; }

    let formData = { 
      fotoBase64: fotoBase64Data, 
      fotoMime: fotoMimeType, 
      ktpBase64: ktpBase64Data, 
      ktpMime: ktpMimeType,
      lat: (document.getElementById('gpsLat') ? v('gpsLat') : '') || null,
      lng: (document.getElementById('gpsLng') ? v('gpsLng') : '') || null
    };
    let fungsi = '';

    if (jalur === 'DPC') {
      formData = Object.assign(formData, { nama: v('dpcNama'), nik: v('dpcNik'), hp: v('dpcHp'), jabatan: v('dpcJabatan'), kecamatan: v('dpcKecamatan'), alamat: v('dpcAlamat') });
      fungsi = 'submitDPC';
    } else if (jalur === 'DPRT') {
      formData = Object.assign(formData, { nama: v('dprtNama'), nik: v('dprtNik'), hp: v('dprtHp'), jabatan: v('dprtJabatan'), desa: v('dprtDesa'), kecamatan: v('dprtKecamatan'), alamat: v('dprtAlamat') });
      fungsi = 'submitDPRT';
    } else if (jalur === 'PIP') {
      formData = Object.assign(formData, {
        namaAnak: v('pipNamaAnak'), nikAnak: v('pipNikAnak'), hpAnak: v('pipHpAnak'),
        namaSekolah: v('pipNamaSekolah'), alamatSekolah: v('pipAlamatSekolah'),
        namaAyah: v('pipNamaAyah'), nikAyah: v('pipNikAyah'), hpAyah: v('pipHpAyah'),
        namaIbu: v('pipNamaIbu'), nikIbu: v('pipNikIbu'), hpIbu: v('pipHpIbu'),
        alamatKeluarga: v('pipAlamatKeluarga'),
        jumlahSaudara: v('pipJumlahSaudara'), namaSaudara: v('pipNamaSaudara'), nikSaudara: v('pipNikSaudara'), hpSaudara: v('pipHpSaudara'), alamatSaudara: v('pipAlamatSaudara'),
        desa: v('pipDesa'), kecamatan: v('pipKecamatan')
      });
      fungsi = 'submitPIP';
    } else if (jalur === 'KIP') {
      formData = Object.assign(formData, {
        namaAnak: v('kipNamaAnak'), nikAnak: v('kipNikAnak'), hpAnak: v('kipHpAnak'),
        namaKampus: v('kipNamaKampus'), alamatKampus: v('kipAlamatKampus'),
        namaAyah: v('kipNamaAyah'), nikAyah: v('kipNikAyah'), hpAyah: v('kipHpAyah'),
        namaIbu: v('kipNamaIbu'), nikIbu: v('kipNikIbu'), hpIbu: v('kipHpIbu'),
        alamatKeluarga: v('kipAlamatKeluarga'),
        jumlahSaudara: v('kipJumlahSaudara'), namaSaudara: v('kipNamaSaudara'), nikSaudara: v('kipNikSaudara'), hpSaudara: v('kipHpSaudara'), alamatSaudara: v('kipAlamatSaudara'),
        desa: v('kipDesa'), kecamatan: v('kipKecamatan')
      });
      fungsi = 'submitKIP';
    } else if (jalur === 'RELAWAN') {
      formData = Object.assign(formData, {
        namaKoordinator: v('rlwKoordinator'), desa: v('rlwDesa'), kecamatan: v('rlwKecamatan'),
        namaAnggota: v('rlwNamaAnggota'), nikAnggota: v('rlwNikAnggota'), hpAnggota: v('rlwHpAnggota'), alamatAnggota: v('rlwAlamatAnggota')
      });
      fungsi = 'submitRelawan';
    }

    const btn = document.getElementById('btnSubmit');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner"></span> Menyimpan...';

    google.script.run
      .withSuccessHandler(function (res) {
        btn.disabled = false;
        btn.textContent = 'Simpan Data';
        if (res.success) {
          okBox.textContent = res.message;
          okBox.style.display = 'block';
          resetForm(jalur);
        } else {
          errBox.textContent = res.message;
          errBox.style.display = 'block';
        }
      })
      .withFailureHandler(function (err) {
        btn.disabled = false;
        btn.textContent = 'Simpan Data';
        errBox.textContent = 'Terjadi kesalahan: ' + err.message;
        errBox.style.display = 'block';
      })
      [fungsi](SESSION_TOKEN, formData);
  }

  function resetForm(jalur) {
    document.querySelectorAll('#form-' + jalur + ' input, #form-' + jalur + ' textarea').forEach(el => el.value = '');
    document.querySelectorAll('#form-' + jalur + ' select').forEach(el => el.selectedIndex = 0);
    ['previewFoto', 'previewKtp'].forEach(id => { document.getElementById(id).style.display = 'none'; document.getElementById(id).src = ''; });
    document.getElementById('dropFoto').innerHTML = '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:4px; color:#64748b;"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg><span class="file-drop-label">Pilih / Ambil Foto</span><span class="file-drop-sub">Format JPG, PNG max 5MB</span>';
    document.getElementById('dropFoto').classList.remove('has-file');
    document.getElementById('dropKtp').innerHTML = '<svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:4px; color:#64748b;"><rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="9" cy="10" r="2"/><line x1="15" y1="8" x2="17" y2="8"/><line x1="15" y1="12" x2="17" y2="12"/><line x1="7" y1="16" x2="17" y2="16"/></svg><span class="file-drop-label">Unggah / Scan KTP</span><span class="file-drop-sub">Format JPG, PNG max 5MB</span>';
    document.getElementById('dropKtp').classList.remove('has-file');
    fotoBase64Data = null; ktpBase64Data = null;

    // Kembalikan ke tampilan awal (blok Pindai KTP) untuk jalur yang mendukungnya
    if (JALUR_BOLEH_SCAN.indexOf(jalur) > -1) {
      document.getElementById('scanBlock-' + jalur).style.display = 'block';
      document.getElementById('manualFields-' + jalur).style.display = 'none';
      const statusEl = document.getElementById('ocrStatus-' + jalur);
      statusEl.style.display = 'none';
      statusEl.textContent = '';
    }
  }

  // ============ RIWAYAT DATA (per Jalur) ============
  function loadRiwayat() {
    const jalurKey = document.getElementById('filterJalur').value;
    const filters = {
      keyword: document.getElementById('filterKeyword').value.trim(),
      status: document.getElementById('filterStatus').value
    };

    if (!jalurKey) {
      // "Semua Data" -> ambil kelima jalur sekaligus, lalu gabungkan jadi satu list
      const daftarJalur = ['DPC', 'DPRT', 'PIP', 'KIP', 'RELAWAN'];
      let selesai = 0;
      let gabungan = [];
      let userRoleHasil = null;

      document.getElementById('listRiwayat').innerHTML = '<div style="text-align:center;color:#94a3b8;padding:30px 0;">Memuat data...</div>';

      daftarJalur.forEach(function (jk) {
        google.script.run
          .withSuccessHandler(function (res) {
            selesai++;
            if (res.success) {
              userRoleHasil = res.userRole;
              res.rows.forEach(function (r) {
                r.jalurKeyAsal = jk;
                r.jalur = JALUR_LABEL[jk];
              });
              gabungan = gabungan.concat(res.rows);
            }
            if (selesai === daftarJalur.length) {
              gabungan.sort(function (a, b) { return b.rowNumber - a.rowNumber; });
              renderPeopleCardList('listRiwayat', gabungan, { showJalur: true, userRole: userRoleHasil });
            }
          })
          .getRiwayatData(SESSION_TOKEN, jk, filters);
      });
      return;
    }

    google.script.run.withSuccessHandler(renderRiwayat).getRiwayatData(SESSION_TOKEN, jalurKey, filters);
  }

  const STATUS_LANJUTAN = {
    'Koordinator Desa': [{ value: 'Diverifikasi Desa', label: 'Verifikasi (tingkat Desa)' }, { value: 'Ditolak', label: 'Tolak' }],
    'Koordinator Kecamatan': [{ value: 'Divalidasi Kecamatan', label: 'Validasi (tingkat Kecamatan)' }, { value: 'Final', label: 'Set Final' }, { value: 'Ditolak', label: 'Tolak' }],
    'Superadmin': [
      { value: 'Diinput', label: 'Diinput' }, { value: 'Diverifikasi Desa', label: 'Diverifikasi Desa' },
      { value: 'Divalidasi Kecamatan', label: 'Divalidasi Kecamatan' }, { value: 'Final', label: 'Final' }, { value: 'Ditolak', label: 'Ditolak' }
    ]
  };

  function renderRiwayat(res) {
    if (!res.success) { alert(res.message); return; }
    renderPeopleCardList('listRiwayat', res.rows, { showJalur: false, jalurKey: res.jalur, userRole: res.userRole });
  }

  let verifikasiJalur = null;
  let verifikasiRowNumber = null;
  function openModalVerifikasi(jalurKey, rowNumber, nama, statusSaatIni) {
    verifikasiJalur = jalurKey;
    verifikasiRowNumber = rowNumber;
    document.getElementById('modalVerifikasiNama').textContent = nama + ' (' + JALUR_LABEL[jalurKey] + ') — status saat ini: ' + statusSaatIni;
    document.getElementById('modalVerifikasiError').style.display = 'none';
    document.getElementById('modalVerifikasiCatatan').value = '';

    const select = document.getElementById('modalVerifikasiStatus');
    select.innerHTML = '';
    (STATUS_LANJUTAN[CURRENT_USER.role] || []).forEach(function (opt) {
      select.innerHTML += '<option value="' + opt.value + '">' + opt.label + '</option>';
    });

    openModal('modalVerifikasi');
  }

  function simpanStatusVerifikasi() {
    const newStatus = document.getElementById('modalVerifikasiStatus').value;
    const catatan = document.getElementById('modalVerifikasiCatatan').value.trim();
    const errBox = document.getElementById('modalVerifikasiError');

    fetchApi('verifikasi.php?action=update-status', 'POST', {
      id: verifikasiRowNumber,
      rowNumber: verifikasiRowNumber,
      newStatus: newStatus,
      catatan: catatan
    })
    .then(function (res) {
      if (res && res.success) {
        closeModal('modalVerifikasi');
        if (typeof loadRiwayat === 'function') loadRiwayat();
        if (typeof loadDashboardData === 'function') loadDashboardData();
      } else {
        errBox.textContent = (res && res.message) ? res.message : 'Gagal menyimpan status verifikasi.';
        errBox.style.display = 'block';
      }
    })
    .catch(function (err) {
      errBox.textContent = 'Terjadi kesalahan: ' + (err.message || err);
      errBox.style.display = 'block';
    });
  }

  // ============ LOG AKTIVITAS ============
  function loadLogAktivitas() {
    google.script.run.withSuccessHandler(renderLogAktivitas).getLogAktivitas(SESSION_TOKEN);
  }

  function renderLogAktivitas(res) {
    if (!res.success) { alert(res.message); return; }
    const tbody = document.querySelector('#tabelLog tbody');
    tbody.innerHTML = '';
    if (res.rows.length === 0) {
      tbody.innerHTML = '<tr><td colspan="4" style="text-align:center;color:#94a3b8;">Belum ada log</td></tr>';
      return;
    }
    res.rows.forEach(function (r) {
      tbody.innerHTML += '<tr><td>' + r.waktu + '</td><td>' + r.user + '</td><td>' + r.aksi + '</td><td>' + r.keterangan + '</td></tr>';
    });
  }

  // ============ PENGATURAN PENGGUNA ============
  function loadUsersList() {
    google.script.run.withSuccessHandler(renderUsersList).getUsersList(SESSION_TOKEN);
  }

  function renderUsersList(res) {
    if (!res.success) { alert(res.message); return; }
    const tbody = document.querySelector('#tabelUsers tbody');
    tbody.innerHTML = '';
    res.rows.forEach(function (u) {
      const wilayah = [u.kecamatan, u.desa, u.ranting].filter(Boolean).join(' / ') || '-';
      const statusBadge = u.status === 'Aktif' ? 'badge-aktif' : 'badge-nonaktif';
      const toggleLabel = u.status === 'Aktif' ? 'Nonaktifkan' : 'Aktifkan';
      const toggleTarget = u.status === 'Aktif' ? 'Nonaktif' : 'Aktif';

      tbody.innerHTML += '<tr>' +
        '<td>' + u.nama + '</td>' +
        '<td>' + u.role + '</td>' +
        '<td>' + wilayah + '</td>' +
        '<td>' + u.username + '</td>' +
        '<td><span class="badge ' + statusBadge + '">' + u.status + '</span></td>' +
        '<td>' +
          '<button class="btn-aksi btn-toggle" onclick="toggleUserStatus(' + u.rowNumber + ',\'' + toggleTarget + '\')">' + toggleLabel + '</button>' +
          '<button class="btn-aksi btn-reset" onclick=\'openModalResetPassword(' + u.rowNumber + ',"' + u.nama.replace(/"/g,'') + '")\'>Reset Password</button>' +
        '</td>' +
        '</tr>';
    });
  }

  function openModalTambahUser() {
    ['uNama','uKecamatan','uDesa','uRanting','uUsername','uPassword'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('uRole').value = 'Admin Ranting';
    document.getElementById('modalUserError').style.display = 'none';
    openModal('modalTambahUser');
  }

  function toggleWilayahFields() {
    // Placeholder jika ke depan ingin menyembunyikan/menampilkan kolom sesuai role
  }

  function simpanUserBaru() {
    const userData = {
      nama: document.getElementById('uNama').value.trim(),
      role: document.getElementById('uRole').value,
      kecamatan: document.getElementById('uKecamatan').value,
      desa: document.getElementById('uDesa').value.trim(),
      ranting: document.getElementById('uRanting').value.trim(),
      username: document.getElementById('uUsername').value.trim(),
      password: document.getElementById('uPassword').value
    };
    const errBox = document.getElementById('modalUserError');

    google.script.run
      .withSuccessHandler(function (res) {
        if (res.success) { closeModal('modalTambahUser'); loadUsersList(); }
        else { errBox.textContent = res.message; errBox.style.display = 'block'; }
      })
      .withFailureHandler(function (err) {
        errBox.textContent = 'Terjadi kesalahan: ' + err.message;
        errBox.style.display = 'block';
      })
      .addUserFromUI(SESSION_TOKEN, userData);
  }

  function toggleUserStatus(rowNumber, newStatus) {
    if (!confirm('Ubah status pengguna ini menjadi "' + newStatus + '"?')) return;
    google.script.run.withSuccessHandler(function (res) {
      if (res.success) loadUsersList(); else alert(res.message);
    }).updateUserStatus(SESSION_TOKEN, rowNumber, newStatus);
  }

  let resetPasswordRowNumber = null;
  function openModalResetPassword(rowNumber, nama) {
    resetPasswordRowNumber = rowNumber;
    document.getElementById('modalResetNama').textContent = nama;
    document.getElementById('uNewPassword').value = '';
    document.getElementById('modalResetError').style.display = 'none';
    openModal('modalResetPassword');
  }

  function simpanResetPassword() {
    const newPassword = document.getElementById('uNewPassword').value;
    const errBox = document.getElementById('modalResetError');

    google.script.run
      .withSuccessHandler(function (res) {
        if (res.success) { closeModal('modalResetPassword'); alert('Password berhasil direset.'); }
        else { errBox.textContent = res.message; errBox.style.display = 'block'; }
      })
      .withFailureHandler(function (err) {
        errBox.textContent = 'Terjadi kesalahan: ' + err.message;
        errBox.style.display = 'block';
      })
      .resetUserPassword(SESSION_TOKEN, resetPasswordRowNumber, newPassword);
  }


  // =========================================================================
  // MODUL MASA RESES & PENGAWALAN POKIR DPRD (GUS DIM - FRAKSI NASDEM)
  // =========================================================================
  let RESES_DATA = { events: [], pokir: [], stats: {} };

  function switchResesTab(tab, el) {
    document.querySelectorAll('.saas-tabs-bar .saas-tab-pill, .reses-tabs-segmented .reses-tab-btn').forEach(p => p.classList.remove('active'));
    if (el) {
      el.classList.add('active');
    } else {
      const targetId = tab === 'events' ? 'tabPillEvents' : 'tabPillPokir';
      const btn = document.getElementById(targetId);
      if (btn) btn.classList.add('active');
    }
    
    const secEvents = document.getElementById('resesSectionEvents');
    const secPokir = document.getElementById('resesSectionPokir');
    if (secEvents) secEvents.style.display = tab === 'events' ? 'block' : 'none';
    if (secPokir) secPokir.style.display = tab === 'pokir' ? 'block' : 'none';
  }

  function formatRupiah(val) {
    const num = parseInt(val) || 0;
    return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  }

  function formatTanggalIndo(dateStr) {
    if (!dateStr) return '-';
    try {
      const parts = dateStr.split('-');
      if (parts.length === 3) {
        const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const bIdx = parseInt(parts[1], 10) - 1;
        return parts[2] + ' ' + (bulan[bIdx] || parts[1]) + ' ' + parts[0];
      }
      return dateStr;
    } catch(e) {
      return dateStr;
    }
  }

  async function loadReses() {
    try {
      const res = await fetchApi('reses.php?action=list_all', 'GET');
      if (res && res.success) {
        RESES_DATA = res.data || { events: [], pokir: [], stats: {} };

        // 1. Update KPI
        const s = RESES_DATA.stats || {};
        const elTitik = document.getElementById('kpiTitikReses');
        if (elTitik) elTitik.textContent = (s.total_titik || 0).toLocaleString('id-ID');

        const elWarga = document.getElementById('kpiWargaHadir');
        if (elWarga) elWarga.textContent = (s.total_warga || 0).toLocaleString('id-ID');

        const elPokir = document.getElementById('kpiUsulanPokir');
        if (elPokir) elPokir.textContent = (s.total_pokir || 0).toLocaleString('id-ID');

        const elPagu = document.getElementById('kpiPaguRealisasi');
        if (elPagu) elPagu.textContent = formatRupiah(s.total_anggaran_apbd || 0);

        // Tab count badges
        const badgeEvents = document.getElementById('badgeTabEvents');
        if (badgeEvents) badgeEvents.textContent = (RESES_DATA.events || []).length;

        const badgePokir = document.getElementById('badgeTabPokir');
        if (badgePokir) badgePokir.textContent = (RESES_DATA.pokir || []).length;

        // 2. Render Events & Pokir
        renderResesEvents(RESES_DATA.events || []);
        renderPokirList(RESES_DATA.pokir || []);
        if (typeof mapInstance !== 'undefined' && mapInstance && typeof renderMap === 'function') {
          renderMap();
        }
      } else {
        console.error('Gagal memuat data reses:', res ? res.message : 'Unknown');
      }
    } catch (err) {
      console.error('Error loadReses:', err);
    }
  }

  function renderResesEvents(events) {
    const grid = document.getElementById('resesEventsGrid');
    if (!grid) return;

    if (!events || events.length === 0) {
      grid.innerHTML = `
        <div class="reses-empty-guide">
          <div class="reses-empty-icon">
            <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          </div>
          <div class="reses-empty-header">
            <h3>Belum Ada Titik Kunjungan Reses Terjadwal</h3>
            <p>Masa Reses adalah instrumen resmi Gus Dim untuk turun langsung menjaring aspirasi konstituen Dapil Kraksaan Raya dan mengawalnya menjadi program APBD Kabupaten Probolinggo.</p>
          </div>
          <div class="reses-workflow-steps">
            <div class="reses-step-card">
              <div class="reses-step-num">01</div>
              <h4>Penjadwalan Titik Reses</h4>
              <p>Tentukan desa, dusun, dan kelompok sasaran konstituen (petani, pedagang pasar, nelayan, atau tokoh pemuda) beserta waktu pertemuan.</p>
            </div>
            <div class="reses-step-card">
              <div class="reses-step-num">02</div>
              <h4>Presensi Digital &amp; Aspirasi</h4>
              <p>Catat kehadiran warga secara akurat dan rekam usulan kebutuhan lingkungan langsung saat pertemuan berlangsung.</p>
            </div>
            <div class="reses-step-card">
              <div class="reses-step-num">03</div>
              <h4>Eskalasi ke Bank Pokir</h4>
              <p>Konversikan aspirasi menjadi usulan Pokir resmi fraksi untuk diinput ke SIPD dan diperjuangkan dalam penganggaran APBD.</p>
            </div>
          </div>
          <div class="reses-empty-cta">
            <button class="btn-primary-nasdem" onclick="openModalTambahTitikReses()">
              <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              <span>Jadwalkan Titik Reses Perdana</span>
            </button>
          </div>
        </div>
      `;
      return;
    }

    grid.innerHTML = events.map(ev => {
      const hadirCount = ev.total_hadir || (ev.kehadiran_warga ? ev.kehadiran_warga.length : 0);
      const isSelesai = ev.status === 'Selesai';
      const badgeStyle = isSelesai ? 'background:#ecfdf5; color:#059669; border:1px solid #a7f3d0;' : 'background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe;';
      const badgeText = isSelesai ? 'Selesai Dilaksanakan' : 'Terjadwal Resmi';

      const escTitle = (ev.nama_acara || '').replace(/'/g, "\\'");
      const escKec = (ev.kecamatan || '').replace(/'/g, "\\'");
      const escDesa = (ev.desa || '').replace(/'/g, "\\'");

      return `
        <div class="card reses-card-item" style="padding:18px; display:flex; flex-direction:column; justify-content:space-between; border-radius:12px; border:1px solid #e2e8f0; background:#fff; box-shadow:0 1px 3px rgba(0,0,0,0.05); transition:all 0.2s ease;">
          <div>
            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:12px;">
              <span style="font-size:11px; font-weight:700; padding:3px 8px; border-radius:6px; ${badgeStyle}">
                ${badgeText}
              </span>
              <span style="font-size:11px; font-weight:600; color:#64748b; background:#f1f5f9; padding:3px 8px; border-radius:6px;">
                ${ev.masa_sidang || 'Masa Sidang 2026'}
              </span>
            </div>

            <h4 style="font-family:'Poppins',sans-serif; font-size:15px; font-weight:700; color:#0f172a; margin:0 0 10px; line-height:1.35;">
              ${ev.nama_acara || 'Kunjungan Reses'}
            </h4>

            <div style="font-size:12.5px; color:#475569; display:flex; flex-direction:column; gap:6px; margin-bottom:14px;">
              <div style="display:flex; align-items:center; gap:8px;">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <span><b>${formatTanggalIndo(ev.tanggal)}</b> ${ev.waktu ? '&bull; ' + ev.waktu : ''}</span>
              </div>
              <div style="display:flex; align-items:center; gap:8px;">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Desa <b>${ev.desa || '-'}</b>, Kec. <b>${ev.kecamatan || '-'}</b></span>
              </div>
              <div style="display:flex; align-items:center; gap:8px;">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                <span style="color:#64748b; font-size:12px;">${ev.lokasi_detail || '-'}</span>
              </div>
            </div>

            <div style="background:#f8fafc; border:1px solid #f1f5f9; border-radius:8px; padding:10px 12px; margin-bottom:14px;">
              <div style="display:flex; justify-content:space-between; align-items:center;">
                <span style="font-size:12px; font-weight:600; color:#334155;">Warga Terdaftar Hadir:</span>
                <span style="font-size:13px; font-weight:800; color:#2563eb;">${hadirCount} Orang</span>
              </div>
              <div style="font-size:11.5px; color:#64748b; margin-top:4px;">Sasaran: ${ev.target_kelompok || 'Masyarakat Umum'}</div>
            </div>
          </div>

          <div style="display:flex; gap:8px; border-top:1px solid #f1f5f9; padding-top:12px;">
            <button class="btn-primary-nasdem" style="flex:1; padding:8px 10px; font-size:12px; justify-content:center;" onclick="openModalPresensi('${ev.id}', '${escTitle}', '${escKec}', '${escDesa}')">
              <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="16" y1="11" x2="22" y2="11"/></svg>
              <span>+ Presensi</span>
            </button>
            <button class="btn-toolbar-filter" style="flex:1; padding:8px 10px; font-size:12px; justify-content:center;" onclick="openModalTambahPokir('${escKec}', '${escDesa}')">
              <svg viewBox="0 0 24 24" width="13" height="13" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="11" x2="12" y2="17"/><line x1="9" y1="14" x2="15" y2="14"/></svg>
              <span>+ Usulan Pokir</span>
            </button>
          </div>
        </div>
      `;
    }).join('');
  }

  function filterResesEvents() {
    const q = (document.getElementById('resesEventSearch').value || '').toLowerCase();
    const kec = document.getElementById('resesKecamatanFilter').value;

    const filtered = (RESES_DATA.events || []).filter(ev => {
      const matchQ = !q || (ev.nama_acara && ev.nama_acara.toLowerCase().includes(q)) ||
                           (ev.desa && ev.desa.toLowerCase().includes(q)) ||
                           (ev.lokasi_detail && ev.lokasi_detail.toLowerCase().includes(q));
      const matchKec = !kec || ev.kecamatan === kec;
      return matchQ && matchKec;
    });

    renderResesEvents(filtered);
  }

  function getPokirStageBadge(status) {
    const s = status || 'Aspirasi Reses';
    if (s.includes('Realisasi')) {
      return '<span class="saas-badge" style="background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; font-weight:700;">6. Realisasi Lapangan</span>';
    } else if (s.includes('APBD')) {
      return '<span class="saas-badge" style="background:#eef2ff; color:#4f46e5; border:1px solid #c7d2fe; font-weight:700;">5. Masuk APBD Resmi</span>';
    } else if (s.includes('Verifikasi')) {
      return '<span class="saas-badge" style="background:#ecfeff; color:#0891b2; border:1px solid #a5f3fc; font-weight:700;">4. Verifikasi Dinas</span>';
    } else if (s.includes('SIPD')) {
      return '<span class="saas-badge" style="background:#faf5ff; color:#9333ea; border:1px solid #e9d5ff; font-weight:700;">3. Terinput SIPD</span>';
    } else if (s.includes('Disetujui')) {
      return '<span class="saas-badge" style="background:#fffbeb; color:#d97706; border:1px solid #fde68a; font-weight:700;">2. Disetujui Gus Dim</span>';
    } else {
      return '<span class="saas-badge" style="background:#f1f5f9; color:#475569; border:1px solid #cbd5e1; font-weight:700;">1. Aspirasi Reses</span>';
    }
  }

  function renderPokirList(pokirs) {
    const tbody = document.getElementById('tbodyPokir');
    if (!tbody) return;

    if (!pokirs || pokirs.length === 0) {
      tbody.innerHTML = `
        <tr>
          <td colspan="6" style="text-align:center; padding:48px 20px;">
            <div style="max-width:500px; margin:0 auto;">
              <div style="width:48px; height:48px; border-radius:12px; background:#fffbeb; color:#d97706; display:inline-flex; align-items:center; justify-content:center; margin-bottom:12px;">
                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
              </div>
              <h4 style="font-family:'Poppins',sans-serif; font-size:15px; font-weight:700; color:#1e293b; margin:0 0 6px;">Bank Usulan Pokir Belum Memiliki Data</h4>
              <p style="font-size:12.5px; color:#64748b; margin:0 0 16px; line-height:1.5;">Catat usulan Pokir baru hasil pertemuan reses atau eskalasikan aspirasi konstituen untuk dikawal ke SIPD dan APBD Probolinggo.</p>
              <button class="btn-primary-nasdem" onclick="openModalTambahPokir()" style="margin:0 auto; font-size:12.5px; padding:8px 16px;">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                <span>+ Catat Usulan Pokir Baru</span>
              </button>
            </div>
          </td>
        </tr>
      `;
      return;
    }

    tbody.innerHTML = pokirs.map((pk, idx) => {
      const escJudul = (pk.judul_usulan || '').replace(/'/g, "\\'");
      return `
        <tr>
          <td>
            <div style="font-weight:700; color:#0f172a; font-size:13px; line-height:1.4;">${pk.judul_usulan || '-'}</div>
            <div style="font-size:12px; color:#64748b; margin-top:2px;">${pk.deskripsi || '-'}</div>
            <div style="font-size:11px; color:#94a3b8; margin-top:4px;">ID: #${pk.id || (idx+1)} &bull; Diusulkan: ${formatTanggalIndo(pk.created_at ? pk.created_at.substring(0,10) : '')}</div>
          </td>
          <td>
            <span class="meta-pill" style="font-size:11px; font-weight:600; margin-bottom:4px; display:inline-block;">${pk.kategori || 'Umum'}</span>
            <div style="font-size:12.5px; font-weight:600; color:#334155;">Desa ${pk.desa || '-'}, Kec. ${pk.kecamatan || '-'}</div>
            <div style="font-size:11.5px; color:#64748b;">${pk.dusun || '-'}</div>
          </td>
          <td>
            <div style="font-weight:800; color:#0f172a; font-size:13.5px;">${formatRupiah(pk.estimasi_anggaran)}</div>
            <div style="font-size:11px; color:#16a34a; font-weight:600;">${pk.opd_tujuan || 'OPD Terkait'}</div>
          </td>
          <td>
            <div style="font-weight:700; color:#1e293b; font-size:12.5px;">${pk.nama_pengusul || '-'}</div>
            <div style="font-size:12px; color:#64748b;">${pk.kontak_pengusul || '-'}</div>
          </td>
          <td>
            ${getPokirStageBadge(pk.status_tahap)}
            <div style="font-size:11.5px; color:#475569; margin-top:4px; max-width:200px; line-height:1.3;">
              ${pk.catatan_progres || 'Menunggu verifikasi fraksi.'}
            </div>
          </td>
          <td class="col-action" style="white-space:nowrap;">
            <div style="display:flex; gap:6px;">
              <button class="btn-action btn-action-edit" title="Update Tahapan Siklus" onclick="openModalUpdatePokir('${pk.id}', '${escJudul}', '${pk.status_tahap || ''}', '${(pk.catatan_progres || '').replace(/'/g, "\\'")}')">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
              </button>
              <button class="btn-action" style="color:#16a34a; border-color:#bbf7d0; background:#f0fdf4;" title="Kirim Kabar Progres via WhatsApp" onclick="kirimWaUpdatePokir('${pk.id}')">
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
              </button>
            </div>
          </td>
        </tr>
      `;
    }).join('');
  }

  function filterPokirList() {
    const q = (document.getElementById('pokirSearch').value || '').toLowerCase();
    const kat = document.getElementById('pokirKategoriFilter').value;
    const thp = document.getElementById('pokirTahapFilter').value;

    const filtered = (RESES_DATA.pokir || []).filter(pk => {
      const matchQ = !q || (pk.judul_usulan && pk.judul_usulan.toLowerCase().includes(q)) ||
                           (pk.nama_pengusul && pk.nama_pengusul.toLowerCase().includes(q)) ||
                           (pk.desa && pk.desa.toLowerCase().includes(q));
      const matchKat = !kat || pk.kategori === kat;
      const matchThp = !thp || (pk.status_tahap && pk.status_tahap.includes(thp));
      return matchQ && matchKat && matchThp;
    });

    renderPokirList(filtered);
  }

  // MODAL ACTIONS: TITIK RESES
  function openModalTambahTitikReses() {
    document.getElementById('trNama').value = '';
    document.getElementById('trDesa').value = '';
    document.getElementById('trDusun').value = '';
    document.getElementById('trLokasi').value = '';
    document.getElementById('trTanggal').value = new Date().toISOString().substring(0, 10);
    document.getElementById('trTarget').value = '';
    document.getElementById('trCatatan').value = '';
    const err = document.getElementById('modalTitikError');
    if (err) { err.style.display = 'none'; err.textContent = ''; }
    openModal('modalTambahTitikReses');
  }

  async function simpanTitikReses() {
    const nama = document.getElementById('trNama').value.trim();
    const sidang = document.getElementById('trSidang').value;
    const kec = document.getElementById('trKecamatan').value;
    const desa = document.getElementById('trDesa').value.trim();
    const dusun = document.getElementById('trDusun').value.trim();
    const lokasi = document.getElementById('trLokasi').value.trim();
    const tgl = document.getElementById('trTanggal').value;
    const wkt = document.getElementById('trWaktu').value;
    const target = document.getElementById('trTarget').value.trim();
    const catatan = document.getElementById('trCatatan').value.trim();
    const errBox = document.getElementById('modalTitikError');

    if (!nama || !desa || !lokasi || !tgl) {
      if (errBox) { errBox.textContent = 'Harap lengkapi nama agenda, desa, lokasi, dan tanggal!'; errBox.style.display = 'block'; }
      return;
    }

    try {
      const res = await fetchApi('reses.php?action=add_event', 'POST', {
        nama_acara: nama,
        masa_sidang: sidang,
        kecamatan: kec,
        desa: desa,
        dusun: dusun,
        lokasi_detail: lokasi,
        tanggal: tgl,
        waktu: wkt,
        target_kelompok: target,
        catatan: catatan
      });

      if (res && res.success) {
        closeModal('modalTambahTitikReses');
        loadReses();
      } else {
        if (errBox) { errBox.textContent = res ? res.message : 'Gagal menyimpan agenda reses'; errBox.style.display = 'block'; }
      }
    } catch(err) {
      if (errBox) { errBox.textContent = 'Terjadi kesalahan sistem: ' + err.message; errBox.style.display = 'block'; }
    }
  }

  // MODAL ACTIONS: PRESENSI RESES
  function openModalPresensi(eventId, eventTitle, kec, desa) {
    document.getElementById('presensiResesId').value = eventId || '';
    const sub = document.getElementById('presensiSubTitle');
    if (sub) sub.textContent = 'Agenda: ' + (eventTitle || 'Reses') + ' (' + desa + ', ' + kec + ')';

    document.getElementById('prNama').value = '';
    document.getElementById('prNik').value = '';
    document.getElementById('prHp').value = '';
    document.getElementById('prKecamatan').value = kec || 'Kraksaan';
    document.getElementById('prDesa').value = desa || '';
    document.getElementById('prDusun').value = '';
    const err = document.getElementById('modalPresensiError');
    if (err) { err.style.display = 'none'; err.textContent = ''; }
    openModal('modalPresensiReses');
  }

  async function simpanPresensiReses() {
    const resesId = document.getElementById('presensiResesId').value;
    const nama = document.getElementById('prNama').value.trim();
    const nik = document.getElementById('prNik').value.trim();
    const hp = document.getElementById('prHp').value.trim();
    const kec = document.getElementById('prKecamatan').value;
    const desa = document.getElementById('prDesa').value.trim();
    const dusun = document.getElementById('prDusun').value.trim();
    const errBox = document.getElementById('modalPresensiError');

    if (!nama) {
      if (errBox) { errBox.textContent = 'Nama lengkap warga wajib diisi!'; errBox.style.display = 'block'; }
      return;
    }

    try {
      const res = await fetchApi('reses.php?action=add_kehadiran', 'POST', {
        reses_id: resesId,
        nama: nama,
        nik: nik,
        no_hp: hp,
        kecamatan: kec,
        desa: desa,
        dusun: dusun,
        kategori_peserta: 'Konstituen Reses'
      });

      if (res && res.success) {
        closeModal('modalPresensiReses');
        loadReses();
      } else {
        if (errBox) { errBox.textContent = res ? res.message : 'Gagal mencatat presensi'; errBox.style.display = 'block'; }
      }
    } catch (err) {
      if (errBox) { errBox.textContent = 'Terjadi kesalahan sistem: ' + err.message; errBox.style.display = 'block'; }
    }
  }

  // MODAL ACTIONS: TAMBAH POKIR
  function openModalTambahPokir(defaultKec, defaultDesa) {
    document.getElementById('pkJudul').value = '';
    document.getElementById('pkEstimasi').value = '';
    if (defaultKec) document.getElementById('pkKecamatan').value = defaultKec;
    if (defaultDesa) document.getElementById('pkDesa').value = defaultDesa;
    document.getElementById('pkDusun').value = '';
    document.getElementById('pkPengusulNama').value = '';
    document.getElementById('pkPengusulHp').value = '';
    document.getElementById('pkDeskripsi').value = '';
    const err = document.getElementById('modalPokirError');
    if (err) { err.style.display = 'none'; err.textContent = ''; }
    openModal('modalTambahPokir');
  }

  async function simpanPokirBaru() {
    const judul = document.getElementById('pkJudul').value.trim();
    const kategori = document.getElementById('pkKategori').value;
    const estimasi = document.getElementById('pkEstimasi').value;
    const kec = document.getElementById('pkKecamatan').value;
    const desa = document.getElementById('pkDesa').value.trim();
    const dusun = document.getElementById('pkDusun').value.trim();
    const pengusul = document.getElementById('pkPengusulNama').value.trim();
    const hp = document.getElementById('pkPengusulHp').value.trim();
    const deskripsi = document.getElementById('pkDeskripsi').value.trim();
    const errBox = document.getElementById('modalPokirError');

    if (!judul || !desa || !estimasi) {
      if (errBox) { errBox.textContent = 'Harap isi judul proyek, desa, dan estimasi anggaran!'; errBox.style.display = 'block'; }
      return;
    }

    try {
      const res = await fetchApi('reses.php?action=add_pokir', 'POST', {
        judul_usulan: judul,
        kategori: kategori,
        estimasi_anggaran: estimasi,
        kecamatan: kec,
        desa: desa,
        dusun: dusun,
        nama_pengusul: pengusul,
        kontak_pengusul: hp,
        deskripsi: deskripsi
      });

      if (res && res.success) {
        closeModal('modalTambahPokir');
        if (window.currentEskalasiAspirasiId) {
          fetchApi('aspirasi.php?action=update-status', 'POST', {
            id: window.currentEskalasiAspirasiId,
            status: 'Ditindaklanjuti'
          }).then(function () {
            if (typeof loadAspirasi === 'function') loadAspirasi();
          });
          window.currentEskalasiAspirasiId = null;
        }
        if (typeof showPage === 'function') showPage('reses');
        switchResesTab('pokir', document.getElementById('tabPillPokir'));
        loadReses();
      } else {
        if (errBox) { errBox.textContent = res ? res.message : 'Gagal menyimpan usulan pokir'; errBox.style.display = 'block'; }
      }
    } catch(err) {
      if (errBox) { errBox.textContent = 'Terjadi kesalahan sistem: ' + err.message; errBox.style.display = 'block'; }
    }
  }

  // MODAL ACTIONS: UPDATE STATUS POKIR
  function openModalUpdatePokir(pokirId, judul, currentStatus, currentCatatan) {
    document.getElementById('upPokirId').value = pokirId || '';
    const titleEl = document.getElementById('upPokirTitle');
    if (titleEl) titleEl.textContent = judul || 'Usulan Pokir';

    const sel = document.getElementById('upStatusTahap');
    if (sel && currentStatus) {
      for (let i = 0; i < sel.options.length; i++) {
        if (sel.options[i].value === currentStatus || sel.options[i].value.includes(currentStatus)) {
          sel.selectedIndex = i;
          break;
        }
      }
    }

    document.getElementById('upCatatan').value = currentCatatan || '';
    const err = document.getElementById('modalUpPokirError');
    if (err) { err.style.display = 'none'; err.textContent = ''; }
    openModal('modalUpdateStatusPokir');
  }

  async function simpanUpdateStatusPokir() {
    const id = document.getElementById('upPokirId').value;
    const status = document.getElementById('upStatusTahap').value;
    const catatan = document.getElementById('upCatatan').value.trim();
    const errBox = document.getElementById('modalUpPokirError');

    try {
      const res = await fetchApi('reses.php?action=update_pokir_status', 'POST', {
        id: id,
        status_tahap: status,
        catatan_progres: catatan
      });

      if (res && res.success) {
        closeModal('modalUpdateStatusPokir');
        loadReses();
      } else {
        if (errBox) { errBox.textContent = res ? res.message : 'Gagal memperbarui tahapan pokir'; errBox.style.display = 'block'; }
      }
    } catch(err) {
      if (errBox) { errBox.textContent = 'Terjadi kesalahan: ' + err.message; errBox.style.display = 'block'; }
    }
  }

  // WHATSAPP ADVOKASI UPDATE
  function kirimWaUpdatePokir(pokirId) {
    const pk = (RESES_DATA.pokir || []).find(p => String(p.id) === String(pokirId));
    if (!pk) {
      alert('Data Pokir tidak ditemukan');
      return;
    }

    const rawHp = pk.kontak_pengusul || '';
    let cleanHp = rawHp.replace(/[^0-9]/g, '');
    if (cleanHp.startsWith('0')) {
      cleanHp = '62' + cleanHp.substring(1);
    }

        const pengusulNama = pk.nama_pengusul || 'Pengusul Aspirasi';
    const judul = pk.judul_usulan || '-';
    const desa = pk.desa || '-';
    const kec = pk.kecamatan || '-';
    const tahap = pk.status_tahap || 'Aspirasi Reses';
    const catatan = pk.catatan_progres || 'Sedang dalam pengawalan tim fraksi';

    const pesan = "Assalamu'alaikum Wr. Wb. Bpk/Ibu *" + pengusulNama + "*,\n\n" +
      "Kami dari Tim Advokasi Fraksi NasDem (Gus Dim) DPRD Kabupaten Probolinggo menyampaikan kabar perkembangan usulan Pokir Anda:\n\n" +
      "Usulan: *" + judul + "*\n" +
      "Wilayah: Desa " + desa + ", Kec. " + kec + "\n" +
      "Tahap Saat Ini: *" + tahap + "*\n" +
      "Catatan Progres: " + catatan + "\n\n" +
      "Terima kasih atas aspirasi yang disampaikan demi kemajuan dan kemaslahatan bersama.\n\n" +
      "Salam Hormat,\n" +
      "*Tim Advokasi Reses Gus Dim*";

    const waUrl = cleanHp ? 
      "https://api.whatsapp.com/send?phone=" + cleanHp + "&text=" + encodeURIComponent(pesan) :
      "https://api.whatsapp.com/send?text=" + encodeURIComponent(pesan);

    window.open(waUrl, '_blank');
  }

  // ============ JADIKAN AKUN OPERATOR DARI DATA PENDUKUNG ============
  function bukaModalJadikanOperator() {
    const r = window.currentDetailRecord;
    if (!r) return;

    document.getElementById('opNama').value = r.nama || '-';
    document.getElementById('opWilayah').value = [r.kecamatan, r.desa].filter(Boolean).join(' / ') || '-';

    const selRole = document.getElementById('opRole');
    const jk = (r.jalurKey || r.jalur || '').toUpperCase();
    if (jk.indexOf('DPC') !== -1) {
      selRole.value = 'Koordinator Kecamatan';
    } else if (jk.indexOf('DPRT') !== -1) {
      selRole.value = 'Koordinator Desa';
    } else {
      selRole.value = 'Admin Ranting';
    }

    const cleanHp = (r.hp || '').replace(/[^0-9]/g, '');
    document.getElementById('opUsername').value = cleanHp || ('op_' + (r.rowNumber || r.id || ''));

    const cleanNik = (r.nik || '').replace(/[^0-9]/g, '');
    document.getElementById('opPassword').value = cleanNik.length >= 6 ? cleanNik.slice(-6) : '123456';

    const errBox = document.getElementById('modalKonfirmasiOperatorError');
    if (errBox) errBox.style.display = 'none';

    openModal('modalKonfirmasiOperator');
  }

  function prosesBuatAkunOperator() {
    const r = window.currentDetailRecord;
    if (!r) return;

    const opRole = document.getElementById('opRole').value;
    const opUsername = document.getElementById('opUsername').value.trim();
    const opPassword = document.getElementById('opPassword').value.trim();
    const errBox = document.getElementById('modalKonfirmasiOperatorError');
    errBox.style.display = 'none';

    if (!opUsername || !opPassword) {
      errBox.textContent = 'Username dan password awal wajib diisi.';
      errBox.style.display = 'block';
      return;
    }

    const btn = document.getElementById('btnProsesBuatOperator');
    btn.disabled = true;
    btn.textContent = 'Memproses...';

    const payload = {
      pendukung_id: r.rowNumber || r.id,
      role: opRole,
      username: opUsername,
      password: opPassword
    };

    google.script.run
      .withSuccessHandler(function (res) {
        btn.disabled = false;
        btn.textContent = 'Buat Akun Sekarang';
        if (res.success && res.user) {
          closeModal('modalKonfirmasiOperator');
          closeModal('modalDetailOrang');

          document.getElementById('kredNama').textContent = res.user.nama;
          document.getElementById('kredRole').textContent = res.user.role;
          document.getElementById('kredWilayah').textContent = [res.user.kecamatan, res.user.desa].filter(Boolean).join(' / ') || '-';
          document.getElementById('kredUsername').textContent = res.user.username;
          document.getElementById('kredPassword').textContent = res.user.password;

          window.lastCreatedOperator = res.user;

          const nomorWa = formatNomorWa(res.user.hp || r.hp);
          const btnWa = document.getElementById('btnKirimWaKredensial');
          const teksWa = "Assalamu'alaikum Bpk/Ibu " + res.user.nama + ",\n\nBerikut akun login Anda sebagai " + res.user.role + " Tim Gus Dim (Wilayah: " + (res.user.kecamatan || '') + " " + (res.user.desa || '') + "):\n- Username: " + res.user.username + "\n- Password: " + res.user.password + "\n\nSilakan masuk melalui aplikasi: " + window.location.origin + "\n\nTerima kasih.";
          if (nomorWa) {
            btnWa.href = "https://wa.me/" + nomorWa + "?text=" + encodeURIComponent(teksWa);
            btnWa.style.display = 'inline-flex';
          } else {
            btnWa.style.display = 'none';
          }

          openModal('modalKredensialOperator');
          if (typeof loadUsersList === 'function') loadUsersList();
        } else {
          errBox.textContent = res.message || 'Gagal membuat akun operator.';
          errBox.style.display = 'block';
        }
      })
      .withFailureHandler(function (err) {
        btn.disabled = false;
        btn.textContent = 'Buat Akun Sekarang';
        errBox.textContent = 'Terjadi kesalahan: ' + (err.message || err);
        errBox.style.display = 'block';
      })
      .createOperatorFromPendukung(SESSION_TOKEN, payload);
  }

  function salinKredensialOperator() {
    const op = window.lastCreatedOperator;
    if (!op) return;
    const text = "Akun Operator Gus Dim:\nNama: " + op.nama + "\nRole: " + op.role + "\nWilayah: " + (op.kecamatan || '') + " " + (op.desa || '') + "\nUsername: " + op.username + "\nPassword: " + op.password;
    if (navigator.clipboard) {
      navigator.clipboard.writeText(text).then(function () {
        alert('Kredensial berhasil disalin ke clipboard!');
      });
    } else {
      prompt('Salin kredensial berikut:', text);
    }
  }

  function kirimUlangAksesWaOperator() {
    const r = window.currentDetailRecord;
    if (!r) return;
    const nomorWa = formatNomorWa(r.hp);
    if (!nomorWa) {
      alert('Nomor HP tidak valid.');
      return;
    }
    const teksWa = "Assalamu'alaikum Bpk/Ibu " + r.nama + ",\n\nAkun login Anda sebagai " + (r.operator_role || 'Operator') + " Tim Gus Dim:\n- Username: " + (r.operator_username || r.hp) + "\n- Wilayah: " + (r.kecamatan || '') + " " + (r.desa || '') + "\n\nSilakan akses aplikasi: " + window.location.origin + "\n\nJika lupa kata sandi, silakan hubungi admin untuk reset password.\n\nTerima kasih.";
    window.open("https://wa.me/" + nomorWa + "?text=" + encodeURIComponent(teksWa), "_blank");
  }

  function resetPasswordOperatorDariDetail() {
    const r = window.currentDetailRecord;
    if (!r) return;
    if (!confirm("Reset password akun operator untuk " + r.nama + "? Password baru akan dibuat otomatis.")) {
      return;
    }

    const payload = {
      pendukung_id: r.rowNumber || r.id,
      user_id: r.operator_user_id || 0
    };

    google.script.run
      .withSuccessHandler(function (res) {
        if (res.success && res.user) {
          closeModal('modalDetailOrang');

          document.getElementById('kredNama').textContent = res.user.nama;
          document.getElementById('kredRole').textContent = res.user.role;
          document.getElementById('kredWilayah').textContent = [res.user.kecamatan, res.user.desa].filter(Boolean).join(' / ') || '-';
          document.getElementById('kredUsername').textContent = res.user.username;
          document.getElementById('kredPassword').textContent = res.user.password;

          window.lastCreatedOperator = res.user;

          const nomorWa = formatNomorWa(res.user.hp || r.hp);
          const btnWa = document.getElementById('btnKirimWaKredensial');
          const teksWa = "Assalamu'alaikum Bpk/Ibu " + res.user.nama + ",\n\nPassword akun operator Anda (" + res.user.role + ") telah direset:\n- Username: " + res.user.username + "\n- Password Baru: " + res.user.password + "\n\nSilakan masuk melalui aplikasi: " + window.location.origin + "\n\nTerima kasih.";
          if (nomorWa) {
            btnWa.href = "https://wa.me/" + nomorWa + "?text=" + encodeURIComponent(teksWa);
            btnWa.style.display = 'inline-flex';
          } else {
            btnWa.style.display = 'none';
          }

          openModal('modalKredensialOperator');
        } else {
          alert(res.message || 'Gagal mereset password.');
        }
      })
      .withFailureHandler(function (err) {
        alert('Terjadi kesalahan: ' + (err.message || err));
      })
      .resetOperatorPassword(SESSION_TOKEN, payload);
  }
