@extends('layouts.app')

@section('assets')
@endsection

@section('content')
    <div class="configuration">
        <h1 class="title">Clients</h1>
        @include('configuration.client.table')
        <hr>

        <h1 class="title">Add a new client</h1>
        @include('configuration.client.add-form')
    </div>
@endsection
