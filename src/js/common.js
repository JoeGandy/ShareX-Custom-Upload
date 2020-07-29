$(document).ready(function() {
    $('.bi').on('keyup', (e) => {
        if (e.keyCode === 32 || e.keyCode === 13) {
            $(e.target).click();
        }
    });

    $('.bi-moon').on('click', () => {
        localStorage.setItem('useDarkMode', true);
        window.__updateTheme();
        $('.bi-sun')[0].focus();
    });

    $('.bi-sun').on('click', () => {
        localStorage.setItem('useDarkMode', false);
        window.__updateTheme();
        $('.bi-moon')[0].focus();
    });

    $('.bi-arrow-clockwise').on('click', () => {
        localStorage.removeItem('useDarkMode');
        window.__updateTheme()
    });
});
$('[data-toggle="tooltip"]').tooltip();

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('./service-worker.js').then(() => {
        console.log('Service worker registered. This site can now be installed as a PWA.');
    }).catch((error) => {
        console.log('Service worker registration failed with ' + error);
    });
}
