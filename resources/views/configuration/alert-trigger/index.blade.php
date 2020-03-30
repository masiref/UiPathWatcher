@extends('layouts.app')

@section('assets')
@endsection

@section('content')
    <div class="configuration">
        @include('layouts.title', [
            'title' => 'Alert triggers',
            'icon' => 'dragon',
            'color' => 'link'
        ])
        @include('configuration.alert-trigger.table')
        
        <div class="is-divider"></div>

        @include('layouts.title', [
            'title' => 'Configure a new alert trigger',
            'icon' => 'plus-circle',
            'color' => 'primary'
        ])
        @include('configuration.alert-trigger.add-form')
    </div>
@endsection
