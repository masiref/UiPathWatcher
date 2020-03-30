@extends('layouts.app')

@section('assets')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('content')
    @include('dashboard.tiles.index')
    @if ($watchedAutomatedProcessesCount === 0)
        <article class="message is-info">
            <div class="message-body">
                You can watch your first Automated Process by creating it
                <a href="{{ route('configuration.watched-automated-process') }}" class="">here</a>.
            </div>
        </article>
    @else
        @if ($alertTriggersCount === 0)
            <article class="message is-info">
                <div class="message-body">
                    You can configure your first <strong><span class="icon"><i class="fas fa-dragon"></i></span> Alert trigger</strong> by creating it
                    <a href="{{ route('configuration.alert-trigger') }}" class="">here</a>.
                </div>
            </article>
        @endif
        
        <div class="dashboard">
            <div class="is-divider" data-content="TABLE VIEW"></div>
            @include('layouts.title', [
                'title' => 'Pending alerts',
                'icon' => 'fire',
                'color' => 'dark'
            ])
            @include('dashboard.alert.table', [
                'tableID' => 'pending-alerts-table',
                'alerts' => $pendingAlerts,
                'options' => [ 'closed' => false ]
            ])

            @include('layouts.title', [
                'title' => 'Closed alerts',
                'icon' => 'dumpster-fire',
                'color' => 'grey-light'
            ])
            @include('dashboard.alert.table', [
                'tableID' => 'closed-alerts-table',
                'alerts' => $closedAlerts,
                'options' => [ 'closed' => true ]
            ])

            <div class="is-divider" data-content="DETAILED VIEW"></div>
            <div class="columns is-multiline">
                @foreach ($client->watchedAutomatedProcesses()->get() as $watchedAutomatedProcess)
                    <div class="column is-6">
                        @include('dashboard.watched-automated-process.element', [ 'autonomous' => true ])
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endsection