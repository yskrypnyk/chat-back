#chat-back

---
##About
This is a server part for the University summer practice WebChat project.

---
##Getting started
 - To start the project locally first of you need to install **composer**

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

###Using docker
 - This project supports docker containers. If you wish to start this via docker, you need to make sure it is
   [installed](https://docs.docker.com/engine/install/) on your device. Then simply run

```bash
docker-compose up
```

###Using framework 
- if you wish to start this project using framework, you should establish database server and add connection credentials
  to **/config/db.php/**
- then run migration command
```bash
php yii migrate
```
- after successful process finish run serve command
```bash
php yii serve
```

Either way of getting started will result with your server running on localhost:8080 


###Starting sockets

To run sockets you have to install pm2
```bash
npm install pm2 -g
pm2 start ecosystem.config.js
```

---


