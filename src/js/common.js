$(document).ready(function() {
    const updateTheme = () => {
        const userPrefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        const localSetting = localStorage.getItem('useDarkMode');

        const themedElements = $('body');

        if (localSetting === null) {
            if (userPrefersDark) {
                themedElements.removeClass('bootstrap');
                themedElements.addClass('bootstrap-dark');
            } else {
                themedElements.removeClass('bootstrap-dark');
                themedElements.addClass('bootstrap');
            }
        } else if (localSetting === 'true') {
            themedElements.removeClass('bootstrap');
            themedElements.addClass('bootstrap-dark');
        } else {
            themedElements.removeClass('bootstrap-dark');
            themedElements.addClass('bootstrap');
        }
    };

    $('.bi').on('keyup', (e) => {
        if (e.keyCode === 32 || e.keyCode === 13) {
            $(e.target).click();
        }
    });

    $('.bi-moon').on('click', () => {
        localStorage.setItem('useDarkMode', true);
        updateTheme();
        $('.bi-sun')[0].focus();
    });

    $('.bi-sun').on('click', () => {
        localStorage.setItem('useDarkMode', false);
        updateTheme();
        $('.bi-moon')[0].focus();
    });

    $('.bi-arrow-clockwise').on('click', () => {
        localStorage.removeItem('useDarkMode');
        updateTheme()
    });

    updateTheme();
});
$('[data-toggle="tooltip"]').tooltip();

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('./service-worker.js').then(() => {
        console.log('Service worker registered. This site can now be installed as a PWA.');
    }).catch((error) => {
        console.log('Service worker registration failed with ' + error);
    });
}
