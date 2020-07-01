import { strings, elements, selectors } from './base';
import * as _base from '../base';
import bulmaCollapsible from '@creativebulma/bulma-collapsible';

export const update = (id, markup) => {
    const client = _base.update(elements.box(id), markup);
    bulmaCollapsible.attach(`#${strings.boxCollapsibleContent}${id}`);
    return client;
};

const remove = (id) => {
    const box = elements.box(id);
    if (box)
        box.parentNode.removeChild(box);
};