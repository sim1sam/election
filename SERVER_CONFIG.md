# Server Configuration for Live Server

## Connection Timeout Fix

Since the site works locally but times out on live server (dhaka13mh.info), check these server settings:

### 1. PHP Configuration (php.ini)

```ini
; Increase execution time
max_execution_time = 300
max_input_time = 300

; Increase memory limit
memory_limit = 256M

; Increase POST size
post_max_size = 50M
upload_max_filesize = 50M
```

### 2. Apache/Nginx Configuration

**Apache (.htaccess or httpd.conf):**
```apache
<IfModule mod_php.c>
    php_value max_execution_time 300
    php_value memory_limit 256M
</IfModule>
```

**Nginx (nginx.conf):**
```nginx
fastcgi_read_timeout 300;
client_max_body_size 50M;
```

### 3. Database Indexes (IMPORTANT)

Run this migration to add indexes:
```bash
php artisan migrate
```

Or manually add indexes:
```sql
ALTER TABLE voters ADD INDEX idx_ward_number (ward_number);
ALTER TABLE voters ADD INDEX idx_date_of_birth (date_of_birth);
ALTER TABLE voters ADD INDEX idx_ward_date (ward_number, date_of_birth);
```

### 4. Test Server Health

```bash
# Test health endpoint
curl https://dhaka13mh.info/health

# Test API with small page
curl https://dhaka13mh.info/api/voters/all?page=1&per_page=10
```

### 5. Laravel Optimization

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6. Check Server Logs

```bash
# Laravel logs
tail -f storage/logs/laravel.log

# PHP error logs
tail -f /var/log/php_errors.log

# Apache/Nginx error logs
tail -f /var/log/apache2/error.log
# or
tail -f /var/log/nginx/error.log
```

### 7. Database Connection

Check `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Increase timeout
DB_TIMEOUT=30
```

### 8. Quick Fixes

1. **Disable PWA temporarily** (if service worker is causing issues):
   - Comment out service worker registration in `pwa.js`
   - Or clear browser cache

2. **Reduce API page size**:
   - Change `per_page=500` to `per_page=100` in `pwa.js`

3. **Check server resources**:
   - CPU usage
   - Memory usage
   - Disk space
   - Database connection pool

### 9. Emergency Bypass

If site is completely down, you can temporarily disable the auto-load feature:

In `public/js/pwa.js`, comment out:
```javascript
// if (navigator.onLine) {
//   this.loadAllVoterData();
// }
```

This will prevent the automatic data loading that might be causing the timeout.

