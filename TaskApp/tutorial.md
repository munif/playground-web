# TaskApp

## Referensi
[Bootstrapping a Laravel CRUD Project](http://www.sitepoint.com/bootstrapping-laravel-crud-project/)
[CRUD (Create Read Update Delete) in a Laravel App](http://www.sitepoint.com/crud-create-read-update-delete-laravel-app/)

## Konfigurasi database
Apabila belum ada tabel di database, Laravel menyediakan fitur untuk migrasi. Apabila sudah ada tabel di database, maka tidak menjalankan step ini.

Namun apabila menggunakan fitur `Auth` dari Laravel, maka tetap diperlukan menjalankan migrasi (langkah ke-3) untuk membuat tabel `users` dan `password_resets`.

1. Buat tabel `tasks`.
```
php artisan make:migration create_tasks_table --create=tasks
```
Catatan
* `--create=<tabel>` : buat tabel baru
* `--table=<tabel>`: menggunakan tabel yang sudah ada

2. Edit `database/migration/...-create_task_table.php`
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

## Membuat `Auth` Controller
### Login menggunakan `email`
1. Jalankan perintah berikut.
```
php artisan make:auth
```
2. Untuk mengetes hasilnya, jalankan aplikasi Laravel dengan menggunakan perintah:
```
php artisan serve
```
3. Secara otomatis Laravel akan membuatkan file-file berikut.
        * Halaman `Login`
        * Halaman `Register`
        * Halaman `Home`: _redirect_ apabila sudah login.
        * Halaman `Welcome`
        * Controller `HomeController.php`

    Selain itu Laravel akan meng-_update_ `routes.php`.
    ```
    ...
    Route::auth();

    Route::get('/home', 'HomeController@index');
    ...
    ```
    `Route::auth()` digunakan untuk routing `login`, `register`, `logout`, `password reset`.

4. Lakukan registrasi user baru apabila belum ada user. Kemudian coba lakukan login. Perhatikan bahwa kita bisa melakukan login dengan menggunakan user yang baru.
5. Buka file `HomeController.php`. Halaman ini hanya bisa diakses apabila user login karena terdapat fungsi berikut.
```php
...
    public function __construct()
    {
        $this->middleware('auth');
    }
...
```
Sehingga apabila kita membutuhkan `controller` yang hanya bisa diakses saat user login, maka harus ditambahkan fungsi tersebut di dalam `controller`-nya.

### Login Menggunakan `username`
Secara umum untuk `Auth` Laravel menggunakan email untuk login. Apabila kita ingin menggunakan `username` sebagai loginnya maka ada beberapa setup yang diperlukan.

1. Menambahkan field `username` pada kelas `app/Http/Controllers/Auth/AuthController.php`.
```php
...
    protected $username = 'username';
...

```
2. Lakukan `php artisan migrate:rollback` untuk menge-_drop_ tabel `users`.
3. Tambahkan field `username` pada file `database/migration/...-create_users_table.php`.
```php
...
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            
            // Uncomment apabila ingin menambahkan kolom 'username'
            $table->string('username');

            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }
...
```
4. Lakukan `php artisan migrate` untuk meng-_generate_ tabel yang baru.
5. Edit `app/Http/Controllers/Auth/AuthController.php` untuk menambahkan validasi `username` dan proses `create` ke database.
```
...
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'username' => 'required|max:30',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            // tambahkan insert column `username` berikut
            'username' => $data['username'],

            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
...
```

6. Edit file `app/User.php` untuk menambahkan _fillable_ kolom `username`.
```php
...
    protected $fillable = [
        'name', 'username', 'email', 'password',
    ];
...
```
7. Sesuaikan form register dengan menambahkan input `username`.
8. Sesuaikan form login dengan mengganti input `email` menjadi `username`.

## Membuat View `Task Home`
### Membuat `resource/views/tasks/index.blade.php`
1. Download Bootstrap dan taruh di folder `public/bootstrap`.
2. Ubah referensi bootstrap pada file `resource/views/layouts/app.blade.php` menjadi ke file lokal.
```
...
    <link rel="stylesheet" href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}">
...
```
3. Buat folder `tasks` pada folder `resource/views`.
4. Buat file `index.blade.php` di folder `resource/views/tasks`.
```
@extends('layouts.app')

@section('content')

    <h1>Task List</h1>
    <p class="lead">Here's a list of all your tasks. <a href="{{ route('tasks.create') }}">Add a new one?</a></p>
    <hr>

@stop
```

### Membuat `TaskController`
1. Lakukan `php artisan make:controller TasksController`
2. Tambahkan fungsi berikut pada file `app/Http/Controllers/TasksController.php`.
```php
...
    public function index()
    {
        return view('tasks.index');
    }

    public function store()
    {

    }

    public function create()
    {

    }


    public function update()
    {

    }

    public function show()
    {

    }

    public function destroy()
    {

    }

    public function edit()
    {
        
    }
...
```
3. Tambahkan fungsi `__construct()` untuk mengamankan dengan login.
```
...
    public function __construct()
    {
        $this->middleware('auth');
    }
...
```

### Menambahkan route `Task`
1. Buka file `app/Http/routes.php`.
2. Edit routes dengan menambahkan route Tasks.
```
...
// Routing untuk resource tasks
Route::resource('tasks', 'TasksController');
```
3. Cek routing-nya dengan menggunakan `php artisan route:list`.

### Update view `resources/views/layouts/app.blade.php`
1. Tambahkan link ke resource `tasks` di atas bagian dropdown user.
```
    <li><a href="{{ route('tasks.index') }}">Tasks</a></li>
```

## Membuat View `Add New Task`
1. Tambahkan file `create.blade.php` pada folder `resource/views/tasks/create.blade.php`.
```
@extends('layouts.app')

@section('content')

    <h1>Add a New Task</h1>
    <p class="lead">Add to your task list below.</p>
    <hr>

@stop
```
2. Tambahkan referensi laravelcollective
```
composer require laravelcollective/html
```
3. Tambahkan referensi `providers` di `config/app.php`.
```
        // Laravel Collective provider
        Collective\Html\HtmlServiceProvider::class,
```
4. Tambahkan referensi `alias` di `config/app.php`.
```
        // Laravel Collective Facade
        'Form' => Collective\Html\FormFacade::class,
        'Html' => Collective\Html\HtmlFacade::class,
```
5. Tambahkan form pada file `create.blade.php` tadi.
```html
{!! Form::open([
    'route' => 'tasks.store'
]) !!}

<div class="form-group">
    {!! Form::label('title', 'Title:', ['class' => 'control-label']) !!}
    {!! Form::text('title', null, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('description', 'Description:', ['class' => 'control-label']) !!}
    {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
</div>

{!! Form::submit('Create New Task', ['class' => 'btn btn-primary']) !!}

{!! Form::close() !!}
```
6. 

## Membuat View `Edit New Task`
## Membuat View `Delete New Task`