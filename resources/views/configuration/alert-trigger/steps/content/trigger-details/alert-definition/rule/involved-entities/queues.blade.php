<div class="involved-queues-section p-b-lg">
    @php
        $level = $queues->count() === 1 ? 'success' : $level;
        $title = 'Involved UiPath queues &nbsp;<span class="tag is-rounded is-' . $level . '">'
            . ($queues->count() === 1 ? '1' : '0')
            . '</span>';
        $uniqid = uniqid();
    @endphp

    @include('layouts.title', [
        'title' => $title,
        'icon' => 'layer-group',
        'color' => $level,
        'titleSize' => '5'
    ])
    <div class="field is-horizontal">
        <div class="field-body">
            <div class="field">
                @foreach ($queues as $queue)
                    @if ($loop->iteration % 5 === 0)
                        </div>
                    @endif
                    @if ($loop->iteration % 5 === 0 || $loop->first)
                        <div class="columns">
                    @endif
                    <div class="column">
                        <p class="control">
                            <input type="checkbox" id="queue-{{ $uniqid . '-' . $queue->id }}"
                                class="switch is-rounded trigger-details--alert-definition--involved-queues--queue-switch
                                    trigger-details--alert-definition--rule--parameter"
                                {!! $alertTriggerRule->queues->pluck('id')->contains($queue->id) || $queues->count() === 1 ? ' checked="checked"' : '' !!}
                                {!! $queues->count() === 1 ? ' disabled' : '' !!}
                                data-id="{{ $queue->id }}">
                            <label for="queue-{{ $uniqid . '-' . $queue->id }}" class="checkbox">{{ $queue }}</label>
                        </p>
                    </div>
                    @if ($loop->last)
                        @for ($i = $loop->iteration + 1; ($i + 1) % 5 !== 0; $i++)
                            <div class="column"></div>
                        @endfor
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>