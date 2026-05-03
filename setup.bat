@echo off
REM Live Notify Tour Setup Script for Windows
REM Run this script to set up the project

echo.
echo ========================================
echo   Live Notify Tour (LNT) Setup
echo ========================================
echo.

REM 1. Install PHP dependencies
echo [1/6] Installing PHP dependencies...
call composer install
if %ERRORLEVEL% neq 0 exit /b %ERRORLEVEL%
echo.

REM 2. Copy environment file
echo [2/6] Setting up environment...
copy .env.example .env
if %ERRORLEVEL% neq 0 exit /b %ERRORLEVEL%
echo.

REM 3. Generate application key
echo [3/6] Generating application key...
php artisan key:generate
if %ERRORLEVEL% neq 0 exit /b %ERRORLEVEL%
echo.

REM 4. Run migrations
echo [4/6] Running database migrations...
php artisan migrate
if %ERRORLEVEL% neq 0 exit /b %ERRORLEVEL%
echo.

REM 5. Install JavaScript dependencies
echo [5/6] Installing JavaScript dependencies...
call npm install
if %ERRORLEVEL% neq 0 exit /b %ERRORLEVEL%
echo.

REM 6. Build assets
echo [6/6] Building assets...
call npm run build
if %ERRORLEVEL% neq 0 exit /b %ERRORLEVEL%
echo.

echo.
echo ========================================
echo   Setup Complete! ✅
echo ========================================
echo.
echo To start the development server:
echo.
echo   Terminal 1: php artisan serve
echo   Terminal 2: npm run dev
echo.
echo Then visit: http://localhost:8000
echo.
