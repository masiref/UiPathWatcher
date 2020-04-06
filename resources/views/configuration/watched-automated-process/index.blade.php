@extends('layouts.app')

@section('assets')
@endsection

@section('content')
    @php
        $watchedAutomatedProcessSubtitle = '
            In order to watch an automated processcreated with UiPath solution, you\'ll need to give information on it
            (eg: a name, a code, an execution time slot, etc.) but also identify the UiPath
            <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-sitemap"></i></span> Processes</span>,
            <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-robot"></i></span> Robots</span> and
            <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-layer-group"></i></span> Queues</span> involved. It will allow you to define
            alert triggers on these entities (and on others related to <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-chart-bar"></i></span> ElasticSearch</span>).
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

        @include('layouts.title', [
            'title' => 'Watch a new process',
            'icon' => 'plus-circle',
            'color' => 'primary'
        ])
        @include('configuration.watched-automated-process.add-form')
    </div>
@endsection
