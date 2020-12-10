<article class="message is-{{ $level }} alert-definition-item" data-rank="{{ $rank }}">
	<div class="message-header">
        <p>
            <span class="icon"><i class="fas fa-burn"></i></span> <span class="definition-title">{!! $title !!}</span>
            <span class="icon is-medium validity-icon">
                <i class="fas medium fa-{{ $valid ? 'check-circle' : 'exclamation-circle' }}"></i>
            </span>
        </p>
		<a href="#collapsible-definition-{{ $rank }}" data-action="collapse" aria-label="more options">
			<span class="icon">
				<i class="fas fa-angle-down" aria-hidden="true"></i>
			</span>
		</a>
	</div>
    <div id="collapsible-definition-{{ $rank }}" class="is-collapsible">
        <div class="message-body has-text-grey-dark">
            <div class="alert-definition">

                <!-- definition description -->
                @include('configuration.alert-trigger.steps.content.trigger-details.alert-definition.description')

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
    </div>
</article>