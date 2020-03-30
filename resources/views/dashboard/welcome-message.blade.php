<article class="message is-info">
    <div class="message-header">
        Hi there! Welcome to UiPath Watcher! You'll find below a quick start guide on how to use this tool. Happy watching!
    </div>
    <div class="message-body">
        @php
            $orchestratorTitle = '1. Register your first UiPath Orchestrator';
            $orchestratorSubtitle = '
                By registering a <strong><span class="icon"><i class="fas fa-server"></i></span> UiPath Orchestrator</strong>
                you\'ll be able to link your <strong><span class="icon"><i class="fas fa-building"></i></span> Clients</strong> to it,
                but also loading involved <strong><span class="icon"><i class="fas fa-robot"></i></span> Robots</strong> and
                <strong><span class="icon"><i class="fas fa-sitemap"></i></span> Processes</strong> you need to watch. Furthermore,
                by specifying information on <strong><span class="icon"><i class="fas fa-chart-bar"></i></span> ElasticSearch</strong>,
                you\'ll give access to your logs and extend your watching.
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
                A <strong><span class="icon"><i class="fas fa-building"></i></span> Client</strong> is a simple entity linked to a
                <strong><span class="icon"><i class="fas fa-server"></i></span> UiPath Orchestrator</strong> in which you\'ll add
                <strong><span class="icon"><i class="fas fa-binoculars"></i></span> Processes to watch</strong>.
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
                <strong><span class="icon"><i class="fas fa-sitemap"></i></span> Processes</strong>,
                <strong><span class="icon"><i class="fas fa-robot"></i></span> Robots</strong> and
                <strong><span class="icon"><i class="fas fa-layer-group"></i></span> Queues</strong> involved. It will allow you to define
                alert triggers on these entities (and on others related to <strong><span class="icon"><i class="fas fa-chart-bar"></i></span> ElasticSearch</strong>).
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
            $alertTriggerSubtitle = "
                ...
            ";
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