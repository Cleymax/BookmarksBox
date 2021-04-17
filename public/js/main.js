$(document).on('turbolinks:load', function () {
    let theme = localStorage.getItem('theme');
    if (theme != null) {
        $('#theme').prop("checked", theme === 'theme-light' ? false : true)
        $('body').addClass(theme)
    }
    $('#theme').click(function () {
        let body = $('body');
        if (body.hasClass('theme-light')) {
            body.removeClass('theme-light');
            body.addClass('theme-dark');
            localStorage.setItem('theme', 'theme-dark');
        } else {
            body.removeClass('theme-dark');
            body.addClass('theme-light');
            localStorage.setItem('theme', 'theme-light');
        }
    });
    document.querySelector("#show-password").addEventListener("click", function () {
        document.querySelectorAll('input[autocomplete="current-password"]').forEach(value => {
            if (value.type === "password") {
                value.type = "text";
            } else {
                value.type = "password";
            }
        });
    })
});

document.addEventListener('turbolinks:click', e => {
    const anchorElement = e.target
    const isSamePageAnchor =
        anchorElement.hash &&
        anchorElement.origin === window.location.origin &&
        anchorElement.pathname === window.location.pathname

    if (isSamePageAnchor) {
        Turbolinks.controller.pushHistoryWithLocationAndRestorationIdentifier(e.data.url, Turbolinks.uuid())
        e.preventDefault()
        window.dispatchEvent(new Event('hashchange'))
    }
});
