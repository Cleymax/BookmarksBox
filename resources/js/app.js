import 'regenerator-runtime/runtime';
import $ from 'jquery';
import Alert from './elements/Alert';
import Skeleton from './elements/Skeleton';

window.customElements.define('alert-message', Alert);
window.customElements.define('skeleton-box', Skeleton);

$(document).ready(() => {
  const themeswitch = $('#theme');
  const theme = localStorage.getItem('theme');
  if (theme != null) {
    themeswitch.prop('checked', theme !== 'theme-light');
    $('body').addClass(theme);
  }
  themeswitch.click(() => {
    const body = $('body');
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
  $('#show-password').on('click', () => {
    document.querySelectorAll('input[autocomplete="current-password"]').forEach((value) => {
      if (value.type === 'password') {
        value.type = 'text';
      } else {
        value.type = 'password';
      }
    });
  });
});
