# Membuat View `Task Home`
## Membuat `resource/views/tasks/index.blade.php`
1. Download Bootstrap dan taruh di folder `public/bootstrap`.
2. Ubah referensi bootstrap pada file `resource/views/layouts/app.blade.php` menjadi ke file lokal.
    ```
    ...
        <link rel="stylesheet" href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}">
    ...
    ```
3. Buat folder `tasks` pada folder `resources/views`.
4. Buat file `index.blade.php` di folder `resources/views/tasks`.
    ```
    @extends('layouts.app')
    
    @section('content')
    
        <div class="container">
            <h1>Task List</h1>
                <p class="lead">Here's a list of all your tasks. <a href="{{ route('tasks.create') }}">Add a new one?</a></p>
            <hr>
        </div>
    
    @stop
    ```

## Membuat `TaskController`
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

## Menambahkan route `Tasks`
1. Buka file `app/Http/routes.php`.
2. Edit routes dengan menambahkan route Tasks.
    ```
    ...
    // Routing untuk resource tasks
    Route::resource('tasks', 'TasksController');
    ```
3. Cek routing-nya dengan menggunakan `php artisan route:list`.

## Update view `resources/views/layouts/app.blade.php`
1. Tambahkan link ke resource `tasks` di atas bagian dropdown user.
    ```
    ...
        <li><a href="{{ route('tasks.index') }}">Tasks</a></li>
    ...
    ```

## Membuat View `Add New Task`
### Menambahkan file `create.blade.php`
1. Tambahkan referensi `laravelcollective`. Tunggu sampai proses instalasi selesai.
    ```
    composer require laravelcollective/html
    ```
2. Tambahkan referensi `providers` di `config/app.php`.
    ```
        // Laravel Collective provider
        Collective\Html\HtmlServiceProvider::class,
    ```
3. Tambahkan referensi `alias` di `config/app.php`.
    ```
        // Laravel Collective Facade
        'Form' => Collective\Html\FormFacade::class,
        'Html' => Collective\Html\HtmlFacade::class,
    ```
4. Buatlah file `create.blade.php` pada folder `resource/views/tasks/`. Tambahkan konten berikut.
    ```
    @extends('layouts.app')
    
    @section('content')
        <div class="container">
            <h1>Add a New Task</h1>
            <p class="lead">Add to your task list below.</p>
            <hr>
        </div>
        
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
    
    @stop
    ```

## Menambahkan model `Task`
1. Buatlah model `Task` dengan menggunakan perintah berikut.
    ```
    php artisan make:model Task
    ```
2. Edit file `app/Task.php` yang telah terbuat dengan menambahkan nama tabel (`$table`)dan nama field yang bisa di-_insert_ data (`$fillable`).
    ```
        class Task extends Model
        {
            //
            protected $table = 'tasks';
            
            protected $fillable = [
                'title',
                'description'
            ];
    
        }
    ```

## Menambahkan fungsi `store`
1. Buka controller `app/Http/Controllers/TasksController.php` dan tambahkan kode berikut di dalam fungsi `store`. Fungsi ini digunakan untuk menyimpan input dari form (dalam bentuk `$request`) ke dalam 
    ```
        // Tambahkan parameter Request $store
        public function store(Request $request)
        {
            // Mengambil semua input dan memasukkan ke dalam variabel $input
            $input = $request->all();
    
            // Menyimpan ke dalam database dengan menggunakan Eloquent::create
            Task::create($input);
            
            // redirect ke halaman sebelumnya.
            return redirect()->back();
        }
    ```

2. Tambahkan `include` file `Request` dan `Task` sebelum deklarasi kelas `TasksController`.
    ```
    use App\Http\Requests;
    use App\Task;
    ...
    ```
3. Coba jalankan aplikasi dan tambahkan `Task Baru`. Apabila kita mengeklik tombol , maka data `task` baru akan disimpan di dalam tabel.

## Validasi Input
Laravel menyediakan fitur validasi input yang dapat dengan mudah digunakan. Sebagai contohnya, kita dapat melakukan validasi input seperti berikut.

1. Edit fungsi `store` di `TasksController` dengan menambahkan validasi input.
    ```
    ...
        public function store(Request $request)
        {
            // Melakukan validasi input menggunakan Validation dari Laravel
            $this->validate($request, [
                'title' => 'required',
                'description' => 'required'
            ]);
    
            $input = $request->all();
    
            Task::create($input);
    
            // Menambahkan flash message untuk ditampilkan saat redirect
            Session::flash('flash_message', 'Task successfully added!');
    
            return redirect()->back();
        }
    ...
    ```
2. Tambahkan handling `error flash_message` pada file `resources/views/tasks/create.blade.php`. Letakkan pada bagian atas sebelum form.
    ```
        @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif
    ```
3. Tambahkan handling `success flash_message` pada file `resources/views/tasks/create.blade.php`. Letakkan pada bagian atas sebelum form.
    ```
        @if(Session::has('flash_message'))
            <div class="alert alert-success">
                {{ Session::get('flash_message') }}
            </div>
        @endif
    ```
4. Tambahkan `include` file `Session` sebelum deklarasi kelas `TasksController`.
    ```
    use Illuminate\Support\Facades\Session;
    ...
    ```

5. Cobalah untuk memasukkan data baru dengan benar dan lihat success flash yang ditampilkan. Coba juga untuk memasukkan data yang tidak benar, misalnya `title` tidak diisi.
