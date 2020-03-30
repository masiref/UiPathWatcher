@extends('layouts.app')

@section('assets')
@endsection

@section('content')
    <div class="configuration">
        @include('layouts.title', [
            'title' => 'Orchestrators',
            'icon' => 'server',
            'color' => 'link'
        ])
        @include('configuration.orchestrator.table')
        
        <div class="is-divider"></div>

        @include('layouts.title', [
            'title' => 'Add a new orchestrator',
            'icon' => 'plus-circle',
            'color' => 'primary'
        ])
        @include('configuration.orchestrator.add-form')
    </div>
@endsection
