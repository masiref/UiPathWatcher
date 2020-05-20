@extends('layouts.app')

@section('assets')
@endsection

@section('content')
    @php
        $clientSubtitle = '
            A <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-building"></i></span> Client</span> is a simple entity linked to a
            <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-server"></i></span> UiPath Orchestrator</span> in which you\'ll add
            <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-binoculars"></i></span> Processes to watch</span>. Furthermore,
            by specifying information on <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-chart-bar"></i></span> ElasticSearch</span>,
            you\'ll give access to your logs and extend your watching.
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
        <div class="forms-section">
            @include('configuration.client.form.add')
            @include('configuration.client.form.edit')
        </div>
    </div>
@endsection
