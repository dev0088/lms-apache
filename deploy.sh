#!/bin/bash

echo ""
echo "1. Restarting apache2 service..."
echo -e $1 | sudo systemctl reload apache2.service

echo ""
echo "2. Selecting node version manager..."
source /home/dev/.nvm/nvm.sh
nvm use 18.15.0

echo ""
echo "3. Restarting websocket server..."
cd ./ws/server
pm2 stop all
npm install
pm2 start server.js

echo ""
echo "Done!"
