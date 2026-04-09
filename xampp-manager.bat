@echo off
echo XAMPP Service Manager
echo =====================
echo.

echo Checking XAMPP Services Status...
echo.

echo Apache Status:
sc query Apache2.4 >nul 2>&1
if %errorlevel%==0 (
    echo ✓ Apache service found
    for /f "tokens=3" %%a in ('sc query Apache2.4 ^| findstr "STATE"') do set "apache_state=%%a"
    echo Apache State: %apache_state%
) else (
    echo ✗ Apache service not found
)
echo.

echo MySQL Status:
sc query mysql >nul 2>&1
if %errorlevel%==0 (
    echo ✓ MySQL service found
    for /f "tokens=3" %%a in ('sc query mysql ^| findstr "STATE"') do set "mysql_state=%%a"
    echo MySQL State: %mysql_state%
) else (
    echo ✗ MySQL service not found
)
echo.

echo Port Status:
echo Checking port 80 (Apache):
netstat -an | findstr :80 >nul 2>&1
if %errorlevel%==0 (
    echo ✓ Port 80 is in use (Apache likely running)
) else (
    echo ✗ Port 80 is free (Apache not running)
)

echo Checking port 3306 (MySQL):
netstat -an | findstr :3306 >nul 2>&1
if %errorlevel%==0 (
    echo ✓ Port 3306 is in use (MySQL likely running)
) else (
    echo ✗ Port 3306 is free (MySQL not running)
)
echo.

echo Quick Actions:
echo.
echo 1. Start XAMPP Control Panel: 
echo    start "" "c:\xampp\xampp-control.exe"
echo.
echo 2. Test Database Connection:
echo    c:\xampp\php\php.exe test-database.php
echo.
echo 3. Start Development Server (after MySQL is running):
echo    c:\xampp\php\php.exe -S localhost:8000
echo.

set /p choice="Do you want to open XAMPP Control Panel? (y/n): "
if /i "%choice%"=="y" (
    start "" "c:\xampp\xampp-control.exe"
)

pause
