import './bootstrap';
import toastr from 'toastr';
import * as Push from 'push.js';
import BulmaTagsInput from '@creativebulma/bulma-tagsinput';

import * as base from './views/base';
import * as view from './views/view';

import App from './models/App';

import * as dashboardController from './views/dashboard/index';
import * as orchestratorConfigurationController from './views/configuration/orchestrator/index';
import * as clientConfigurationController from './views/configuration/client/index';
import * as watchedAutomatedProcessConfigurationController from './views/configuration/watched-automated-process/index';
import * as alertTriggerConfigurationController from './views/configuration/alert-trigger/index';
import * as layoutController from './views/layout/index';

const url = window.location.href;
if (base.isDashboardRelatedURL(url)) {
    dashboardController.init();
} else if (base.isConfigurationOrchestratorRelatedURL(url)) {
    orchestratorConfigurationController.init();
} else if (base.isConfigurationClientRelatedURL(url)) {
    clientConfigurationController.init();
} else if (base.isConfigurationWatchedAutomatedProcessRelatedURL(url)) {
    watchedAutomatedProcessConfigurationController.init();
} else if (base.isConfigurationAlertTriggerRelatedURL(url)) {
    alertTriggerConfigurationController.init();
}

const app = new App();

export const showNotifications = () => {
    try {
        return new Promise((resolve, reject) => {
            resolve(
                app.getNotifications().then(result => {
                    const notifications = result.data;
                    notifications.forEach(notification => {
                        const title = notification.data.title;
                        const body = notification.data.body;
                        Push.create(title, {
                            body: body,
                            icon: '/images/surveillance_camera2.png',
                            onClick: () => {
                                window.focus();
                            }
                        });
                    });
                })
            );
        });
    } catch (error) {
        console.log(error);
    }
};

showNotifications();

base.elements.app.addEventListener('click', e => {
    const target = e.target;

    if (target.matches(`${base.selectors.shutdownAlertTriggersButton}, ${base.selectors.shutdownAlertTriggersButtonChildren}`)) {
        const modal = view.showShutdownAlertTriggersFormModal();
        modal.addEventListener('click', async (e) => {
            if (e.target.matches(`${base.selectors.validateModalButton}, ${base.selectors.validateModalButtonChildren}`)) {
                commitShutdownAlertTriggers();
            }
        });
    }

    if (target.matches(`${base.selectors.reactivateAlertTriggersButton}, ${base.selectors.reactivateAlertTriggersButtonChildren}`)) {
        const modal = view.showReactivateAlertTriggersFormModal();
        modal.addEventListener('click', async (e) => {
            if (e.target.matches(`${base.selectors.validateModalButton}, ${base.selectors.validateModalButtonChildren}`)) {
                commitReactivateAlertTriggers();
            }
        });
    }
});

const commitShutdownAlertTriggers = () => {
    try {
        const reasonTextarea = document.querySelector(base.selectors.shutdownAlertTriggersReasonTextarea);
        if (reasonTextarea.value.trim() === '') {
            toastr.error('Reason is mandatory!', null, {
                positionClass: 'toast-bottom-right'
            });
            reasonTextarea.classList.add('is-danger');
        } else {
            view.removeShutdownAlertTriggersFormModal();
            base.renderLoader(document.querySelector(base.selectors.shutdownAlertTriggersButton));
            try {
                app.shutdownAlertTriggers(reasonTextarea.value).then(response => {
                    layoutController.updateHero(app.layout);
                });
            } catch (error) {
                toastr.error(`Alert triggers not shutdown due to application exception: ${error}`, null, {
                    positionClass: 'toast-bottom-right'
                });
                base.clearLoader(document.querySelector(base.selectors.shutdownAlertTriggersButton));
            }
        }
    } catch (error) {
        console.log(error);
    }
}

const commitReactivateAlertTriggers = () => {
    try {
        const reasonTextarea = document.querySelector(base.selectors.reactivateAlertTriggersReasonTextarea);
        if (reasonTextarea.value.trim() === '') {
            toastr.error('Reason is mandatory!', null, {
                positionClass: 'toast-bottom-right'
            });
            reasonTextarea.classList.add('is-danger');
        } else {
            view.removeReactivateAlertTriggersFormModal();
            base.renderLoader(document.querySelector(base.selectors.reactivateAlertTriggersButton));
            try {
                app.reactivateAlertTriggers(reasonTextarea.value).then(response => {
                    layoutController.updateHero(app.layout);
                });
            } catch (error) {
                toastr.error(`Alert triggers not reactivated due to application exception: ${error}`, null, {
                    positionClass: 'toast-bottom-right'
                });
                base.clearLoader(document.querySelector(base.selectors.reactivateAlertTriggersButton));
            }
        }
    } catch (error) {
        console.log(error);
    }
}

// Bulma NavBar Burger Script
document.addEventListener('DOMContentLoaded', function () {
    // Get all "navbar-burger" elements
    const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
    
    // Check if there are any navbar burgers
    if ($navbarBurgers.length > 0) {
        
        // Add a click event on each of them
        $navbarBurgers.forEach(function ($el) {
            $el.addEventListener('click', function () {
                
                // Get the target from the "data-target" attribute
                let target = $el.dataset.target;
                let $target = document.getElementById(target);
                
                // Toggle the class on both the "navbar-burger" and the "navbar-menu"
                $el.classList.toggle('is-active');
                $target.classList.toggle('is-active');
                
            });
        });
    }
});

