@extends('layouts.app')

@section('assets')
@endsection

@section('content')
    <div class="configuration">
        @include('layouts.title', [
            'title' => 'Clients',
            'icon' => 'building',
            'color' => 'link'
        ])
        @include('configuration.client.table')
        
        <div class="is-divider"></div>

        @include('layouts.title', [
            'title' => 'Add a new client',
            'icon' => 'plus-circle',
            'color' => 'primary'
        ])
        @include('configuration.client.add-form')
    </div>
@endsection
