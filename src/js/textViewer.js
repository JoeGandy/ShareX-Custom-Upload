hljs.initHighlightingOnLoad();

$(document).ready(function() {
    $('.code-box').css('padding-left', `${$('.line-numbers').outerWidth() + 15}px`);

    const updateTheme = () => {
        const userPrefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        const localSetting = localStorage.getItem('useDarkMode');

        if (localSetting === null) {
            if (userPrefersDark) {
                document.body.classList.remove('bootstrap');
                document.body.classList.add('bootstrap-dark');
                $('#highlightjs-dark-theme').prop('disabled', false);
                $('#highlightjs-light-theme').prop('disabled', true);
            } else {
                document.body.classList.remove('bootstrap-dark');
                document.body.classList.add('bootstrap');
                $('#highlightjs-dark-theme').prop('disabled', true);
                $('#highlightjs-light-theme').prop('disabled', false);
            }
        } else if (localSetting === 'true') {
            document.body.classList.remove('bootstrap');
            document.body.classList.add('bootstrap-dark');
            $('#highlightjs-dark-theme').prop('disabled', false);
            $('#highlightjs-light-theme').prop('disabled', true);
        } else {
            document.body.classList.remove('bootstrap-dark');
            document.body.classList.add('bootstrap');
            $('#highlightjs-dark-theme').prop('disabled', true);
            $('#highlightjs-light-theme').prop('disabled', false);
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

    $('.bi-code-slash').on('click', () => {
        const params = new URLSearchParams(window.location.search);
        params.set('raw', 'true');
        window.location.search = params.toString();
    });

    updateTheme();

    const codeClipboard = new ClipboardJS('.bi-clipboard', {
        text: () => {
            return $('.code-box')[0].textContent;
        }
    });

    codeClipboard.on('success', () => {
        $('.bi-clipboard').addClass('is-hidden');
        $('.bi-clipboard-check').removeClass('is-hidden');
        $('.bi-clipboard')[0].blur();
        $('.bi-clipboard-check')[0].focus();
        
        setTimeout(() => {
            $('.bi-clipboard').removeClass('is-hidden');
            $('.bi-clipboard-check').addClass('is-hidden');
            if (document.activeElement === $('.bi-clipboard-check')[0]) {
                $('.bi-clipboard-check')[0].blur();
                $('.bi-clipboard')[0].focus();
            }
        }, 3000);
    });
    
    codeClipboard.on('error', () => {
        $('.bi-clipboard').addClass('is-hidden');
        $('.bi-clipboard-minus').removeClass('is-hidden');
        $('.bi-clipboard')[0].blur();
        $('.bi-clipboard-minus')[0].focus();
        
        setTimeout(() => {
            $('.bi-clipboard').removeClass('is-hidden');
            $('.bi-clipboard-minus').addClass('is-hidden');
            if (document.activeElement === $('.bi-clipboard-minus')[0]) {
                $('.bi-clipboard-minus')[0].blur();
                $('.bi-clipboard')[0].focus();
            }
        }, 3000);
    });
});
$('[data-toggle="tooltip"]').tooltip();
