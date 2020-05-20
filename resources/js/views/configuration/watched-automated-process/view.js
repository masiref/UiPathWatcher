import { strings, elements, selectors } from './base';
import * as _base from '../../base';

export const updateTable = markup => {
    const table = _base.update(
        document.querySelector(selectors.table).closest(_base.selectors.tableDataTablesWrapper),
        markup
    );
    $(selectors.table).DataTable({
        responsive: true,
        select: {
            className: 'is-selected',
            info: false,
            toggleable: false
        }
    });
    return table;
};

export const showAddForm = () => {
    document.querySelector(selectors.editFormSection).style.display = 'none';
    document.querySelector(selectors.addFormSection).style.display = 'block';
};

export const showEditForm = () => {
    document.querySelector(selectors.addFormSection).style.display = 'none';
    document.querySelector(selectors.editFormSection).style.display = 'block';
};

export const updateEditFormSection = markup => {
    return _base.update(document.querySelector(selectors.editFormSection), markup);
};