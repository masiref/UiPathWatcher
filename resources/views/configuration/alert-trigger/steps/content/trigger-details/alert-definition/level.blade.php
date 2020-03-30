<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <div class="control has-icons-left">
                <div class="select is-fullwidth is-{{ $level }}">
                    <select class="trigger-details--alert-definition--level-select" data-rank="{{ $rank }}">
                        <option value="info" {!! $level === 'info' ? 'selected' : '' !!}>Info</option>
                        <option value="warning" {!! $level === 'warning' ? 'selected' : '' !!}>Warning</option>
                        <option value="danger" {!! $level === 'danger' ? 'selected' : '' !!}>Danger</option>
                    </select>
                </div>
                <span class="icon is-small is-left">
                    <i class="fas fa-burn"></i>
                </span>
            </div>
        </div>
    </div>
</div>