const workerVersion = 2;

self.addEventListener('install', (event) => {
    // cache resources necessary for error page
    event.waitUntil(
        caches.open(`cache-v${workerVersion}`).then((cache) => {
            return cache.addAll([
                './css/toggle-bootstrap.min.css',
                './css/toggle-bootstrap-dark-overlay.min.css',
                './css/main.css',
                'https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js',
                'https://code.jquery.com/jquery-3.5.1.min.js',
                'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js',
                './js/common.js',
                './icons/maskable.png',
                './favicon.ico',
                './icons/android-chrome-512x512.png',
                './icons/android-chrome-192x192.png',
                `./error.php?error_msg=${encodeURIComponent('You are currently offline.<br>Please connect to the internet and try again.')}`,
            ]);
        })
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keyList) => {
            return Promise.all(keyList.map((key) => {
                if (key !== `cache-v${workerVersion}`) {
                    return caches.delete(key);
                }
            }));
        })
    );
});

self.addEventListener('fetch', (event) => {
    event.respondWith(
        caches.match(event.request).then((response) => {
            return response || fetch(event.request).catch((response) => {
                return caches.match('error.php', {
                    ignoreSearch: true,
                });
            });
        })
    );
});
