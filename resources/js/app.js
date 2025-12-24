import * as bootstrap from 'bootstrap';
import '../scss/app.scss';

function initPopovers() {
    const popoverElements = document.querySelectorAll('[data-bs-toggle="popover"]');

    popoverElements.forEach(element => {
        try {
            new bootstrap.Popover(element, {
                trigger: 'hover',
                html: true,
                placement: 'top',
                container: 'body'
            });
        } catch (error) {
            console.warn('Ошибка инициализации popover:', error);
        }
    });
    
    console.log(`Инициализировано ${popoverElements.length} popover(s)`);
}

document.addEventListener('DOMContentLoaded', initPopovers);

if (typeof Turbolinks !== 'undefined') {
    document.addEventListener('turbolinks:load', initPopovers);
}

window.initPopovers = initPopovers;