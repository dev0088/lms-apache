#!/bin/bash

echo "Restarting apache2 service..."
echo $1 | sudo systemctl reload apache2.service

echo "Selecting node version manager..."
source /home/dev/.nvm/nvm.sh
nvm use 18.15.0

echo "Restarting websocket server..."
cd ./ws/server
pm2 stop all
npm install
pm2 start server.js

echo "Done!"
