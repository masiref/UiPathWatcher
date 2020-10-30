<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <p class="control is-expanded has-icons-left">
                <input class="input label_" type="text" placeholder="Label"
                    value="{{ ($robotTool ?? false) ? $robotTool->label : '' }}">
                <span class="icon is-small is-left">
                    <i class="fas fa-signature"></i>
                </span>
            </p>
        </div>
        <div class="field">
            <p class="control is-expanded has-icons-left">
                <input class="input process-name" type="text" placeholder="Process name"
                    value="{{ ($robotTool ?? false) ? $robotTool->process_name : '' }}">
                <span class="icon is-small is-left">
                    <i class="fas fa-sitemap"></i>
                </span>
            </p>
        </div>
    </div>
</div>
<div class="field is-horizontal">
    <div class="field-body">
        <div class="field">
            <div class="control has-icons-left">
                <div class="select is-fullwidth">
                    <select class="color">
                        <option value="none">Select a color</option>
                        <option value="yellow" {!! ($robotTool ?? false) && $robotTool->color === 'yellow' ? 'selected' : '' !!}>Yellow</option>
                        <option value="success" {!! ($robotTool ?? false) && $robotTool->color === 'success' ? 'selected' : '' !!}>Green</option>
                        <option value="primary" {!! ($robotTool ?? false) && $robotTool->color === 'primary' ? 'selected' : '' !!}>Turquoise</option>
                        <option value="info" {!! ($robotTool ?? false) && $robotTool->color === 'info' ? 'selected' : '' !!}>Cyan</option>
                        <option value="link" {!! ($robotTool ?? false) && $robotTool->color === 'link' ? 'selected' : '' !!}>Blue</option>
                        <option value="danger" {!! ($robotTool ?? false) && $robotTool->color === 'danger' ? 'selected' : '' !!}>Red</option>
                        <option value="black" {!! ($robotTool ?? false) && $robotTool->color === 'black' ? 'selected' : '' !!}>Black</option>
                        <option value="light" {!! ($robotTool ?? false) && $robotTool->color === 'light' ? 'selected' : '' !!}>Grey</option>
                        <option value="white" {!! ($robotTool ?? false) && $robotTool->color === 'white' ? 'selected' : '' !!}>White</option>
                    </select>
                </div>
                <span class="icon is-small is-left">
                    <i class="fas fa-paint-brush"></i>
                </span>
            </div>
        </div>
    </div>
</div>