@extends('layouts.app')

@section('assets')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('content')
    @include('dashboard.tiles.index')
    
    @if ($alertTriggersCount < 1)
        @include('dashboard.welcome-message')
    @endif
    
    <div class="dashboard">
        @if ($watchedAutomatedProcessesCount > 0)
            <div class="is-divider" data-content="QUICK BOARD"></div>
            @include('dashboard.quick-board')

            <div class="is-divider" data-content="DETAILED VIEW"></div>
            <div class="columns is-multiline">
                @foreach($clients as $client)
                    <div class="column is-12">
                        @include('dashboard.client.element')
                    </div>
                @endforeach
            </div>

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
    </div>
@endsection
