@php
    $type = $type ?? 'none';
    $color = $valid ? 'success' : 'danger';
@endphp

<div class="rule-item p-md m-b-md" data-rank="{{ $rank }}">
    @include('layouts.title', [
        'title' => $title,
        'icon' => 'swatchbook',
        'color' => $color,
        'titleSize' => '5'
    ])

    <!-- rule type -->
    <div class="field is-horizontal">
        <div class="field-body">
            <div class="field">
                <div class="control has-icons-left">
                    <div class="select is-fullwidth is-{{ $type === 'none' ? 'danger' : 'success' }}">
                        <select class="trigger-details--alert-definition--rule--type-select">
                            <option value="none" {!! $type === 'none' ? 'selected' : '' !!}>Select an option</option>
                            <option value="jobs-min-duration" {!! $type === 'jobs-min-duration' ? 'selected' : '' !!}>Job should last at minimum</option>
                            <option value="jobs-max-duration" {!! $type === 'jobs-max-duration' ? 'selected' : '' !!}>Job should last at maximum</option>
                            <option value="faulted-jobs-percentage" {!! $type === 'faulted-jobs-percentage' ? 'selected' : '' !!}>Faulted jobs percentage at maximum</option>
                            <option value="failed-queue-items-percentage" {!! $type === 'failed-queue-items-percentage' ? 'selected' : '' !!}>Failed queue items percentage at maximum</option>
                            <option value="elastic-search-query" {!! $type === 'elastic-search-query' ? 'selected' : '' !!}>ElasticSearch query results count</option>
                            {{--<option value="kibana-metric-visualization" {!! $type === 'kibana-metric-visualization' ? 'selected' : '' !!}>Kibana metric visualization</option>--}}
                        </select>
                    </div>
                    <span class="icon is-small is-left">
                        <i class="fas fa-filter"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    {{ $slot }}

    <!-- delete rule button -->
    <div class="field is-horizontal">
        <div class="field-body">
            <div class="field">
                <div class="control">
                    <div class="buttons is-centered">
                        <button class="button is-medium is-outlined is-fullwidth is-danger trigger-details--alert-definition--delete-rule-button">
                            <span class="icon">
                                <i class="fas fa-trash-alt"></i>
                            </span>
                            <span>Remove rule</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>