#!/bin/bash

# Live Notify Tour Setup Script
# Run this script to set up the project

echo "🚀 Setting up Live Notify Tour (LNT) project..."
echo ""

# 1. Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install

# 2. Copy environment file
echo "⚙️ Setting up environment..."
cp .env.example .env

# 3. Generate application key
echo "🔑 Generating application key..."
php artisan key:generate

# 4. Create database (for SQLite - uncomment if using SQLite)
# touch database/database.sqlite

# 5. Run migrations
echo "🗄️ Running database migrations..."
php artisan migrate

# 6. Seed sample data (optional)
# echo "🌱 Seeding sample data..."
# php artisan db:seed

# 7. Install JavaScript dependencies
echo "📚 Installing JavaScript dependencies..."
npm install

# 8. Build assets
echo "🏗️ Building assets..."
npm run build

echo ""
echo "✅ Setup complete!"
echo ""
echo "To start the development server:"
echo "  Terminal 1: php artisan serve"
echo "  Terminal 2: npm run dev"
echo ""
echo "Then visit: http://localhost:8000"
