# Membuat `Auth` Controller
## Login menggunakan `email`
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

## Login Menggunakan `username`
Secara umum untuk `Auth` Laravel menggunakan email untuk login. Apabila kita ingin menggunakan `username` sebagai loginnya maka ada beberapa setup yang diperlukan. 
Lakukan langkah 2-4 apabila ingin menggunakan `migrate`, atau tambahkan kolom `username` di tabel secara manual.

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
7. Sesuaikan form register dengan menambahkan input `username`. Cek pada contoh file `resources/views/auth/register.blade.php`
8. Sesuaikan form login dengan mengganti input `email` menjadi `username`. Cek pada contoh file `resources/views/auth/login.blade.php`
