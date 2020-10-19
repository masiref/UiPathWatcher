<div class="level title-level {{ ($underlined ?? true) ? 'underlined' : '' }}" id="{{ $titleId ?? '' }}">
    <div class="level-left">
        <div class="level-item icon-item">
            <span class="icon is-{{ $iconSize ?? 'medium' }} has-text-{{ $color }}">
                <i class="{{ $iconType ?? false ? $iconType : 'fas' }} {{ ($iconSize ?? 'medium') === 'medium' ? 'fa-2x' : '' }} fa-{{ $icon }}"></i>
            </span>
        </div>
        <div class="level-item title-item">
            <p class="title has-text-{{ $color }} is-{{ $titleSize ?? '2' }}">{!! $title !!}</p>
        </div>
    </div>
    @if ($iconRight ?? false)
        <div class="level-right">
            <span class="icon is-{{ $iconRightSize ?? 'medium' }} has-text-{{ $iconRightColor }}">
                <i class="{{ $iconRightType ?? false ? $iconRightType : 'fas' }} {{ ($iconRightSize ?? 'medium') === 'medium' ? 'fa-2x' : '' }} fa-{{ $iconRight }}"></i>
            </span>
        </div>
    @endif
</div>
@if ($subtitle ?? false)
    <p
        class="subtitle has-text-{{ $color }} is-{{ $subtitleSize ?? '4' }}"
        id="{{ $subtitleId ?? '' }}">{!! $subtitle !!}</p>
@endif