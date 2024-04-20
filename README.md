# ZEWS (backend part)

## Установка для разработки (OSPanel)

Необходимо:
* Установить [git](https://git-scm.com/download/win)
* В настойках OSPanel во вкладке "Модули" установить PHP 8.1 или выше и MySQL 5.7 или выше
* В настойках OSPanel во вкладке "Сервер" установить "Свой Path + Win Path" для видимости команды `git`

Откройте консоль OSPanel и введите следующие команды (по очереди): 
```bat
cd domains
git clone https://github.com/Kopchan/hotel-zews-server zews-hotel.ru
cd zews-hotel.ru
composer i
copy .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
```
После можно перезапускать OSPanel для видимости нового домена.

Для доступа к веб-сайту просто через домен в корне проекта необходимо создать файл с названием `.htaccess` и заполнить следующим содержимым:
```apacheconf
RewriteEngine on
RewriteRule (.*)? /public/$1
```
