@echo off
echo Checking PHP Environment...
echo.

echo PHP Version:
c:\xampp\php\php.exe --version
echo.

echo Checking if project directory exists:
if exist "c:\xampp\htdocs\WEB_6\index.php" (
    echo ✓ Project directory found
) else (
    echo ✗ Project directory not found
)
echo.

echo Checking vendor directory:
if exist "c:\xampp\htdocs\WEB_6\vendor\autoload.php" (
    echo ✓ Composer dependencies installed
) else (
    echo ✗ Composer dependencies missing - run 'composer install'
)
echo.

echo Checking .env file:
if exist "c:\xampp\htdocs\WEB_6\.env" (
    echo ✓ Environment file found
) else (
    echo ✗ Environment file missing
)
echo.

echo Checking MySQL Service:
sc query mysql >nul 2>&1
if %errorlevel%==0 (
    echo ✓ MySQL service found
) else (
    echo ✗ MySQL service not found - start XAMPP Control Panel and start MySQL
)
echo.

echo Checking if MySQL is listening on port 3306:
netstat -an | findstr :3306 >nul 2>&1
if %errorlevel%==0 (
    echo ✓ MySQL is running on port 3306
) else (
    echo ✗ MySQL is not running - start MySQL in XAMPP Control Panel
)
echo.

echo To fix database connection issues:
echo 1. Open XAMPP Control Panel
echo 2. Start Apache service
echo 3. Start MySQL service
echo 4. Check if both services show green "Running" status
echo.

echo To start the development server, run:
echo php -S localhost:8000
echo.
pause
