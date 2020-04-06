<article class="message is-{{ $level }} alert-definition-item" data-rank="{{ $rank }}">
    <div class="message-body has-text-grey-dark">
        <div class="alert-definition">
            @include('layouts.title', [
                'title' => $title,
                'icon' => 'burn',
                'color' => $level,
                'titleSize' => '5',
                'iconRight' => $valid ? 'check-circle' : 'exclamation-circle',
                'iconRightColor' => $valid ? 'success' : 'danger'
            ])

            <!-- alert level -->
            @include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.level')

            <!-- rules -->
            <div class="is-divider" data-content="RULES"></div>
            <div class="rules-list">
                {{ $slot }}
            </div>
            
            <!-- buttons -->
            @include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.buttons')
        </div>
    </div>
</article>