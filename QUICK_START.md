# 🚀 Quick Start Guide - Live Notify Tour

## Haraka Setup

### Option 1: Using Setup Script (Easiest)
```bash
# Windows
setup.bat

# macOS/Linux
bash setup.sh
```

### Option 2: Manual Setup
```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Create database (update .env DATABASE_URL first)
php artisan migrate

# 4. Build assets
npm run build
```

## Development Server

### Terminal 1 - Laravel Server
```bash
php artisan serve
```
This starts the server at: `http://localhost:8000`

### Terminal 2 - Frontend Assets
```bash
npm run dev
```
Watches and builds CSS/JS files

## 📝 .env Configuration

Update these in `.env` for your database:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ltn_database
DB_USERNAME=root
DB_PASSWORD=
```

Or use SQLite (easier for development):
```
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite
```

## 🎯 Testing the App

1. **Start Servers**
   - `php artisan serve` (Terminal 1)
   - `npm run dev` (Terminal 2)

2. **Go to**: `http://localhost:8000`

3. **Register**: Click "Sign up" and create account

4. **Select Role**: Choose (Tourist/Provider/Admin)

5. **View Dashboard**: See role-specific dashboard with navigation

## 📌 Default Routes

| Route | Purpose |
|-------|---------|
| `/` | Home page |
| `/register` | Sign up |
| `/login` | Sign in |
| `/role-selection` | Choose role after login |
| `/dashboard` | Smart redirect to role dashboard |
| `/dashboard/tourist` | Tourist dashboard |
| `/dashboard/provider` | Provider dashboard |
| `/dashboard/admin` | Admin dashboard |

## 🔧 Troubleshooting

### Database issues?
```bash
# Reset migrations
php artisan migrate:reset

# Redo migrations
php artisan migrate
```

### Clear cache?
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Dependencies issue?
```bash
# Reinstall PHP packages
composer install --no-cache

# Reinstall Node packages
rm -rf node_modules package-lock.json
npm install
```

## 📚 Project Files to Know

- **Routes**: `routes/web.php` - All routes defined here
- **Models**: `app/Models/` - Database models
- **Components**: `app/Livewire/` - Interactive components
- **Views**: `resources/views/livewire/` - Dashboard views
- **Migrations**: `database/migrations/` - Database schema

## 🎨 Key Technologies

- **Laravel 11** - Backend framework
- **Livewire 3** - Interactive components
- **FluxUI** - UI component library
- **Tailwind CSS** - Styling
- **MySQL** - Database

---

**Need more help?**
- Check `PROJECT_GUIDE.md` for detailed documentation
- See inline code comments in models and components
