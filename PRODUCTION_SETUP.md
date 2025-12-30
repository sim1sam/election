# Production Setup Guide

## Cache Management

### Clear All Cache (IndexedDB + Service Worker)

To clear all cached data, open browser console and run:
```javascript
clearAllCache()
```

Or visit any page and run in console:
```javascript
// Clear IndexedDB
await voterDB.clearAll();

// Clear Service Worker Cache
const cacheNames = await caches.keys();
await Promise.all(cacheNames.map(name => caches.delete(name)));

// Unregister Service Worker
const registrations = await navigator.serviceWorker.getRegistrations();
await Promise.all(registrations.map(reg => reg.unregister()));

// Reload page
window.location.reload();
```

## Production Build

### NPM Build (Optional)

The public pages (home, voter-search, voter-search-results) use CDN resources, so npm build is **NOT required** for these pages.

However, if you have admin pages using Vite assets, run:

```bash
# Install dependencies
npm install

# Build for production
npm run build
```

### Laravel Production Commands

```bash
# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Clear all caches (if needed)
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

## PWA Files

The PWA system uses these files (no build required):
- `public/manifest.json` - Web App Manifest
- `public/sw.js` - Service Worker
- `public/js/indexeddb.js` - IndexedDB Helper
- `public/js/pwa.js` - PWA Helper

These are plain JavaScript files and work directly without compilation.

## Deployment Checklist

1. ✅ Clear all caches: `clearAllCache()` in browser console
2. ✅ Run Laravel optimization: `php artisan config:cache`
3. ✅ Set `.env` to production mode: `APP_ENV=production`
4. ✅ Set debug to false: `APP_DEBUG=false`
5. ⚠️ NPM build only if using Vite assets in admin pages
6. ✅ Ensure PWA files are deployed: `manifest.json`, `sw.js`, `js/indexeddb.js`, `js/pwa.js`

