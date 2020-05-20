@extends('layouts.app')

@section('assets')
@endsection

@section('content')
    @php
        $alertTriggerSubtitle = '
            The <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-dragon"></i></span> Alert trigger</span> is the final key component of UiPath Watcher.
            It allows you to define <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-burn"></i></span> Alerts</span> by
            applying rules based on <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-server"></i></span> UiPath Orchestrator</span> entities
            and <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-chart-bar"></i></span> ElasticSearch</span> logs.
            These <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-dragon"></i></span> Alert triggers</span>
            will be scanned every 5 minutes and may generate <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-burn"></i></span> Alerts</span> to handle.
        ';
    @endphp

    <div class="configuration">
        @include('layouts.title', [
            'title' => 'Alert triggers',
            'icon' => 'dragon',
            'color' => 'link',
            'subtitle' => $alertTriggerSubtitle,
            'subtitleSize' => '6'
        ])
        
        <div class="is-divider"></div>
        @include('configuration.alert-trigger.table')
        
        <div class="is-divider"></div>
        <div class="forms-section">
            @include('configuration.alert-trigger.form.add')
            @include('configuration.alert-trigger.form.edit')
        </div>
    </div>
@endsection
