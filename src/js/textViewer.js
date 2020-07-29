hljs.initHighlightingOnLoad();

$(document).ready(function() {
    $('.code-box').css('padding-left', `${$('.line-numbers').outerWidth() + 15}px`);

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

    $('.bi-code-slash').on('click', () => {
        const params = new URLSearchParams(window.location.search);
        params.set('raw', 'true');
        window.location.search = params.toString();
    });

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
