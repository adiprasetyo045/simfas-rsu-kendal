const CACHE_NAME = 'simfas-cache-v2';
const urlsToCache = [
  '/login',
  '/manifest.json',
  '/css/app.css',
  '/js/app.js',
  '/images/logo.png',
  '/images/login.jpeg'
];

// Install Service Worker dan Cache file penting
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
  );
});

// Aktivasi dan hapus cache lama
self.addEventListener('activate', event => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});

// Fetch dari cache jika offline
self.addEventListener('fetch', event => {
  // Hanya proses request GET
  if (event.request.method !== 'GET') {
      return;
  }
  
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        // Jika ada di cache, kembalikan response cache. Jika tidak, fetch dari network.
        return response || fetch(event.request).catch(() => {
             // Opsional: Kembalikan halaman offline khusus jika ada
        });
      })
  );
});
