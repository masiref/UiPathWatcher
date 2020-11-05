<div id="uipath-robot-tools" class="buttons"
    data-id="{{ $alert->id }}"
    data-created-at="{{ $alert->created_at }}"
    data-revision-started-at="{{ $alert->revision_started_at }}"
    data-messages="{{ json_encode($alert->messages) }}"
    data-trigger-id="{{ $alert->trigger->id }}"
    data-trigger-title="{{ $alert->trigger->title }}"
    data-trigger-definition-id="{{ $alert->definition->id }}"
    data-trigger-definition-level="{{ $alert->definition->level }}"
    data-trigger-definition-description="{{ $alert->definition->description }}"
    data-watched-automated-process-id="{{ $alert->watchedAutomatedProcess->id }}"
    data-watched-automated-process-code="{{ $alert->watchedAutomatedProcess->code }}"
    data-watched-automated-process-name="{{ $alert->watchedAutomatedProcess->name }}"
    data-watched-automated-process-operational-handbook-page-url="{{ $alert->watchedAutomatedProcess->operational_handbook_page_url }}"
    data-watched-automated-process-kibana-dashboard-url="{{ $alert->watchedAutomatedProcess->kibana_dashboard_url }}"
    data-watched-automated-process-additional-information="{{ $alert->watchedAutomatedProcess->additional_information }}"
    data-client-id="{{ $alert->watchedAutomatedProcess->client->id }}"
    data-client-name="{{ $alert->watchedAutomatedProcess->client->name }}"
    data-client-code="{{ $alert->watchedAutomatedProcess->client->code }}">
    @foreach($robotTools as $robotTool)
        <a class="button is-{{ $robotTool->color }}" data-uipath-process="{{ $robotTool->process_name }}" data-uipath-process-label="{{ $robotTool->label }}">
            <span class="icon">
                <i class="fas fa-play-circle"></i>
            </span>
            <span>{{ $robotTool->label }}</span>
        </a>
    @endforeach
</div>