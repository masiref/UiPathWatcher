<a class="panel-block">
    <span class="panel-icon">
        <i class="fas fa-stopwatch" aria-hidden="true"></i>
    </span>
    Between {{ $rule->timeSlotFromReadable() }} and {{ $rule->timeSlotUntilReadable() }}
    @if ($rule->has_relative_time_slot)
        during the last {{ $rule->relative_time_slot_duration }} minutes
    @endif
</a>