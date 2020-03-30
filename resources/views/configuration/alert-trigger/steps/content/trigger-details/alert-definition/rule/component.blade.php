@php
    $type = $type ?? 'none';
    $color = $type === 'none' ? 'danger' : '';
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
                            <option value="none" {!! $type === 'none' ? 'selected' : '' !!}>Select a type</option>
                            <option value="jobs-duration" {!! $type === 'jobs-duration' ? 'selected' : '' !!}>Jobs duration</option>
                            <option value="faulted-jobs-percentage" {!! $type === 'faulted-jobs-percentage' ? 'selected' : '' !!}>Faulted jobs percentage</option>
                            <option value="failed-queue-items-percentage" {!! $type === 'failed-queue-items-percentage' ? 'selected' : '' !!}>Failed queue items percentage</option>
                            <option value="elastic-search-query" {!! $type === 'elastic-search-query' ? 'selected' : '' !!}>ElasticSearch query</option>
                            {{--<option value="kibana-metric-visualization" {!! $type === 'kibana-metric-visualization' ? 'selected' : '' !!}>Kibana metric visualization</option>--}}
                        </select>
                    </div>
                    <span class="icon is-small is-left">
                        <i class="fas fa-object-group"></i>
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
                        <button class="button is-small is-outlined is-danger trigger-details--alert-definition--delete-rule-button">
                            <span class="icon">
                                <i class="fas fa-trash-alt"></i>
                            </span>
                            <span>Delete</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>