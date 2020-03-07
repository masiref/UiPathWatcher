import { strings, elements, selectors } from './base';
import * as _base from '../base';

export const update = (id, markup) => {
    return _base.update(elements.box(id), markup);
};

const remove = (id) => {
    const box = elements.box(id);
    if (box)
        box.parentNode.removeChild(box);
};