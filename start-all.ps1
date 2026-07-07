Write-Host "===================================" -ForegroundColor Cyan
Write-Host "   STARTING HEALTH AI SYSTEM" -ForegroundColor Green
Write-Host "===================================" -ForegroundColor Cyan

# BACKEND (Laravel)
Write-Host "Starting Backend (Laravel)..." -ForegroundColor Yellow
Start-Process powershell -ArgumentList "cd backend-laravel; php artisan serve --port=8001"

# FRONTEND (Angular)
Write-Host "Starting Frontend (Angular)..." -ForegroundColor Yellow
Start-Process powershell -ArgumentList "cd aisclepius-frontend; ng serve"

# ML SERVICE (FastAPI)
Write-Host "Starting ML Service (FastAPI)..." -ForegroundColor Yellow
Start-Process powershell -ArgumentList "cd ml-service; uvicorn main:app --reload"



Write-Host "===================================" -ForegroundColor Green
Write-Host "   ALL SERVICES STARTED" -ForegroundColor Green
Write-Host "===================================" -ForegroundColor Green