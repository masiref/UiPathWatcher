export const strings = {
    box: 'client-'
};

export const selectors = {
    boxes: '.client-box'
};

export const elements = {
    boxes: document.querySelectorAll(selectors.boxes),
    box: id => {
        return document.getElementById(strings.box + id);
    }
};