@extends('layouts.app')

@section('assets')
@endsection

@section('content')
    @php
        $orchestratorsSubtitle = '
            By registering a <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-server"></i></span> UiPath Orchestrator</span>
            you\'ll be able to link your <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-building"></i></span> Clients</span> to it,
            but also loading involved <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-robot"></i></span> Robots</span> and
            <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-sitemap"></i></span> Processes</span> you need to watch. Furthermore,
            by specifying information on <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-chart-bar"></i></span> ElasticSearch</span>,
            you\'ll give access to your logs and extend your watching.
        ';
    @endphp

    <div class="configuration">
        @include('layouts.title', [
            'title' => 'Orchestrators',
            'icon' => 'server',
            'color' => 'link',
            'subtitle' => $orchestratorsSubtitle,
            'subtitleSize' => '6',
        ])
        
        <div class="is-divider"></div>
        
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
