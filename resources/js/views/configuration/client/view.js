import { strings, elements, selectors } from './base';
import * as _base from '../../base';

export const updateTable = markup => {
    const table = _base.update(
        document.querySelector(selectors.table).closest(_base.selectors.tableDataTablesWrapper),
        markup
    );
    $(selectors.table).DataTable();
    return table;
}