# Каталог книг

1. Склонировать с репозитория командой `git clone git@github.com:ram-kh/books.git`
2. Перейти в каталог `docker`
3. Переименовать файл example.env в .env
4. Запустить команду `docker compose build`
5. После успешной сборки контейнеров выполнить команду `make up` или `docker-compose up -d`
6. После старта контейнеров перейти в браузер по адресу <http://localhost:9090/> и проверить его работоспособность
7. Зайти в консоль контейнера `make php` или `docker compose exec php-fpm sh`
8. Выполнить следующие команды:  
   ***./yii migrate/up --migrationPath=@yii/rbac/migrations***  
   ***./yii migrate/up***  
   ***./yii add-user/index admin admin***  
   ***./yii add-user/index user user***  
   ***./yii rbac/init***    

9. Проверить авторизацию на сайте `http://localhost:9090/` под пользователем `admin` с паролем `admin` или пользователем `user` с паролем `user`


### В случае неработы контейнеров
В консоль контейнера можно попасть выполнив команду `make php` или `docker compose exec -u app php-fpm sh`

Для остановки контейнера выполнить команду `make down` или `docker compose down --remove-orphans`

Консольные команды можно посмотреть выполнив в контейнере команду `./yii`

### Добавление нового пользователя
Для добавления нового пользователя в контейнере выполнить команду `./yii add-user login password`





