import * as _base from '../base';

import * as base from './base';
import * as view from './view';

export const update = async (layout) => {
    try {
        _base.renderLoader(base.elements.sidebar);
        return layout.update().then(res => {
            base.elements.menu = view.updateMenu(layout.menu);
            base.elements.sidebar = view.updateSidebar(layout.sidebar);
        });
    } catch (error) {
        _base.clearLoader(base.elements.menu);
        _base.clearLoader(base.elements.sidebar);
    }
};

export const updateMenu = async (layout) => {
    try {
        return layout.updateMenu().then(res => {
            base.elements.menu = view.updateMenu(layout.menu);
        });
    } catch (error) {
        _base.clearLoader(base.elements.menu);
    }
};

export const updateSidebar = async (layout) => {
    try {
        _base.renderLoader(base.elements.sidebar);
        return layout.updateSidebar().then(res => {
            base.elements.sidebar = view.updateSidebar(layout.sidebar);
        });
    } catch (error) {
        _base.clearLoader(base.elements.sidebar);
    }
};