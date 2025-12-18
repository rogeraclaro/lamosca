#!/bin/bash
#
# Deploy to /test/ subdirectory
# Usage: ./deploy-test.sh
#

SERVER="lamosca.com"
USER="lamosca"  # Canvia pel teu usuari SSH
REMOTE_PATH="/usr/home/lamosca.com/web/test"

echo "=== Deploying to $SERVER:$REMOTE_PATH ==="

# 1. Crear directori remot si no existeix
echo "Creating remote directory..."
ssh $USER@$SERVER "mkdir -p $REMOTE_PATH"

# 2. Pujar fitxers
echo "Uploading files..."
rsync -avz --progress \
    --exclude='docker-compose.yml' \
    --exclude='Dockerfile' \
    --exclude='.env' \
    --exclude='_dumpsql' \
    --exclude='admin/.htpasswd' \
    --exclude='.htaccess.prod' \
    --exclude='.git' \
    --exclude='.claude' \
    --exclude='deploy-test.sh' \
    --exclude='deploy-prod.sh' \
    --exclude='*.bak' \
    --exclude='*~' \
    ./ $USER@$SERVER:$REMOTE_PATH/

echo ""
echo "=== Done! ==="
echo "Test URL: https://www.lamosca.com/test/"
echo "Admin:    https://www.lamosca.com/test/admin/"
