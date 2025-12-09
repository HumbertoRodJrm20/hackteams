#!/bin/sh
# Script para limpiar mensajes de commit
sed -e '/Co-Authored-By: Claude/d' \
    -e '/🤖 Generated with \[Claude Code\]/d' \
    -e '/^$/N;/^\n$/D'
