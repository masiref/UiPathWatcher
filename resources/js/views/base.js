import Swal from 'sweetalert2';

export const selectors = {
    closeModalTriggers: '.modal button.delete, .modal button.cancel, .modal button.cancel *',
    validateModalButton: '.modal button.validate',
    validateModalButtonChildren: '.modal button.validate *'
};

export const elements = {
};

export const update = (old, markup) => {
    if (old) {
        var node = htmlToElement(markup);
        old.parentNode.replaceChild(node, old);
        return node;
    }
};

export const htmlToElement = html => {
    var template = document.createElement('template');
    template.innerHTML = html;
    return template.content.firstChild;
};

export const htmlToElements = html => {
    var template = document.createElement('template');
    template.innerHTML = html;
    return template.content.childNodes;
};

export const renderLoader = parent => {
    if (parent)
        parent.classList.add('is-loading');
};

export const clearLoader = parent => {
    if (parent)
        parent.classList.remove('is-loading');
};

export const animateCSS = (node, animationName, callback) => {
    node.classList.add('animated', animationName)
    function handleAnimationEnd() {
        node.classList.remove('animated', animationName)
        node.removeEventListener('animationend', handleAnimationEnd)

        if (typeof callback === 'function') callback()
    }
    node.addEventListener('animationend', handleAnimationEnd)
};

export const showModal = modal => {
    modal.classList.add('is-active');
    animateCSS(modal, 'slideInUp');
    modal.addEventListener('click', e => {
        if (e.target.matches(selectors.closeModalTriggers)) {
            closeModal(modal);
        }
    });
};

export const closeModal = modal => {
    animateCSS(modal, 'slideOutDown', () => {
        modal.classList.remove('is-active');
        modal.parentNode.removeChild(modal);
    });
};

export const swalWithBulmaButtons = Swal.mixin({
    customClass: {
        confirmButton: 'button is-success',
        cancelButton: 'button is-danger'
    },
    buttonsStyling: false
});

export const isDashboardRelatedURL = url => {
    let locationIsDashboardRelated = /.*\/dashboard\/.*|.*\/$/;
    return locationIsDashboardRelated.test(url);
};

export const getClientIDFromURL = url => {
    let locationHasClient = /.*\/client\/(\d)$/;
    if (locationHasClient.test(url)) {
        return url.match(locationHasClient)[1];
    }
    return null;
};

export const isUserRelatedURL = url => {
    let locationIsUserRelated = /.*\/user$/;
    return locationIsUserRelated.test(url);
};