#!/bin/bash
sudo systemctl reload apache2.service <<<"
$1
"
nvm use 18.15.0
cd ./ws/server
pm2 stop all
npm install
pm2 start server.js

