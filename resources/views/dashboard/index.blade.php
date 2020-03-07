@extends('layouts.app')

@section('assets')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('content')
    @include('dashboard.tiles.index')
    <hr>
    
    <div class="dashboard">
        <div class="columns is-multiline">
            @foreach($clients as $client)
                <div class="column is-6">
                    @include('dashboard.client.element')
                </div>
            @endforeach
        </div>
        <hr>
        <h1 class="title">Pending alerts</h1>
        @include('dashboard.alert.table', [
            'tableID' => 'pending-alerts-table',
            'alerts' => $pendingAlerts,
            'options' => [ 'closed' => false ]
        ])
        <hr>
        <h1 class="title">Closed alerts</h1>
        @include('dashboard.alert.table', [
            'tableID' => 'closed-alerts-table',
            'alerts' => $closedAlerts,
            'options' => [ 'closed' => true ]
        ])
    </div>
@endsection
