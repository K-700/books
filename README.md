# Тестовое задание "парсинг списка книг"

## Описание задания
Спарсить с любого ресурса рейтинг книг и вывести с пагинацией. Например: https://readrate.com/rus/ratings/top100 (название, автора, изображение, место)

## Описание проекта
Каждые пол часа происходит парсинг страницы https://readrate.com/rus/ratings/top100 и выводится список книг с возможностью фильтрации и сортировки

## Зависимости
+ composer
+ docker-compose/docker
+ crontab

## Установка
**Все команды выполняются из корня проекта**  
`composer update` установка пакетов  
`docker-compose up` поднимаем контейнеры  
`docker exec -it books_fpm_1 php yii migrate --migrationPath=@console/migrations` выполняем миграции  
`docker exec -it books_fpm_1 php yii parse/top-books` запускаем парсер  
Добавить в crontab команду (необходимо заменить **_/путь/к/проекту/_**)  
`* * * * * php /путь/к/проекту/yii schedule/run --scheduleFile=@console/config/schedule.php 1>> /dev/null 2>&1` ставим задачу на парсинг по расписанию  
Добавить в hosts  
`127.0.0.1 top-books.com`  
По адресу http://top-books.com:81/ должен открыться список книг