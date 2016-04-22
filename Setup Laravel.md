# Setup Laravel Project
## Setup instalasi
1. Set *environment variable* PHP. Cek lokasi `php.exe` berada di mana, kemudian tambahkan ke `System variables` -> `PATH`.
    Catatan: Apabila menggunakan xampp, maka lokasinya adalah `folder xampp\php`. Contoh: `D:\xampp\php`.

2. Install [Composer](https://getcomposer.org/download/).
 
    Apabila membutuhkan proxy ITS, maka tambahkan *environment variable* `http_proxy` dan `https_proxy` set dengan *value* sebagai berikut.
    ```
    http://email:password@alamatproxy:port/

    Contoh:
    // @ pada email diganti dengan %40
    http://email%40if.its.ac.id:passwordku@proxy.its.ac.id:8080/
    ```
    Tes `composer` di command line. Apabila tidak bisa dijalankan, tambahkan path `composer` ke `System variables` -> `PATH`.
    ```
    C:\ProgramData\ComposerSetup\bin
    ```

3. Install Laravel
    ```
    composer global require "laravel/installer"
    ```
    Tes `laravel` di command line. Apabila tidak terdeteksi sebagai program, tambahkan path berikut di `System variables` -> `PATH`.
    ```
    C:\Users\%Your username%\AppData\Roaming\Composer\vendor\bin
    ```

4. Create project baru dengan *syntax* `laravel new <project>`. Misalkan nama *project*-nya adalah warehouse, maka perintahnya adalah seperti berikut.
    ```
    D:\xampp\htdocs\laravel new warehouse
    ```
    Tunggu sampai proses instalasi selesai.

5. Masuk ke direktori `warehouse`. Tes aplikasi `warehouse` dengan menjalankan internal web server Laravel.
    ```
    D:\xampp\htdocs\warehouse>php artisan serve
    ```

6. Kalau misalkan `artisan`-nya tidak dikenali, maka lakukan langkah-langkah berikut:
    * Jalankan `composer install`.
    * Tambahkan *application key*.
        ```
        php artisan key:generate
        ```
    * Copy `.env.example` menjadi `.env`.
