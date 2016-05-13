<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class TasksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('tasks.index');
    }

    public function store()
    {

    }

    public function create()
    {
        return view('tasks.create');
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


}
