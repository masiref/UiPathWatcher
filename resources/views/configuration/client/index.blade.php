@extends('layouts.app')

@section('assets')
@endsection

@section('content')
    @php
        $clientSubtitle = '
            A <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-building"></i></span> Client</span> is a simple entity linked to a
            <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-server"></i></span> UiPath Orchestrator</span> in which you\'ll add
            <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-binoculars"></i></span> Processes to watch</span>.
        ';
    @endphp

    <div class="configuration">
        @include('layouts.title', [
            'title' => 'Clients',
            'icon' => 'building',
            'color' => 'link',
            'subtitle' => $clientSubtitle,
            'subtitleSize' => '6'
        ])
        
        <div class="is-divider"></div>
        
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
