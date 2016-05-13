# TaskApp

## Referensi
[Bootstrapping a Laravel CRUD Project](http://www.sitepoint.com/bootstrapping-laravel-crud-project/)
[CRUD (Create Read Update Delete) in a Laravel App](http://www.sitepoint.com/crud-create-read-update-delete-laravel-app/)

## Konfigurasi database
Apabila belum ada tabel di database, Laravel menyediakan fitur untuk migrasi. Apabila sudah ada tabel di database tidak perlu menjalankan step ini.

Namun apabila menggunakan fitur `Auth` dari Laravel, maka tetap diperlukan menjalankan migrasi (langkah ke-3) untuk membuat tabel `users` dan `password_resets`.

1. Buat tabel `tasks`.
```
php artisan make:migration create_tasks_table --create=tasks
```
Catatan:
`--create=<tabel>` : buat tabel baru
`--table=<tabel>`: menggunakan tabel yang sudah ada

2. Edit `database/migration/...-create_task_table.php` dengan menambahkan kolom `title` dan `description`.
```php
...

    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title');
            $table->text('description');

            $table->timestamps();
        });
    }
...
```

3. Lakukan `migrate`.
```
php artisan migrate
```
4. Cek di database, seharusnya tabel sudah dibuat.
5. Kita dapat meng-_generate_ tabel lain dengan cara yang sama. Apabila tidak ingin menggunakan fitur ini, buatlah tabel di dalam database secara manual.