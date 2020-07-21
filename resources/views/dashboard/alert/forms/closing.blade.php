<div id="alert-closing-modal" class="modal">
    <div class="modal-background"></div>
    <div class="modal-card modal-content">
        <header class="modal-card-head has-background-{{ $alert->definition->level }}">
            <p class="modal-card-title has-text-light">
                <span class="icon"><i class="fas fa-burn"></i></span> Alert closing
            </p>
            <button class="delete" aria-label="close"></button>
        </header>
        <section class="modal-card-body">
            <form class="alert-closing-form" method="POST">
                <div class="columns">
                    <div class="column">
                        <div class="field">
                            <label class="label">
                                <span class="icon"><i class="fas fa-building"></i></span>
                                <span>Client</span>
                            </label>
                            <div class="control">
                                <input class="input is-static" type="text" value="{{ $alert->watchedAutomatedProcess->client }}" readonly>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">
                                <span class="icon"><i class="fas fa-clock"></i></span>
                                <span>Detected on</span>
                            </label>
                            <div class="control">
                                <input class="input is-static" type="text" value="{{ $alert->createdAt() }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="column">
                        <div class="field">
                            <label class="label">
                                <span class="icon"><i class="fas fa-binoculars"></i></span>
                                <span>Watched automated process</span>
                            </label>
                            <div class="control">
                                <input class="input is-static" type="text" value="{{ $alert->watchedAutomatedProcess }}" readonly>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">
                                <span class="icon"><i class="fas fa-dragon"></i></span>
                                <span>Alert trigger</span>
                            </label>
                            <div class="control">
                                <input class="input is-static" type="text" value="{{ $alert->trigger->title }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <article class="message is-info is-small">
                    <div class="message-body">
                        Revision started on {{ $alert->revisionStartedAt() }}
                        <small>
                            ({{ $alert->revisionStartedAtDiffForHumans() }})
                        </small>
                    </div>
                </article>
                <div class="field">
                    <div class="control">
                        <input type="checkbox" id="closing-false-positive" name="false_positive" class="switch is-rounded">
                        <label for="closing-false-positive" class="checkbox">
                            Is it a false positive?
                        </label>
                    </div>
                </div>
                <div class="field has-addons">
                    <div class="control is-expanded">
                        <select multiple id="closing-keywords" data-type="tags" data-placeholder="Choose keywords">
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="control">
                        <button id="closing-add-keyword" class="button is-info" disabled>
                            <span class="icon is-small">
                                <i class="fas fa-plus-circle"></i>
                            </span>
                            <span>New</span>
                        </button>
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                        <textarea class="textarea"
                            id="closing-description"
                            name="closing-description"
                            placeholder="Resolution description"
                            required></textarea>
                    </div>
                </div>
            </form>
        </section>
        <footer class="modal-card-foot">
            <div class="field is-grouped has-addons">
                <div class="control">
                    <button class="button is-success validate">
                        <span class="icon is-small">
                            <i class="fas fa-fire-extinguisher"></i>
                        </span>
                        <span>Put out the fire!</span>
                    </button>
                </div>
                <div class="control">
                    <button class="button is-danger is-outlined cancel">
                        <span class="icon is-small">
                            <i class="fas fa-window-close"></i>
                        </span>
                        <span>Cancel</span>
                    </button>
                </div>
            </div>
        </footer>
    </div>
</div>