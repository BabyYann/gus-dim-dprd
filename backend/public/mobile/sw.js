const CACHE_NAME = 'gusdim-mobile-cache-v26';
const STATIC_ASSETS = [
  './',
  './index.html',
  './manifest.json',
  './assets/css/mobile.css',
  './assets/js/mobile.js',
  './assets/icons/gus-dim.png',
  './assets/img/header-bg.png',
  './assets/img/thumb-kraksaan.png',
  './assets/img/thumb-besuk.png',
  './assets/img/thumb-gading.png',
  './assets/img/map-sebaran.png'
];

self.addEventListener('install', (event) => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(STATIC_ASSETS).catch(() => {});
    })
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) => {
      return Promise.all(
        keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))
      );
    }).then(() => self.clients.claim())
  );
});

self.addEventListener('message', (event) => {
  if (event.data && event.data.action === 'skipWaiting') {
    self.skipWaiting();
  }
  if (event.data && event.data.action === 'clearCache') {
    caches.keys().then((keys) => {
      return Promise.all(keys.map((k) => caches.delete(k)));
    });
  }
});

self.addEventListener('fetch', (event) => {
  const url = new URL(event.request.url);

  // 1. API calls: Selalu Network First
  if (url.pathname.includes('/api/')) {
    event.respondWith(
      fetch(event.request).catch(() => {
        return new Response(JSON.stringify({
          offline: true,
          message: 'Mode offline aktif. Sambungkan ke jaringan internet untuk sinkronisasi data.'
        }), {
          headers: { 'Content-Type': 'application/json' }
        });
      })
    );
    return;
  }

  // 2. Seluruh Request (Navigasi HTML & Aset Statis): Network First agar UI terbaru langsung tampil
  event.respondWith(
    fetch(event.request)
      .then((networkResponse) => {
        if (networkResponse && networkResponse.status === 200) {
          const copy = networkResponse.clone();
          caches.open(CACHE_NAME).then((cache) => cache.put(event.request, copy));
        }
        return networkResponse;
      })
      .catch(() => {
        return caches.match(event.request).then((cached) => cached || caches.match('./index.html'));
      })
  );
});
