@echo off
echo Git Publisher for WEB_6
echo ========================
echo.

set /p message="Enter commit message: "
if "%message%"=="" set message="Update files"

echo.
echo Adding files to git...
git add .

echo Committing changes...
git commit -m "%message%"

echo Pushing to GitHub...
git push origin main

echo.
echo ✓ Successfully published to GitHub!
echo Your repository: https://github.com/Glenn-IT/WEB_6
echo.
pause
