#!/bin/sh
# Script para renombrar commits a nombres simples

msg=$(cat)

case "$msg" in
    "docs: Update scoring system documentation"*)
        echo "documentación"
        ;;
    "feat: Implement criteria-based scoring system for judges"*)
        echo "calificaciones"
        ;;
    "fix: Resolve calificaciones constraint issue preventing score submissions"*)
        echo "fix calificaciones"
        ;;
    "fix: Resolve background color glitch when toggling theme"*)
        echo "fix tema"
        ;;
    "fix: Correct eventos-container background responsiveness in dark mode"*)
        echo "fix eventos"
        ;;
    "fix: Add dark mode support to rankings promedio text"*)
        echo "fix rankings"
        ;;
    "fix: Add dark mode support to project detail view"*)
        echo "fix proyectos"
        ;;
    "fix: Resolve dark mode color responsiveness in events catalog"*)
        echo "fix catálogo"
        ;;
    "feat: Improve rankings view and add detailed ratings panel for admin"*)
        echo "rankings"
        ;;
    "docs: Add comprehensive implementation summary"*)
        echo "resumen"
        ;;
    "docs: Add migration setup instructions"*)
        echo "migraciones"
        ;;
    "docs: Add comprehensive judging and rankings documentation"*)
        echo "documentación jueces"
        ;;
    "feat: Implement project assignment to judges and ranking system"*)
        echo "asignación jueces"
        ;;
    "fix: Enforce single role per user and prevent Juez access to Participante features"*)
        echo "fix roles"
        ;;
    "feat: Complete dark mode implementation and admin management features"*)
        echo "dark mode"
        ;;
    *)
        echo "$msg"
        ;;
esac
