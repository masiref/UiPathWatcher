export const markup = () => {
    return `
        <div id="reactivate-alert-triggers-modal" class="modal">
            <div class="modal-background"></div>
            <div class="modal-card modal-content">
                <header class="modal-card-head has-background-success">
                    <p class="modal-card-title has-text-light">
                        <span class="icon"><i class="fas fa-plug"></i></span> Reactivate alert triggers
                    </p>
                    <button class="delete" aria-label="close"></button>
                </header>
                <section class="modal-card-body">
                    <form class="reactivate-alert-triggers-form" method="POST">
                        <div class="field">
                            <div class="control">
                                <textarea class="textarea"
                                    id="reason"
                                    name="reason"
                                    placeholder="Reason"
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
                                    <i class="fas fa-plug"></i>
                                </span>
                                <span>Reactivate everything up!</span>
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
            </div>
        </div>
    `;
};