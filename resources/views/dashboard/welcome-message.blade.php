<article class="message is-info">
    <div class="message-header">
        Hi there! Welcome to UiPath Watcher! You'll find below a quick start guide on how to use this tool. Happy watching!
    </div>
    <div class="message-body">
        @php
            $orchestratorTitle = '1. Register your first UiPath Orchestrator';
            $orchestratorSubtitle = '
                By registering a <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-server"></i></span> UiPath Orchestrator</span>
                you\'ll be able to link your <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-building"></i></span> Clients</span> to it,
                but also loading involved <span class="has-text-weight-medium"><span class="icon"><i class="fab fa-android"></i></span> Robots</span> and
                <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-sitemap"></i></span> Processes</span> you need to watch.
            ';
            $orchestratorState = $orchestratorsCount === 0 ? 'link' : 'success';
            $orchestratorSubtitle .= $orchestratorsCount === 0 ? "
                <br><br>
                <a href='" . route('configuration.orchestrator') . "' class='button is-link'>
                    <span class='icon'><i class='fas fa-plus-circle'></i></span>
                    <span>Register a UiPath Orchestrator</span>
                </a>
            " : "";
            $orchestratorIcon = $orchestratorsCount === 0 ? 'server' : 'check-circle';

            $clientTitle = '2. Create your first Client';
            $clientSubtitle = '
                A <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-building"></i></span> Client</span> is a simple entity linked to a
                <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-server"></i></span> UiPath Orchestrator</span> in which you\'ll add
                <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-binoculars"></i></span> Processes to watch</span>. Furthermore,
                by specifying information on <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-chart-bar"></i></span> ElasticSearch</span>,
                you\'ll give access to your logs and extend your watching.
            ';
            $clientState = $orchestratorsCount === 0 ? 'grey' : ($clientsCount === 0 ? 'link' : 'success');
            $clientSubtitle .= $orchestratorsCount > 0 && $clientsCount === 0 ? "
                <br><br>
                <a href='" . route('configuration.client') . "' class='button is-link'>
                    <span class='icon'><i class='fas fa-plus-circle'></i></span>
                    <span>Create a Client</span>
                </a>
            " : "";
            $clientIcon = $clientsCount === 0 ? 'building' : 'check-circle';

            $watchedAutomatedProcessTitle = '3. Watch your first Automated Process';
            $watchedAutomatedProcessSubtitle = '
                In order to watch an automated processcreated with UiPath solution, you\'ll need to give information on it
                (eg: a name, a code, an execution time slot, etc.) but also identify the UiPath
                <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-sitemap"></i></span> Processes</span>,
                <span class="has-text-weight-medium"><span class="icon"><i class="fab fa-android"></i></span> Robots</span> and
                <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-layer-group"></i></span> Queues</span> involved. It will allow you to define
                alert triggers on these entities (and on others related to <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-chart-bar"></i></span> ElasticSearch</span>).
            ';
            $watchedAutomatedProcessState = $clientsCount === 0 ? 'grey' : ($watchedAutomatedProcessesCount === 0 ? 'link' : 'success');
            $watchedAutomatedProcessSubtitle .= $clientsCount > 0 && $watchedAutomatedProcessesCount === 0 ? "
                <br><br>
                <a href='" . route('configuration.watched-automated-process') . "' class='button is-link'>
                    <span class='icon'><i class='fas fa-plus-circle'></i></span>
                    <span>Watch an Automated Process</span>
                </a>
            " : "";
            $watchedAutomatedProcessIcon = $watchedAutomatedProcessesCount === 0 ? 'binoculars' : 'check-circle';

            $alertTriggerTitle = '4. Trigger your first Alert';
            $alertTriggerSubtitle = '
                The <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-dragon"></i></span> Alert trigger</span> is the final key component of UiPath Watcher.
                It allows you to define <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-burn"></i></span> Alerts</span> by
                applying rules based on <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-server"></i></span> UiPath Orchestrator</span> entities
                and <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-chart-bar"></i></span> ElasticSearch</span> logs.
                These <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-dragon"></i></span> Alert triggers</span>
                will be scanned every 5 minutes and may generate <span class="has-text-weight-medium"><span class="icon"><i class="fas fa-burn"></i></span> Alerts</span> to handle.
            ';
            $alertTriggerState = $watchedAutomatedProcessesCount === 0 ? 'grey' : ($alertTriggersCount === 0 ? 'link' : 'success');
            $alertTriggerSubtitle .= $watchedAutomatedProcessesCount > 0 ? "
                <br><br>
                <a href='" . route('configuration.alert-trigger') . "' class='button is-link'>
                    <span class='icon'><i class='fas fa-plus-circle'></i></span>
                    <span>Configure an Alert trigger</span>
                </a>
            " : "";
            $alertTriggerIcon = $alertTriggersCount === 0 ? 'dragon' : 'check-circle';
        @endphp

        @include('layouts.title', [
            'title' => $orchestratorTitle,
            'titleSize' => '4',
            'subtitle' => $orchestratorSubtitle,
            'subtitleSize' => '6',
            'icon' => $orchestratorIcon,
            'color' => $orchestratorState
        ])

        @include('layouts.title', [
            'title' => $clientTitle,
            'titleSize' => '4',
            'subtitle' => $clientSubtitle,
            'subtitleSize' => '6',
            'icon' => $clientIcon,
            'color' => $clientState
        ])

        @include('layouts.title', [
            'title' => $watchedAutomatedProcessTitle,
            'titleSize' => '4',
            'subtitle' => $watchedAutomatedProcessSubtitle,
            'subtitleSize' => '6',
            'icon' => $watchedAutomatedProcessIcon,
            'color' => $watchedAutomatedProcessState
        ])

        @include('layouts.title', [
            'title' => $alertTriggerTitle,
            'titleSize' => '4',
            'subtitle' => $alertTriggerSubtitle,
            'subtitleSize' => '6',
            'icon' => $alertTriggerIcon,
            'color' => $alertTriggerState
        ])
    </div>
</article>