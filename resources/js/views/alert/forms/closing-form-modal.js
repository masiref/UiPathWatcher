import moment from 'moment';
import { header, footer, titleBlock, underRevisionBlock } from './form-modal';

export const markup = (elementID, alert) => {
    return `
        <div id="${elementID}" class="modal">
            <div class="modal-background"></div>
            <div class="modal-card modal-content">
                ${header('Alert closing', alert)}
                <section class="modal-card-body">
                    <form class="alert-closing-form" method="POST">
                        ${titleBlock(alert)}
                        ${underRevisionBlock(alert)}
                        <div class="field">
                            <div class="control">
                                <input type="checkbox" id="false_positive" name="false_positive" class="switch is-rounded">
                                <label for="false_positive" class="checkbox">
                                    It is a false positive
                                </label>
                            </div>
                        </div>
                        <div class="field">
                            <div class="control">
                                <textarea class="textarea"
                                    id="closing_description"
                                    name="closing_description"
                                    placeholder="Resolution description"
                                    required></textarea>
                            </div>
                        </div>
                    </form>
                </section>
                ${footer('Put out the fire!', 'fire-extinguisher')}
            </div>
        </div>
    `;
};