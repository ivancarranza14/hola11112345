@echo off
cd /d "%~dp0"
echo === Verificando PHP === > servidor_status.log
php -v >> servidor_status.log 2>&1
echo. >> servidor_status.log
echo === Instalando dependencias con Composer === >> servidor_status.log
call composer install >> servidor_status.log 2>&1
echo. >> servidor_status.log
echo === COMPOSER TERMINO, iniciando servidor === >> servidor_status.log
php -S localhost:8000 -t public >> servidor_status.log 2>&1
echo === EL SERVIDOR SE DETUVO, codigo: %errorlevel% === >> servidor_status.log
