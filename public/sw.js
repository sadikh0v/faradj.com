const CACHE_NAME = 'faradj-v4';
const OFFLINE_URL = '/offline.php';

const STATIC_ASSETS = [
  '/assets/css/style.css',
  '/assets/css/mobile.css',
  '/assets/js/script.js',
  '/assets/js/app.js',
  '/assets/img/logo/faradj_logo.png',
  '/offline.php'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(STATIC_ASSETS))
      .then(() => self.skipWaiting())
      .catch(() => {})
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(
        keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k))
      )
    ).then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  const url = event.request.url;
  const isDocument = event.request.mode === 'navigate' || event.request.destination === 'document';

  if (
    isDocument ||
    url.includes('/lang') ||
    url.includes('/lang.php') ||
    url.includes('nc=') ||
    event.request.method === 'POST'
  ) {
    event.respondWith(
      fetch(event.request).catch(() =>
        isDocument ? caches.match(OFFLINE_URL) : Promise.reject()
      )
    );
    return;
  }
  if (event.request.method !== 'GET') return;
  if (!url.startsWith('http')) return;
  if (url.startsWith('chrome-extension://')) return;
  if (url.startsWith('moz-extension://')) return;

  event.respondWith(
    caches.open(CACHE_NAME).then(cache =>
      cache.match(event.request).then(cached => {
        if (cached) return cached;
        return fetch(event.request)
          .then(response => {
            if (response && response.status === 200 && response.type === 'basic' &&
                event.request.url.startsWith('http')) {
              const clone = response.clone();
              cache.put(event.request, clone).catch(() => {});
            }
            return response;
          })
          .catch(() =>
            event.request.destination === 'document'
              ? caches.match(OFFLINE_URL)
              : caches.match(event.request)
          )
          .then(r => r || caches.match(OFFLINE_URL))
      })
    )
  );
});

self.addEventListener('push', (event) => {
  const data = event.data?.json() ?? {};
  event.waitUntil(
    self.registration.showNotification(data.title || 'Faradj MMC', {
      body: data.body || 'Yeni xəbər var!',
      icon: '/assets/img/logo/icon-192.png',
      badge: '/assets/img/logo/icon-192.png',
      data: { url: data.url || '/' },
      actions: [
        { action: 'open', title: 'Aç' },
        { action: 'close', title: 'Bağla' }
      ]
    })
  );
});

self.addEventListener('notificationclick', (event) => {
  event.notification.close();
  if (event.action === 'open' || !event.action) {
    event.waitUntil(clients.openWindow(event.notification.data?.url || '/'));
  }
});
