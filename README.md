# How to setup project

PHP Version: 7.4.3
Composer version: Composer 1.10.1
Docker version 20.10.6
docker-compose version 1.29.1,

Instructions:

-Pull project
-Inside project run `composer install`
-Next command run `docker-compose up`
-Run PHP In-build server `php -S localhost:3500`
-Set up a .env file with sample file included in project

It should be looking like this on localhost:
HOST="127.0.0.1:3306"
USERNAME="root"
PASSWORD="password"
DATABASE="app"

###### Application link: http://localhost:3500/
###### Adminer link: http://localhost:8080/

#### DB Screenshot

[Imgur](https://i.imgur.com/pVn7htQ.png)