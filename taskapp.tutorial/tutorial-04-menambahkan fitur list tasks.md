# Menambahkan fitur List Tasks
## Menambahkan proses query pada fungsi `index` di `TasksController`
Tambahkan kode program berikut untuk melakukan query dan mengirimkan hasilnya ke halaman view.
```
    public function index()
    {
        $tasks = Task::all();

        return view('tasks.index')->withTasks($tasks);
    }
```
 
## Mengupdate view untuk menampilkan semua list
Buka file `resources/tasks/index.blade.php`. Tambahkan looping untuk menampilkan data `tasks` yang dikirimkan oleh controller. Letakkan setelah `<hr>`
```
    @foreach($tasks as $task)
    <h3>{{ $task->title }}</h3>
    <p>{{ $task->description}}</p>
    <p>
        <a href="{{ route('tasks.show', $task->id) }}" class="btn btn-info">View Task</a>
        <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-primary">Edit Task</a>
    </p>
    <hr>
    @endforeach
```
Refresh halaman `tasks` untuk melihat list semua tasks.

## Pengembangan lebih lanjut
Laravel menyediakan fitur `pagination` untuk menampilkan data. Kalian dapat mengeksplorasi sendiri fitur tersebut.