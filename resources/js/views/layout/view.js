import { strings, elements, selectors } from './base';
import * as _base from '../base';

export const updateMenu = markup => {
    return _base.update(elements.menu, markup);
};

export const updateSidebar = markup => {
    return _base.update(elements.sidebar, markup);
};
