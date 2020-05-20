import moment from 'moment';
import { header, footer, titleBlock, underRevisionBlock } from './form-modal';

export const markup = (elementID, alert) => {
    return `
        <div id="${elementID}" class="modal">
            <div class="modal-background"></div>
            <div class="modal-card modal-content">
                ${header('Alert trigger ignorance', alert)}
                <section class="modal-card-body">
                    <form class="alert-closing-form" method="POST">
                        ${titleBlock(alert)}
                        ${underRevisionBlock(alert)}
                        <div class="field">
                            <label class="label">Ignore alert trigger in range</label>
                            <div class="control">
                                <input type="date" class="datetime"
                                    id="ignorance_calendar"
                                    name="ignorance_calendar">
                            </div>
                        </div>
                        <div class="field">
                            <div class="control">
                                <textarea class="textarea"
                                    id="ignorance_description"
                                    name="ignorance_description"
                                    placeholder="Ignorance description"
                                    required></textarea>
                            </div>
                        </div>
                    </form>
                </section>
                ${footer('Ignore it!', 'eye-slash')}
            </div>
        </div>
    `;
};