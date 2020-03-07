export const strings = {
    box: 'watched-automated-process-'
};

export const selectors = {
    boxes: '.watched-automated-process-box'
};

export const elements = {
    boxes: document.querySelectorAll(selectors.boxes),
    box: id => {
        return document.getElementById(strings.box + id);
    }
};