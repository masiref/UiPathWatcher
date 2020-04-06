import * as _base from '../base';

import * as base from './base';
import * as view from './view';

export const update = async (layout) => {
    try {
        _base.renderLoader(base.elements.sidebar);
        _base.renderLoader(base.elements.hero);
        return layout.update().then(res => {
            base.elements.menu = view.updateMenu(layout.menu);
            base.elements.hero = view.updateHero(layout.hero);
            base.elements.sidebar = view.updateSidebar(layout.sidebar);
        });
    } catch (error) {
        _base.clearLoader(base.elements.sidebar);
        _base.clearLoader(base.elements.hero);
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

export const updateHero = async (layout) => {
    try {
        _base.renderLoader(base.elements.hero);
        return layout.updateHero().then(res => {
            base.elements.hero = view.updateHero(layout.hero);
        });
    } catch (error) {
        _base.clearLoader(base.elements.hero);
    }
};