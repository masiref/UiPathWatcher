@extends('layouts.app')

@section('assets')
@endsection

@section('content')
    <div class="configuration">
        @include('layouts.title', [
            'title' => 'Watched processes',
            'icon' => 'binoculars',
            'color' => 'link'
        ])
        @include('configuration.watched-automated-process.table')
        
        <div class="is-divider"></div>

        @include('layouts.title', [
            'title' => 'Watch a new process',
            'icon' => 'plus-circle',
            'color' => 'primary'
        ])
        @include('configuration.watched-automated-process.add-form')
    </div>
@endsection
