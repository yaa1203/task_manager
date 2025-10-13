// Service Worker Version - update this when you want to force cache refresh
const CACHE_VERSION = 'v1.0.5';
const CACHE_NAME = `taskapp-cache-${CACHE_VERSION}`;
const DATA_CACHE_NAME = `taskapp-data-cache-${CACHE_VERSION}`;

// Files to cache for offline access
const urlsToCache = [
  '/dashboard',
  '/admin/dashboard',
  '/tasks',
  '/projects',
  '/calendar',
  '/analytics',
  '/offline',
  '/manifest.json',
  '/icons/logo96x96.png',
  '/icons/logo72x72.png'
];

// URLs that should NEVER be cached (always fetch fresh)
const noCacheUrls = [
  '/analytics/data',
  '/api/',
  '/admin/analytics/data'
];

// Install Event - Cache essential files
self.addEventListener('install', (event) => {
  console.log('[ServiceWorker] Install v' + CACHE_VERSION);
  
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        console.log('[ServiceWorker] Caching app shell');
        return Promise.allSettled(
          urlsToCache.map(url => 
            cache.add(url).catch(err => {
              console.warn(`Failed to cache ${url}:`, err);
              return Promise.resolve();
            })
          )
        );
      })
      .then(() => self.skipWaiting())
  );
});

// Activate Event - Clean up old caches
self.addEventListener('activate', (event) => {
  console.log('[ServiceWorker] Activate v' + CACHE_VERSION);
  
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME && cacheName !== DATA_CACHE_NAME) {
            console.log('[ServiceWorker] Removing old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
    .then(() => self.clients.claim())
  );
});

// Helper function to check if URL should never be cached
function shouldNeverCache(url) {
  return noCacheUrls.some(pattern => url.includes(pattern));
}

// Fetch Event - Serve from cache when possible
self.addEventListener('fetch', (event) => {
  const { request } = event;
  
  // Skip non-GET requests
  if (request.method !== 'GET') {
    return;
  }
  
  // Parse URL safely
  let url;
  try {
    url = new URL(request.url);
  } catch (e) {
    return;
  }

  // Skip cross-origin requests except for allowed CDNs
  const allowedOrigins = [
    location.origin,
    'https://fonts.bunny.net',
    'https://cdn.jsdelivr.net',
    'https://cdn.tailwindcss.com'
  ];
  
  if (!allowedOrigins.includes(url.origin)) {
    return;
  }

  // CRITICAL: Never cache analytics data - always fetch fresh
  if (shouldNeverCache(request.url)) {
    console.log('[ServiceWorker] Force fresh fetch for:', request.url);
    event.respondWith(
      fetch(request.clone(), {
        cache: 'no-store',
        headers: {
          'Cache-Control': 'no-cache, no-store, must-revalidate',
          'Pragma': 'no-cache',
          'Expires': '0'
        }
      })
      .then(response => {
        // Don't cache the response
        return response;
      })
      .catch(error => {
        console.error('[ServiceWorker] Fetch failed for:', request.url, error);
        
        // Return empty data for analytics instead of cached data
        if (request.url.includes('/analytics/data')) {
          return new Response(
            JSON.stringify({
              tasks: { done: 0, unfinished: 0, overdue: 0 },
              workspaces: { total: 0, breakdown: [] },
              weekly_trend: [],
              summary: { total_tasks: 0, completion_rate: 0 }
            }),
            {
              status: 200,
              statusText: 'OK (Offline)',
              headers: { 
                'Content-Type': 'application/json',
                'X-Offline': 'true'
              }
            }
          );
        }
        
        return new Response('Service Unavailable', { status: 503 });
      })
    );
    return;
  }

  // Handle root path redirect
  if (url.pathname === '/') {
    event.respondWith(
      fetch(request)
        .then(response => response)
        .catch(() => {
          return caches.match('/dashboard')
            .then(cachedResponse => cachedResponse || caches.match('/offline'));
        })
    );
    return;
  }

  // Handle other API requests (network first, cache fallback)
  if (request.url.includes('/api/') || 
      request.url.includes('/admin/') || 
      request.url.includes('/tasks/count') ||
      request.url.includes('/projects/count')) {
    
    event.respondWith(
      fetch(request.clone())
        .then((response) => {
          // Only cache successful responses
          if (response && response.status === 200) {
            const responseToCache = response.clone();
            caches.open(DATA_CACHE_NAME)
              .then((cache) => {
                cache.put(request, responseToCache);
              })
              .catch(err => console.warn('Cache put failed:', err));
          }
          return response;
        })
        .catch(() => {
          // Try to serve from cache
          return caches.match(request)
            .then(cachedResponse => {
              if (cachedResponse) {
                return cachedResponse;
              }
              return new Response('Service Unavailable', { status: 503 });
            });
        })
    );
    return;
  }

  // For navigation requests (HTML pages)
  if (request.mode === 'navigate') {
    event.respondWith(
      fetch(request.clone())
        .then((response) => {
          if (response && response.status === 200) {
            const responseToCache = response.clone();
            caches.open(CACHE_NAME)
              .then((cache) => {
                cache.put(request, responseToCache);
              })
              .catch(err => console.warn('Cache put failed:', err));
          }
          return response;
        })
        .catch(() => {
          return caches.match(request)
            .then((response) => {
              if (response) {
                return response;
              }
              return caches.match('/dashboard')
                .then(dashResponse => {
                  return dashResponse || caches.match('/offline') || 
                         new Response('Offline', { status: 503 });
                });
            });
        })
    );
    return;
  }

  // For static assets (cache first with background update)
  event.respondWith(
    caches.match(request)
      .then((response) => {
        if (response) {
          // Return cached version and update in background
          event.waitUntil(
            fetch(request.clone())
              .then((fetchResponse) => {
                if (fetchResponse && fetchResponse.status === 200) {
                  caches.open(CACHE_NAME)
                    .then((cache) => {
                      cache.put(request, fetchResponse.clone());
                    })
                    .catch(err => console.warn('Background cache update failed:', err));
                }
              })
              .catch(() => {})
          );
          return response;
        }

        // Not in cache, fetch from network
        return fetch(request.clone())
          .then((response) => {
            if (!response || response.status !== 200) {
              return response;
            }

            const responseToCache = response.clone();
            caches.open(CACHE_NAME)
              .then((cache) => {
                cache.put(request, responseToCache);
              })
              .catch(err => console.warn('Cache put failed:', err));

            return response;
          });
      })
      .catch((error) => {
        console.error('Fetch failed:', error);
        if (request.destination === 'document') {
          return caches.match('/offline') || 
                 new Response('Offline', { status: 503 });
        }
        return new Response('Network error', { status: 503 });
      })
  );
});

// Background Sync for offline form submissions
self.addEventListener('sync', (event) => {
  console.log('[ServiceWorker] Background sync:', event.tag);
  
  if (event.tag === 'sync-tasks' || event.tag === 'sync-data') {
    event.waitUntil(syncTasks());
  }
});

// Function to sync tasks when back online
async function syncTasks() {
  try {
    const clients = await self.clients.matchAll();
    
    // Clear analytics data cache to force refresh
    const cache = await caches.open(DATA_CACHE_NAME);
    const requests = await cache.keys();
    
    for (const request of requests) {
      if (shouldNeverCache(request.url)) {
        await cache.delete(request);
        console.log('[ServiceWorker] Cleared cache for:', request.url);
      }
    }
    
    // Notify all clients
    clients.forEach(client => {
      client.postMessage({
        type: 'SYNC_COMPLETE',
        message: 'Data synchronized'
      });
    });
    
    console.log('[ServiceWorker] Sync completed');
  } catch (error) {
    console.error('[ServiceWorker] Sync failed:', error);
  }
}

// Push Notifications
self.addEventListener('push', (event) => {
  console.log('[ServiceWorker] Push received');
  
  let data = {
    title: 'Task App Notification',
    body: 'You have a new update',
    icon: '/icons/logo72x72.png',
    badge: '/icons/logo72x72.png'
  };
  
  if (event.data) {
    try {
      const payload = event.data.json();
      data = { ...data, ...payload };
    } catch (e) {
      data.body = event.data.text();
    }
  }
  
  const options = {
    body: data.body,
    icon: data.icon,
    badge: data.badge,
    vibrate: [200, 100, 200],
    tag: 'notification',
    renotify: true,
    requireInteraction: false,
    data: {
      dateOfArrival: Date.now(),
      url: data.url || '/dashboard'
    },
    actions: [
      {
        action: 'view',
        title: 'View'
      },
      {
        action: 'close',
        title: 'Close'
      }
    ]
  };
  
  event.waitUntil(
    self.registration.showNotification(data.title, options)
  );
});

// Handle notification clicks
self.addEventListener('notificationclick', (event) => {
  console.log('[ServiceWorker] Notification click:', event.action);
  
  event.notification.close();
  
  if (event.action === 'close') {
    return;
  }
  
  const url = event.notification.data?.url || '/dashboard';
  
  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true })
      .then((clientList) => {
        for (const client of clientList) {
          if (client.url.includes('/dashboard') && 'focus' in client) {
            return client.focus();
          }
        }
        if (clients.openWindow) {
          return clients.openWindow(url);
        }
      })
  );
});

// Periodic Background Sync (if supported)
self.addEventListener('periodicsync', (event) => {
  if (event.tag === 'update-data') {
    console.log('[ServiceWorker] Periodic sync:', event.tag);
    event.waitUntil(updateData());
  }
});

async function updateData() {
  try {
    const response = await fetch('/api/sync-data', { 
      cache: 'no-cache',
      credentials: 'same-origin'
    });
    
    if (!response.ok) {
      throw new Error('Sync failed');
    }
    
    const data = await response.json();
    
    if (data.hasUpdates) {
      await self.registration.showNotification('Data Updated', {
        body: 'Your data has been synchronized',
        icon: '/icons/logo72x72.png',
        badge: '/icons/logo72x72.png',
        tag: 'sync-update',
        renotify: false
      });
    }
    
    console.log('[ServiceWorker] Data updated successfully');
  } catch (error) {
    console.error('[ServiceWorker] Update data failed:', error);
  }
}

// Message handler for cache management
self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
  
  if (event.data && event.data.type === 'CLEAR_CACHE') {
    event.waitUntil(
      caches.keys().then((cacheNames) => {
        return Promise.all(
          cacheNames.map((cacheName) => {
            console.log('[ServiceWorker] Clearing cache:', cacheName);
            return caches.delete(cacheName);
          })
        );
      }).then(() => {
        if (event.ports && event.ports[0]) {
          event.ports[0].postMessage({ 
            type: 'CACHE_CLEARED',
            success: true 
          });
        }
      }).catch((error) => {
        console.error('[ServiceWorker] Clear cache failed:', error);
        if (event.ports && event.ports[0]) {
          event.ports[0].postMessage({ 
            type: 'CACHE_CLEARED',
            success: false,
            error: error.message 
          });
        }
      })
    );
  }
  
  // Handle clear analytics cache
  if (event.data && event.data.type === 'CLEAR_ANALYTICS_CACHE') {
    event.waitUntil(
      caches.open(DATA_CACHE_NAME).then((cache) => {
        return cache.keys().then((requests) => {
          return Promise.all(
            requests.map((request) => {
              if (shouldNeverCache(request.url)) {
                console.log('[ServiceWorker] Clearing analytics cache:', request.url);
                return cache.delete(request);
              }
            })
          );
        });
      }).then(() => {
        if (event.ports && event.ports[0]) {
          event.ports[0].postMessage({ 
            type: 'ANALYTICS_CACHE_CLEARED',
            success: true 
          });
        }
      })
    );
  }
});

// Error handler
self.addEventListener('error', (event) => {
  console.error('[ServiceWorker] Error:', event.error);
});

self.addEventListener('unhandledrejection', (event) => {
  console.error('[ServiceWorker] Unhandled rejection:', event.reason);
});