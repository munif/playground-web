@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Task List</h1>
        <p class="lead">Here's a list of all your tasks. <a href="{{ route('tasks.create') }}">Add a new one?</a></p>
        <hr>
    </div>

@stop