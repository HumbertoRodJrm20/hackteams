@echo off
echo Limpiando cache de Laravel...
echo.

php artisan route:clear
echo [OK] Cache de rutas limpiada

php artisan config:clear
echo [OK] Cache de configuracion limpiada

php artisan cache:clear
echo [OK] Cache de aplicacion limpiada

php artisan view:clear
echo [OK] Cache de vistas limpiada

echo.
echo ===================================
echo Cache limpiada exitosamente!
echo ===================================
echo.
pause
