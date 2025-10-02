// const CACHE_NAME = "taskmanager-cache-v1";
// const urlsToCache = [
//   "/", // halaman awal
//   "/offline", // offline page
//   "/favicon.ico",
//   "/manifest.json",
//   "/icons/logo.png"
// ];

// // Install SW dan cache asset penting (dengan try-catch agar tidak gagal total)
// self.addEventListener("install", (event) => {
//   event.waitUntil(
//     caches.open(CACHE_NAME).then(async (cache) => {
//       for (let url of urlsToCache) {
//         try {
//           await cache.add(url);
//         } catch (err) {
//           console.warn("Gagal cache:", url, err);
//         }
//       }
//     })
//   );
//   self.skipWaiting();
// });

// // Activate SW dan hapus cache lama
// self.addEventListener("activate", (event) => {
//   event.waitUntil(
//     caches.keys().then((cacheNames) =>
//       Promise.all(
//         cacheNames
//           .filter((name) => name !== CACHE_NAME)
//           .map((name) => caches.delete(name))
//       )
//     )
//   );
//   self.clients.claim();
// });

// // Strategy: cache-first untuk asset statis, network-first untuk API/dashboard
// self.addEventListener("fetch", (event) => {
//   if (event.request.method !== "GET") return;

//   const url = new URL(event.request.url);

//   // API & halaman dinamis -> network first
//   if (url.pathname.startsWith("/api") || url.pathname.startsWith("/dashboard") || url.pathname.startsWith("/tasks")) {
//     event.respondWith(
//       fetch(event.request)
//         .then((response) => {
//           const resClone = response.clone();
//           caches.open(CACHE_NAME).then((cache) => cache.put(event.request, resClone));
//           return response;
//         })
//         .catch(() => caches.match(event.request).then((res) => res || caches.match("/offline")))
//     );
//   } else {
//     // Asset statis -> cache first
//     event.respondWith(
//       caches.match(event.request).then((response) => {
//         return (
//           response ||
//           fetch(event.request).then((res) => {
//             const resClone = res.clone();
//             caches.open(CACHE_NAME).then((cache) => cache.put(event.request, resClone));
//             return res;
//           }).catch(() => caches.match("/offline"))
//         );
//       })
//     );
//   }
// });
