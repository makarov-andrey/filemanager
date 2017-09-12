Для установки проекта создайте и настройте файл окружения .env в корне проекта и выполните команды из папки приложения:
```sh
$ composer install
$ artisan migrate
$ artisan db:seed
```
Для заполнения БД фейковыми файлами выполните
 ```sh
artisan db:seed --class=FilesTableFakesSeeder
```