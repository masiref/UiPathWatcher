@extends('layouts.app')

@section('assets')
@endsection

@section('content')
    @php
        $robotToolsSubtitle = '
            An <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-tools"></i></span> Extension</span> is a
            <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-sitemap"></i></span> UiPath Process</span> executed
            on the connected user machine.
            It allows to run attended jobs helping the user to interact with other systems
            (e.g.: Create an IT service request in ServiceNow, create a new issue in Jira, etc.).
            When an <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-burn"></i></span> Alert</span> is triggered,
            you will have the ability, with a simple click, to launch these jobs.
        ';
    @endphp

    <div class="configuration">
        @include('layouts.title', [
            'title' => 'Extensions',
            'icon' => 'tools',
            'color' => 'link',
            'subtitle' => $robotToolsSubtitle,
            'subtitleSize' => '6',
        ])
        
        <div class="is-divider"></div>
        
        @include('configuration.robot-tool.table')
        
        <div class="is-divider"></div>

        <div class="forms-section">
            @include('configuration.robot-tool.form.add')
            @include('configuration.robot-tool.form.edit')
        </div>
    </div>
@endsection
