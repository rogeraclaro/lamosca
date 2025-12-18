#!/bin/bash
#
# Deploy to production (root)
# Usage: ./deploy-prod.sh
#
# IMPORTANT: Before running this script:
# 1. Change /test/ to / in phpincludes/database.php
# 2. Change /test/ to / in js/navigation.js
# 3. Restore .htaccess.prod as .htaccess
#

SERVER="lamosca.com"
USER="lamosca"  # Canvia pel teu usuari SSH
REMOTE_PATH="/usr/home/lamosca.com/web"

echo "=== Deploying to PRODUCTION $SERVER:$REMOTE_PATH ==="
echo ""
echo "WARNING: This will update the live site!"
read -p "Are you sure? (y/N) " -n 1 -r
echo ""

if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Cancelled."
    exit 1
fi

# Verificar que els paths estan configurats per producció
if grep -q "/test/" phpincludes/database.php; then
    echo "ERROR: database.php still has /test/ paths!"
    echo "Please update to / before deploying to production."
    exit 1
fi

if grep -q "/test/" js/navigation.js; then
    echo "ERROR: navigation.js still has /test/ paths!"
    echo "Please update to / before deploying to production."
    exit 1
fi

# Pujar fitxers
echo "Uploading files..."
rsync -avz --progress \
    --exclude='docker-compose.yml' \
    --exclude='Dockerfile' \
    --exclude='.env' \
    --exclude='_dumpsql' \
    --exclude='admin/.htpasswd' \
    --exclude='.htaccess.prod' \
    --exclude='.htaccess.test' \
    --exclude='.git' \
    --exclude='.claude' \
    --exclude='deploy-test.sh' \
    --exclude='deploy-prod.sh' \
    --exclude='*.bak' \
    --exclude='*~' \
    ./ $USER@$SERVER:$REMOTE_PATH/

echo ""
echo "=== Done! ==="
echo "Live URL: https://www.lamosca.com/"
echo "Admin:    https://www.lamosca.com/admin/"
