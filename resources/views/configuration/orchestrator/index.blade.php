@extends('layouts.app')

@section('assets')
@endsection

@section('content')
    @php
        $orchestratorsSubtitle = '
            By registering a <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-server"></i></span> UiPath Orchestrator</span>
            you\'ll be able to link your <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-building"></i></span> Customers</span> to it,
            but also loading involved <span class="has-text-weight-medium"><span class="icon"><i class="fab fa-android"></i></span> Robots</span> and
            <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-sitemap"></i></span> Processes</span> you need to watch.
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

        <div class="forms-section">
            @include('configuration.orchestrator.form.add')
            @include('configuration.orchestrator.form.edit')
        </div>
    </div>
@endsection
