@extends('layouts.app')

@section('assets')
@endsection

@section('content')
    <div class="configuration">
        <h1 class="title">Watched processes</h1>
        @include('configuration.watched-automated-process.table')
        <hr>

        <h1 class="title">Watch a new process</h1>
        @include('configuration.watched-automated-process.add-form')
    </div>
@endsection
