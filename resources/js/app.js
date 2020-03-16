import './bootstrap';
import * as _base from './views/base';
import * as dashboardController from './views/dashboard/index';
import * as uiPathOrchestratorController from './views/ui-path-orchestrator/index';

const url = window.location.href;
if (_base.isDashboardRelatedURL(url)) {
    dashboardController.init();
} else if (_base.isConfigurationOrchestratorRelatedURL(url)) {
    uiPathOrchestratorController.init();
}

// DataTables
$('.table').DataTable();

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

