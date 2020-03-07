import { strings, elements, selectors } from './base';
import * as _base from '../base';

export const updateMenu = markup => {
    return _base.update(elements.menu, markup);
};

export const updateSidebar = markup => {
    return _base.update(elements.sidebar, markup);
};

export const updateClientTile = markup => {
    return _base.update(elements.clientTile, markup);
};

export const updateClientsTile = markup => {
    return _base.update(elements.clientsTile, markup);
};

export const updateWatchedAutomatedProcessesTile = markup => {
    return _base.update(elements.watchedAutomatedProcessesTile, markup);
};

export const updateRobotsTile = markup => {
    return _base.update(elements.robotsTile, markup);
};

export const updateNotClosedAlertsTile = markup => {
    return _base.update(elements.notClosedAlertsTile, markup);
};

export const updateUnderRevisionAlertsTile = markup => {
    return _base.update(elements.underRevisionAlertsTile, markup);
};

export const updateClosedAlertsTile = markup => {
    return _base.update(elements.closedAlertsTile, markup);
};