@extends('layouts.app')

@section('assets')
@endsection

@section('content')
    @php
        $watchedAutomatedProcessSubtitle = '
            In order to watch an automated process created with UiPath solution, you\'ll need to give information on it
            (eg: a name, a code, an execution time slot, etc.) but also identify the UiPath
            <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-sitemap"></i></span> Processes</span>,
            <span class="has-text-weight-medium"><span class="icon"><i class="fab fa-android"></i></span> Robots</span> and
            <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-layer-group"></i></span> Queues</span> involved. It will allow you to define
            <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-dragon"></i></span> Alert triggers</span>
            on these entities (and on others related to <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-chart-bar"></i></span> ElasticSearch</span>).
        ';
    @endphp

    <div class="configuration">
        @include('layouts.title', [
            'title' => 'Watched processes',
            'icon' => 'binoculars',
            'color' => 'link',
            'subtitle' => $watchedAutomatedProcessSubtitle,
            'subtitleSize' => '6'
        ])
        
        <div class="is-divider"></div>
        
        @include('configuration.watched-automated-process.table')
        
        <div class="is-divider"></div>

        <div class="forms-section">
            @include('configuration.watched-automated-process.form.add')
            @include('configuration.watched-automated-process.form.edit')
        </div>
    </div>
@endsection
