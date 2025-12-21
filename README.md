# Election Management System

A Laravel-based election management system with AdminLTE admin panel.

## Features

- **Admin Panel**: Full-featured admin dashboard using AdminLTE
- **Popup Management**: Backend management for frontend campaign popups
- **User Management**: Role-based access control (Admin/User)
- **Election Information**: Display election data with countdown timer
- **Bengali Language Support**: Full Bengali interface

## Requirements

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL/SQLite

## Installation

1. Clone the repository
```bash
git clone https://github.com/sim1sam/election.git
cd election
```

2. Install dependencies
```bash
composer install
npm install
```

3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

4. Run migrations
```bash
php artisan migrate
php artisan db:seed
```

5. Link storage
```bash
php artisan storage:link
```

6. Build assets
```bash
npm run build
```

## Default Admin Credentials

- **Email**: admin@example.com
- **Password**: password

**⚠️ Change the password after first login!**

## Admin Panel

Access the admin panel at: `/admin/dashboard`

## License

MIT License
