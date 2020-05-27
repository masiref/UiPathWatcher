@extends('layouts.app')

@section('assets')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('content')
    @include('dashboard.user.tiles.index')
    <div class="is-divider"></div>
    
    <div class="dashboard">
        @include('layouts.title', [
            'title' => 'My pending alerts',
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
            'title' => 'My closed alerts',
            'icon' => 'dumpster-fire',
            'color' => 'grey-light',
            'underlined' => false
        ])
        @include('dashboard.alert.table', [
            'tableID' => 'closed-alerts-table',
            'alerts' => $closedAlerts,
            'options' => [ 'closed' => true ]
        ])
    </div>
@endsection
