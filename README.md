Для установки проекта создайте и настройте файл окружения .env в корне проекта и выполните команды из папки приложения:
```sh
$ composer install
$ cp .env.example .env
$ artisan key:generate
```
Далее настройте доступ к БД и в файле .env, после чего можно приступать к структуре БД. Так же здесь стоит настроить переменную MAIL_DRIVER для отправки писем приложением.  
```sh
$ artisan migrate
$ artisan db:seed
```
front-end
```sh
$ npm install
$ npm run dev
```
Для заполнения БД фейковыми файлами выполните
 ```sh
$ artisan db:seed --class=FilesTableFakesSeeder
```

В приложении реализовано [временное файловое хранилище], которое сохраняет в файловой системе приложения файлы, информация о которых фактически еще не записана в БД. Для того чтобы избавиться от невостребованных реализован сборщик мусора (метод [removeOldFiles]), который следует посадить на любой планировщик задач (например cron) или вызывать при обращениях к публичной части, если нагрузка на приложение не большая.
 
 [временное файловое хранилище]: <https://github.com/makarov-andrey/filemanager/blob/master/app/TemporaryStorage/TemporaryStorage.php>
 [removeOldFiles]: <https://github.com/makarov-andrey/filemanager/blob/master/app/TemporaryStorage/TemporaryStorage.php#L43>
 
