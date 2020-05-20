<div id="alert-{{ $alert->id }}" class="content alert-box p-md">
    <div class="alert-box__content">
        @include('dashboard.alert.general-info')
        <div class="alert-box__footer">
            @include('dashboard.alert.under-revision-info')
            @include('dashboard.alert.buttons')
        </div>
    </div>
</div>