# Script mejorado para eliminar referencias a Claude de los commits
Write-Host "🧹 Limpiando referencias a Claude de los commits..." -ForegroundColor Green

# Verificar que tenemos un backup
if (-not (git branch --list backup-antes-de-limpiar)) {
    Write-Host "⚠️  Creando backup de seguridad..." -ForegroundColor Yellow
    git branch backup-antes-de-limpiar-$(Get-Date -Format 'yyyyMMdd-HHmmss')
}

# Contar commits afectados
$affectedCommits = (git log --all --grep="Co-Authored-By.*Claude" --oneline | Measure-Object).Count
Write-Host "📊 Se encontraron $affectedCommits commits con referencias a Claude" -ForegroundColor Yellow

if ($affectedCommits -eq 0) {
    Write-Host "✅ No hay commits que limpiar localmente!" -ForegroundColor Green
    Write-Host ""
    Write-Host "💡 Si Claude aún aparece en GitHub, necesitas hacer force push:" -ForegroundColor Cyan
    Write-Host "   git push --force --all origin" -ForegroundColor Yellow
    exit 0
}

Write-Host ""
Write-Host "⚠️  ADVERTENCIA: Esto reescribirá $affectedCommits commits" -ForegroundColor Red
$confirm = Read-Host "¿Continuar? (s/n)"

if ($confirm -ne 's' -and $confirm -ne 'S') {
    Write-Host "❌ Operación cancelada" -ForegroundColor Red
    exit 1
}

# Usar filter-branch para limpiar los mensajes
$env:FILTER_BRANCH_SQUELCH_WARNING = "1"

Write-Host ""
Write-Host "🔄 Procesando commits..." -ForegroundColor Cyan

git filter-branch -f --msg-filter "
    `$msg = [Console]::In.ReadToEnd()
    `$msg = `$msg -replace 'Co-Authored-By: Claude.*(\r?\n)', ''
    `$msg = `$msg -replace '🤖 Generated with \[Claude Code\].*(\r?\n)', ''
    `$msg = `$msg -replace '(\r?\n){3,}', ""`r`n`r`n""
    `$msg = `$msg.Trim()
    Write-Output `$msg
" -- --all

Write-Host ""
Write-Host "✅ Limpieza completada localmente!" -ForegroundColor Green
Write-Host ""
Write-Host "📤 Para aplicar los cambios a GitHub, ejecuta:" -ForegroundColor Cyan
Write-Host "   git push --force --all origin" -ForegroundColor Yellow
Write-Host ""
Write-Host "⚠️  IMPORTANTE: Informa a tu equipo que ejecuten:" -ForegroundColor Red
Write-Host "   git fetch origin" -ForegroundColor Yellow
Write-Host "   git reset --hard origin/<su-rama>" -ForegroundColor Yellow
Write-Host ""
Write-Host "💡 Las estadísticas de GitHub pueden tardar hasta 24 horas en actualizarse" -ForegroundColor Cyan
