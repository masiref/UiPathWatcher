<div class="involved-processes-section p-b-lg">
    @php
        $level = $processes->count() === 1 ? 'success' : $level;
        $title = 'Involved UiPath processes &nbsp;<span class="tag is-rounded is-' . $level . '">'
            . ($processes->count() === 1 ? '1' : '0')
            . '</span>';
        $uniqid = uniqid();
    @endphp
    
    @include('layouts.title', [
        'title' => $title,
        'icon' => 'sitemap',
        'color' => $level,
        'titleSize' => '5'
    ])
    <div class="field is-horizontal">
        <div class="field-body">
            <div class="field">
                @foreach ($processes as $process)
                    @if ($loop->iteration % 5 === 0)
                        </div>
                    @endif
                    @if ($loop->iteration % 5 === 0 || $loop->first)
                        <div class="columns">
                    @endif
                    <div class="column">
                        <p class="control">
                            <input type="checkbox" id="process-{{ $uniqid . '-' . $process->id }}"
                                class="switch is-rounded trigger-details--alert-definition--involved-processes--process-switch
                                    trigger-details--alert-definition--rule--parameter"
                                {!! $alertTriggerRule->processes->pluck('id')->contains($process->id) ? ' checked="checked"' : '' !!}
                                {!! $processes->count() === 1 ? ' disabled' : '' !!}
                                data-id="{{ $process->id }}">
                            <label for="process-{{ $uniqid . '-' . $process->id }}" class="checkbox">{{ $process }}</label>
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