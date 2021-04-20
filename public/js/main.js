class Alert extends HTMLElement {

    constructor () {
        super()
        this.close = this.close.bind(this)
    }

    connectedCallback() {
        this.type = this.getAttribute('type')
        if (this.type === 'error' || !this.type) {
            this.type = 'danger'
        }
        const text = this.innerHTML
        const duration = this.getAttribute('duration')
        let progressBar = ''
        if (duration !== null) {
            progressBar = `<div class="alert__progress" style="animation-duration: ${duration}s">`
            window.setTimeout(this.close, duration * 1000)
        }
        this.classList.add('alert')
        this.classList.add(`alert-${this.type}`)
        this.innerHTML = `
        ${this.get_icon()}
        ${text}
        ${progressBar}`
        this.addEventListener('click', e => {
            e.preventDefault();
            this.close();
        });
    }

    close() {
        this.classList.add('out')
        window.setTimeout(async () => {
            if (this.parentElement !== null)
                this.parentElement.removeChild(this)
        }, 500)
    }

    get_icon() {
        if (this.type === 'success') {
            return `<svg x="0px" y="0px" viewBox="0 0 507.2 507.2">
<circle style="fill:#32BA7C;" cx="253.6" cy="253.6" r="253.6"/>
<path style="fill:#0AA06E;" d="M188.8,368l130.4,130.4c108-28.8,188-127.2,188-244.8c0-2.4,0-4.8,0-7.2L404.8,152L188.8,368z"/>
<g><path style="fill:#FFFFFF;" d="M260,310.4c11.2,11.2,11.2,30.4,0,41.6l-23.2,23.2c-11.2,11.2-30.4,11.2-41.6,0L93.6,272.8c-11.2-11.2-11.2-30.4,0-41.6l23.2-23.2c11.2-11.2,30.4-11.2,41.6,0L260,310.4z"/>
<path style="fill:#FFFFFF;" d="M348.8,133.6c11.2-11.2,30.4-11.2,41.6,0l23.2,23.2c11.2,11.2,11.2,30.4,0,41.6l-176,175.2c-11.2,11.2-30.4,11.2-41.6,0l-23.2-23.2c-11.2-11.2-11.2-30.4,0-41.6L348.8,133.6z"/>
</g>
</svg>`
        } else if (this.type === 'danger') {
            return `<svg x="0px" y="0px" viewBox="0 0 507.2 507.2">
<circle style="fill:#F15249;" cx="253.6" cy="253.6" r="253.6"/>
<path style="fill:#AD0E0E;" d="M147.2,368L284,504.8c115.2-13.6,206.4-104,220.8-219.2L367.2,148L147.2,368z"/>
<path style="fill:#FFFFFF;" d="M373.6,309.6c11.2,11.2,11.2,30.4,0,41.6l-22.4,22.4c-11.2,11.2-30.4,11.2-41.6,0l-176-176c-11.2-11.2-11.2-30.4,0-41.6l23.2-23.2c11.2-11.2,30.4-11.2,41.6,0L373.6,309.6z"/>
<path style="fill:#D6D6D6;" d="M280.8,216L216,280.8l93.6,92.8c11.2,11.2,30.4,11.2,41.6,0l23.2-23.2c11.2-11.2,11.2-30.4,0-41.6L280.8,216z"/>
<path style="fill:#FFFFFF;" d="M309.6,133.6c11.2-11.2,30.4-11.2,41.6,0l23.2,23.2c11.2,11.2,11.2,30.4,0,41.6L197.6,373.6c-11.2,11.2-30.4,11.2-41.6,0l-22.4-22.4c-11.2-11.2-11.2-30.4,0-41.6L309.6,133.6z"/>
</svg>`
        } else if (this.type === 'info') {
            return `<svg x="0px" y="0px" viewBox="0 0 512 512">
<path style="fill:#0A4EAF;" d="M256,512c-68.38,0-132.667-26.629-181.02-74.98C26.629,388.667,0,324.38,0,256S26.629,123.333,74.98,74.98C123.333,26.629,187.62,0,256,0s132.667,26.629,181.02,74.98C485.371,123.333,512,187.62,512,256s-26.629,132.667-74.98,181.02C388.667,485.371,324.38,512,256,512z"/>
<path style="fill:#063E8B;" d="M437.02,74.98C388.667,26.629,324.38,0,256,0v512c68.38,0,132.667-26.629,181.02-74.98C485.371,388.667,512,324.38,512,256S485.371,123.333,437.02,74.98z"/>
<path style="fill:#FFFFFF;" d="M256,185c-30.327,0-55-24.673-55-55s24.673-55,55-55s55,24.673,55,55S286.327,185,256,185z M301,395V215H191v30h30v150h-30v30h140v-30H301z"/>
<g><path style="fill:#CCEFFF;" d="M256,185c30.327,0,55-24.673,55-55s-24.673-55-55-55V185z"/><polygon style="fill:#CCEFFF;" points="301,395 301,215 256,215 256,425 331,425 331,395 \t"/></g>`;
        }
    }
}

window.customElements.define('alert-message', Alert);
$(document).ready(function() {
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
    $("#show-password").on('click', function () {
        document.querySelectorAll('input[autocomplete="current-password"]').forEach(value => {
            if (value.type === "password") {
                value.type = "text";
            } else {
                value.type = "password";
            }
        });
    });
});
// });
/*
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
});*/

