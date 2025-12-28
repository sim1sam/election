# Laravel Deployment Guide - Fixing 403 Forbidden Error

## Common Causes of 403 Forbidden Error

### 1. **Document Root Not Set to `public` Directory** (Most Common)

**Problem:** Your web server's document root is pointing to the project root instead of the `public` folder.

**Solution:**
- **cPanel/Shared Hosting:** 
  - Go to your domain settings
  - Change document root from `/public_html` to `/public_html/public`
  - Or point it to `/home/username/public_html/election/public`

- **Apache Virtual Host:**
  ```apache
  <VirtualHost *:80>
      ServerName yourdomain.com
      DocumentRoot /var/www/election/public
      
      <Directory /var/www/election/public>
          AllowOverride All
          Require all granted
      </Directory>
  </VirtualHost>
  ```

- **Nginx:**
  ```nginx
  server {
      listen 80;
      server_name yourdomain.com;
      root /var/www/election/public;
      
      index index.php;
      
      location / {
          try_files $uri $uri/ /index.php?$query_string;
      }
      
      location ~ \.php$ {
          fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
          fastcgi_index index.php;
          fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
          include fastcgi_params;
      }
  }
  ```

### 2. **File Permissions**

Run these commands on your server:
```bash
# Set correct ownership (replace www-data with your web server user)
sudo chown -R www-data:www-data /path/to/election

# Set directory permissions
find /path/to/election -type d -exec chmod 755 {} \;

# Set file permissions
find /path/to/election -type f -exec chmod 644 {} \;

# Make storage and cache writable
chmod -R 775 /path/to/election/storage
chmod -R 775 /path/to/election/bootstrap/cache
```

### 3. **.htaccess File Issues**

Make sure:
- `.htaccess` file exists in `public` directory
- `mod_rewrite` is enabled on Apache
- `AllowOverride All` is set in Apache config

### 4. **Check Server Error Logs**

Check your server error logs:
- Apache: `/var/log/apache2/error.log`
- Nginx: `/var/log/nginx/error.log`
- cPanel: Check error logs in cPanel dashboard

### 5. **Laravel Environment Setup**

Make sure you have:
```bash
# Copy .env file
cp .env.example .env

# Generate application key
php artisan key:generate

# Set APP_ENV=production in .env
# Set APP_DEBUG=false in .env
```

### 6. **Quick Fix: Root .htaccess Redirect**

If you can't change document root, I've created a root `.htaccess` file that redirects to `public` folder. This should work on most shared hosting.

## Testing Steps

1. **Test if public/index.php is accessible:**
   - Try: `https://yourdomain.com/public/index.php`
   - If this works, document root is the issue

2. **Check file permissions:**
   ```bash
   ls -la public/index.php
   ls -la public/.htaccess
   ```

3. **Test .htaccess:**
   - Try accessing: `https://yourdomain.com/public/`
   - Should redirect or show Laravel welcome page

## Contact Your Hosting Provider

If none of the above works, contact your hosting provider and ask:
- "Can you set my document root to the `public` folder of my Laravel application?"
- "Is mod_rewrite enabled?"
- "What are the correct file permissions for Laravel?"




