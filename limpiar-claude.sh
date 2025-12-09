#!/bin/bash
# Script para eliminar referencias a Claude de todos los commits

echo "🧹 Limpiando referencias a Claude de los commits..."

# Usar git filter-repo para limpiar mensajes
python git-filter-repo --message-callback '
import re
message = message.decode("utf-8")
# Eliminar líneas de Co-Authored-By con Claude
message = re.sub(r"Co-Authored-By: Claude.*\n", "", message)
# Eliminar líneas de "Generated with Claude Code"
message = re.sub(r"🤖 Generated with \[Claude Code\].*\n", "", message)
# Limpiar líneas vacías múltiples
message = re.sub(r"\n\n\n+", "\n\n", message)
message = message.strip()
return message.encode("utf-8")
' --force

echo ""
echo "✅ Limpieza completada!"
echo ""
echo "⚠️  IMPORTANTE: Para aplicar los cambios a GitHub, ejecuta:"
echo "   git push --force --all origin"
echo ""
echo "⚠️  ADVERTENCIA: Esto reescribirá la historia. Informa a tu equipo para que ejecuten:"
echo "   git fetch origin"
echo "   git reset --hard origin/[su-rama]"
