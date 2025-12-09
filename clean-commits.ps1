# Script para limpiar referencias a Claude de los commits
Write-Host "Limpiando referencias a Claude de los commits..." -ForegroundColor Green

# Obtener todos los commits con Co-Authored-By
$commits = git log --all --grep="Co-Authored-By" --format="%H"

Write-Host "Encontrados $($commits.Count) commits con referencias a Claude" -ForegroundColor Yellow

# Usar filter-branch para limpiar los mensajes
$env:FILTER_BRANCH_SQUELCH_WARNING = "1"

git filter-branch -f --msg-filter @'
$msg = [System.Console]::In.ReadToEnd()
$msg = $msg -replace "Co-Authored-By:.*\n", ""
$msg = $msg -replace "🤖 Generated with.*\n", ""
$msg = $msg -replace "\n\n\n+", "`n`n"
$msg = $msg.TrimEnd()
Write-Output $msg
'@ -- --all

Write-Host "`nLimpieza completada!" -ForegroundColor Green
Write-Host "Para aplicar los cambios a GitHub, ejecuta:" -ForegroundColor Cyan
Write-Host "  git push --force --all origin" -ForegroundColor Yellow
