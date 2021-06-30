#!/usr/bin/env bash
now=$(date +"%T")
echo "[Console] $now : WebSocket server started"
php yii socket/start-socket
