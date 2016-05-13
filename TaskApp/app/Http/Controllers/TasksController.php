<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Task;

use Illuminate\Support\Facades\Session;

class TasksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $tasks = Task::all();

        return view('tasks.index')->withTasks($tasks);
    }

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

    public function create()
    {
        return view('tasks.create');
    }


    public function update()
    {
        return view('tasks.update');
    }

    public function show()
    {
        return view('tasks.show');
    }

    public function destroy()
    {
        return view('tasks.destroy');
    }

    public function edit()
    {
        return view('tasks.edit');
    }


}
