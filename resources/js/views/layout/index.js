import * as _base from '../base';

import * as base from './base';
import * as view from './view';

export const updateMenu = async (dashboard) => {
    try {
        return dashboard.layout.updateMenu().then(res => {
            base.elements.menu = view.updateMenu(dashboard.layout.menu);
        });
    } catch (error) {
        _base.clearLoader(base.elements.menu);
    }
};

export const updateSidebar = async (dashboard) => {
    try {
        _base.renderLoader(base.elements.sidebar);
        return dashboard.layout.updateSidebar().then(res => {
            base.elements.sidebar = view.updateSidebar(dashboard.layout.sidebar);
        });
    } catch (error) {
        console.log(error);
        _base.clearLoader(base.elements.sidebar);
    }
};