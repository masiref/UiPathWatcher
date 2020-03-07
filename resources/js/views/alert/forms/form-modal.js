import moment from 'moment';

export const header = (title, alert) => {
    return `
        <header class="modal-card-head has-background-${alert.level}">
            <p class="modal-card-title has-text-light">
                <span class="icon"><i class="fas fa-burn"></i></span> ${title}
            </p>
            <button class="delete" aria-label="close"></button>
        </header>
    `;
};

export const footer = (successButtonLabel, successButtonIcon) => {
    return `
        <footer class="modal-card-foot">
            <div class="field is-grouped has-addons">
                <div class="control">
                    <button class="button is-success validate">
                        <span class="icon is-small">
                            <i class="fas fa-${successButtonIcon}"></i>
                        </span>
                        <span>${successButtonLabel}</span>
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
            </button>
        </footer>
    `;
};

export const titleBlock = alert => {
    return `
        <div class="columns">
            <div class="column">
                <div class="field">
                    <label class="label">Client</label>
                    <div class="control">
                        <input class="input is-static" type="text" value="${alert.watched_automated_process.client.name}" readonly>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Watched automated process</label>
                    <div class="control">
                        <input class="input is-static" type="text" value="${alert.watched_automated_process.name}" readonly>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="field">
                    <label class="label">Alert</label>
                    <div class="control">
                        <input class="input is-static" type="text" value="${alert.label}" readonly>
                    </div>
                </div>
                <div class="field">
                    <label class="label">Detected on</label>
                    <div class="control">
                        <input class="input is-static" type="text" value="${moment(alert.created_at).format('DD/MM/YYYY HH:mm:ss')}" readonly>
                    </div>
                </div>
            </div>
        </div>
    `;
};

export const underRevisionBlock = alert => {
    return `
        <article class="message is-info is-small">
            <div class="message-body">
                Revision started on ${moment(alert.revision_started_at).format('DD/MM/YYYY HH:mm:ss')}
                <small>
                    (${moment(alert.revision_started_at).fromNow()})
                </small>
            </div>
        </article>
    `;
};