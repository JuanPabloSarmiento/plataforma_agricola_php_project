#!/bin/bash
echo "Ejecutando tests (si existen)"
./vendor/bin/phpunit --colors=always || true
