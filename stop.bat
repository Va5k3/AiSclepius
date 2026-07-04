@echo off

echo Stopping HealthAI services...

:: Angular (4200)
for /f "tokens=5" %%a in ('netstat -aon ^| find ":4200"') do taskkill /F /PID %%a

:: Laravel (8000)
for /f "tokens=5" %%a in ('netstat -aon ^| find ":8000"') do taskkill /F /PID %%a

:: FastAPI (8001)
for /f "tokens=5" %%a in ('netstat -aon ^| find ":8001"') do taskkill /F /PID %%a

echo Done.
pause