<div class="involved-robots-section p-b-lg">
    @php
        $level = $robots->count() === 1 ? 'success' : $level;
        $title = 'Involved UiPath robots &nbsp;<span class="tag is-rounded is-' . $level . '">'
            . ($robots->count() === 1 ? '1' : '0')
            . '</span>';
        $uniqid = uniqid();
    @endphp

    @include('layouts.title', [
        'title' => $title,
        'icon' => 'robot',
        'color' => $level,
        'titleSize' => '5'
    ])
    <div class="field is-horizontal">
        <div class="field-body">
            <div class="field">
                @foreach ($robots as $robot)
                    @if ($loop->iteration % 4 === 0)
                        </div>
                    @endif
                    @if ($loop->iteration % 4 === 0 || $loop->first)
                        <div class="columns">
                    @endif
                    <div class="column">
                        <p class="control">
                            <input type="checkbox" id="robot-{{ $uniqid . '-' . $robot->id }}"
                                class="switch is-rounded trigger-details--alert-definition--involved-robots--robot-switch
                                    trigger-details--alert-definition--rule--parameter"
                                {!! $robots->count() === 1 ? 'checked="checked" disabled' : '' !!}
                                data-id="{{ $robot->id }}">
                            <label for="robot-{{ $uniqid . '-' . $robot->id }}" class="checkbox">{{ $robot }}</label>
                        </p>
                    </div>
                    @if ($loop->last)
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>