@inject('alertTriggerService', 'App\Library\Services\AlertTriggerService')

<section class="hero is-{{ $alertTriggerService->isUnderShutdown() ? 'dark' : 'info' }} welcome is-small">
    <div class="hero-body">
        <div class="container">
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <h1 class="title">
                            Hello, {{ Auth::user()->name }}.
                        </h1>
                    </div>
                </div>
                {{--@if ($alertTriggersCount > 0)--}}
                    @if (!$alertTriggerService->isUnderShutdown())
                        <div class="level-right">
                            <div class="level-item">
                                <button class="button is-danger is-large" id="shutdown-alert-triggers">
                                    <span class="icon"><i class="fas fa-power-off"></i></span>
                                    <span>Shutdown alert triggers</span>
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="level-right">
                            <div class="level-item">
                                <button class="button is-success is-large" id="reactivate-alert-triggers">
                                    <span class="icon"><i class="fas fa-plug"></i></span>
                                    <span>Reactivate alert triggers</span>
                                </button>
                            </div>
                        </div>
                    @endif
                {{--@endif--}}
            </div>
            <div class="level">
                <div class="level-left">
                    <div class="level-item">
                        <h2 class="subtitle">
                            @if ($alertTriggerService->isUnderShutdown())
                                Alert triggers are under shutdown: {{ $alertTriggerService->currentShutdown()->reason }}
                            @else
                                {{ $message ?? "Let's watch your bots!" }}
                            @endif
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>