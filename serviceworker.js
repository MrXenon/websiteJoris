//Import the Google Workbox API
importScripts('https://storage.googleapis.com/workbox-cdn/releases/6.2.0/workbox-sw.js');

// Check if service worker is running!
console.log('Service-worker is Working!')

//Choose the caching type "Cachefirst if app is Done!"
workbox.routing.registerRoute(
  ({request}) => request.destination === 'image',
  new workbox.strategies.CacheFirst()
); 