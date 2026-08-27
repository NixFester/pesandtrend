# Pesantrends Setup Script
# Run this in PowerShell from the project root

Write-Host "=== Pesantrends Setup ===" -ForegroundColor Cyan

# Check PHP
Write-Host "`n[1/5] Checking PHP..." -ForegroundColor Yellow
php -v
if ($LASTEXITCODE -ne 0) {
    Write-Host "PHP not found! Install with:" -ForegroundColor Red
    Write-Host "Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.5'))" -ForegroundColor Gray
    exit 1
}

# Check Composer
Write-Host "`n[2/5] Checking Composer..." -ForegroundColor Yellow
composer -V
if ($LASTEXITCODE -ne 0) {
    Write-Host "Composer not found! It should be installed with PHP." -ForegroundColor Red
    exit 1
}

# Install dependencies
Write-Host "`n[3/5] Installing Composer dependencies..." -ForegroundColor Yellow
composer install

# Run migrations
Write-Host "`n[4/5] Running database migrations..." -ForegroundColor Yellow
php artisan migrate --force

# Seed database
Write-Host "`n[5/5] Seeding database with sample data..." -ForegroundColor Yellow
php artisan db:seed --force

Write-Host "`n=== Setup Complete! ===" -ForegroundColor Green
Write-Host "Run 'php artisan serve' to start the server" -ForegroundColor Cyan
Write-Host "Then open http://localhost:8000 in your browser" -ForegroundColor Cyan
