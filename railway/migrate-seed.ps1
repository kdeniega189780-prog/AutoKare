# Run migrations + seed against Railway MySQL from your machine.
# Uses MYSQL_PUBLIC_URL (internal mysql.railway.internal does not resolve locally).
# Usage: .\railway\migrate-seed.ps1

$ErrorActionPreference = "Stop"
Set-Location (Split-Path $PSScriptRoot -Parent)

$pub = ((railway variable list -s MySQL --kv 2>$null) | Where-Object { $_ -match '^MYSQL_PUBLIC_URL=' }) -replace '^MYSQL_PUBLIC_URL=',''
if (-not $pub) {
    Write-Error "MYSQL_PUBLIC_URL not found. Add a MySQL service to the linked Railway project."
}

$cmd = "`$env:DB_URL='$pub'; `$env:DB_CONNECTION='mysql'; php artisan migrate --force && php artisan db:seed --force"
railway run -s AutoKare -- powershell -NoProfile -Command $cmd

Write-Host "Done: migrate + seed on Railway MySQL."
