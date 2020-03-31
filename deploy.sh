#!/bin/bash

echo $1 | sudo systemctl reload apache2.service

source /home/dev/.nvm/nvm.sh
nvm use 18.15.0
cd ./ws/server
pm2 stop all
npm install
pm2 start server.js

