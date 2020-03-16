@extends('layouts.app')

@section('assets')
@endsection

@section('content')
    <div class="configuration">
        <h1 class="title">Orchestrators</h1>
        @include('configuration.orchestrator.table')
        <hr>

        <h1 class="title">Add orchestrator</h1>
        @include('configuration.orchestrator.add-form')
    </div>
@endsection
