@extends('layouts.app')

@section('assets')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('content')
    @include('dashboard.tiles.index')
        
    <div class="dashboard">
        @if ($clientWatchedAutomatedProcessesCount === 0)
            <article class="message is-info">
                <div class="message-body">
                    You can watch your first <strong><span class="icon"><i class="fas fa-binoculars"></i></span> Automated Process</strong> by creating it
                    <a href="{{ route('configuration.watched-automated-process') }}" class="">here</a>.
                </div>
            </article>
        @else
            @if ($clientAlertTriggersCount === 0)
                <article class="message is-info">
                    <div class="message-body">
                        You can configure your first <strong><span class="icon"><i class="fas fa-dragon"></i></span> Alert trigger</strong> by creating it
                        <a href="{{ route('configuration.alert-trigger') }}" class="">here</a>.
                    </div>
                </article>
            @endif
        @endif
        @if ($clientWatchedAutomatedProcessesCount > 0)
            <div class="is-divider" data-content="QUICK BOARD"></div>
            @include('dashboard.client.quick-board')

            @if ($clientAlertTriggersCount > 0)
                <div class="is-divider" data-content="TABLE VIEW"></div>
                @include('layouts.title', [
                    'title' => 'Pending alerts',
                    'icon' => 'fire',
                    'color' => 'dark',
                    'underlined' => false
                ])
                @include('dashboard.alert.table', [
                    'tableID' => 'pending-alerts-table',
                    'alerts' => $pendingAlerts,
                    'options' => [ 'closed' => false ]
                ])

                @include('layouts.title', [
                    'title' => 'Closed alerts',
                    'icon' => 'dumpster-fire',
                    'color' => 'grey-light',
                    'underlined' => false
                ])
                @include('dashboard.alert.table', [
                    'tableID' => 'closed-alerts-table',
                    'alerts' => $closedAlerts,
                    'options' => [ 'closed' => true ]
                ])
            @endif
            
            @if ($clientAlertTriggersCount > 0)
                <div class="is-divider" data-content="DETAILED VIEW"></div>
            @endif
            <div class="columns is-multiline">
                @foreach ($client->watchedAutomatedProcesses()->get() as $watchedAutomatedProcess)
                    <div class="column is-12">
                        @include('dashboard.watched-automated-process.element', [ 'autonomous' => true ])
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection