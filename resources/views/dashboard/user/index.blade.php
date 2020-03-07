@extends('layouts.app')

@section('assets')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('content')
    @include('dashboard.user.tiles.index')
    <hr>
    
    <div class="dashboard">
        <h1 class="title">My pending alerts</h1>
        @include('dashboard.alert.table', [
            'tableID' => 'pending-alerts-table',
            'alerts' => $pendingAlerts,
            'options' => [ 'closed' => false ]
        ])
        <hr>
        <h1 class="title">My closed alerts</h1>
        @include('dashboard.alert.table', [
            'tableID' => 'closed-alerts-table',
            'alerts' => $closedAlerts,
            'options' => [ 'closed' => true ]
        ])
    </div>
@endsection
