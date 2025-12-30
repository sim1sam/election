# Troubleshooting Guide

## Connection Timeout Issues

### Server Configuration

If you're getting `ERR_CONNECTION_TIMED_OUT` on live server, check:

1. **PHP Timeout Settings**
   - Check `php.ini`: `max_execution_time = 300` (or higher)
   - Check `.htaccess` or server config for timeout settings

2. **Database Query Optimization**
   - The `/api/voters/all` endpoint now uses pagination (100 records per page - reduced from 500 to prevent timeouts)
   - Data loading is now **lazy** - only loads when user visits the search page, not on homepage
   - This prevents timeout issues on initial page load

3. **Service Worker Issues**
   - Clear browser cache: `clearAllCache()` in console
   - Unregister service worker in DevTools → Application → Service Workers

### Quick Fixes

1. **Clear All Cache:**
   ```javascript
   // In browser console
   clearAllCache()
   ```

2. **Check Server Logs:**
   - Check Laravel logs: `storage/logs/laravel.log`
   - Check server error logs
   - Check PHP error logs

3. **Test API Endpoint:**
   ```bash
   curl https://dhaka13mh.info/api/voters/all?page=1&per_page=100
   ```
   
   Or test the health endpoint:
   ```bash
   curl https://dhaka13mh.info/health
   ```

4. **Check Database:**
   ```bash
   php artisan tinker
   >>> \App\Models\Voter::count()
   ```

### Common Issues

1. **Too Many Voters:**
   - The API now loads in chunks of 100 (reduced from 500 to prevent timeouts)
   - If you have 100,000+ voters, it will load in multiple requests
   - Data loading is lazy - only happens when user visits search page

2. **Service Worker Blocking:**
   - Service worker might be caching failed requests
   - Clear cache and reload

3. **Server Resources:**
   - Check server memory limits
   - Check database connection limits
   - Check PHP memory_limit

### Production Checklist

- [ ] PHP timeout increased (`max_execution_time = 300` or higher)
- [ ] PHP memory increased (`memory_limit = 256M` or higher)
- [ ] Database indexes on `ward_number` and `date_of_birth` (✅ Already migrated)
- [ ] Server can handle concurrent requests
- [ ] Web server timeout settings configured (Apache/Nginx)
- [ ] CDN or caching layer configured (optional)

### Server-Side Configuration

#### For Apache (.htaccess)
Add these lines to your `.htaccess` file in the public directory:
```apache
php_value max_execution_time 300
php_value memory_limit 256M
```

#### For Nginx (php-fpm)
Edit your PHP-FPM configuration file (usually `/etc/php/8.x/fpm/php.ini`):
```ini
max_execution_time = 300
memory_limit = 256M
```

Then restart PHP-FPM:
```bash
sudo systemctl restart php8.x-fpm
```

#### For cPanel/Shared Hosting
1. Go to cPanel → Select PHP Version
2. Click "Options"
3. Set `max_execution_time` to 300
4. Set `memory_limit` to 256M

### Manual Data Loading

If you need to manually trigger voter data loading (for testing or troubleshooting):
```javascript
// In browser console
loadVoterData()
```

### Recent Fixes Applied

✅ **Lazy Loading**: Voter data no longer loads automatically on homepage - only loads when user visits search page
✅ **Reduced Page Size**: API pagination reduced from 500 to 100 records per page
✅ **Better Timeout Handling**: Reduced request timeout from 30s to 20s per request
✅ **Database Indexes**: All indexes are properly configured and migrated

