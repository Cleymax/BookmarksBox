import 'regenerator-runtime/runtime';
import $ from 'jquery';
import Alert from './elements/Alert';
import Skeleton from './elements/Skeleton';
import Tooltip from './elements/Tooltip';
import registerWindowHeightCSS from './window';
import joinTeamsWithCode from './Teams';
import registerTab from './tab';
import registerAdminChangeRole from './admin';
import { getWindowWidth } from './dom';
import { removeFavorite } from './api';
import registerTeam from './team';
import FilesUploader from './elements/FilesUploader';
import registerSortableTable from './table';
import registerCopyClipboard from './CopyClipboard';

registerWindowHeightCSS();
registerTab();
registerAdminChangeRole();
registerTeam();
registerSortableTable();
registerCopyClipboard();

window.customElements.define('alert-message', Alert);
window.customElements.define('skeleton-box', Skeleton);
window.customElements.define('files-uploader', FilesUploader);

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
  const tooltippeds = document.querySelectorAll('.tooltipped');
  for (let i = 0; i < tooltippeds.length; i += 1) {
    // eslint-disable-next-line no-new
    new Tooltip(tooltippeds[i]);
  }
  const code = document.getElementById('code');
  if (code) {
    code.addEventListener('keyup', (event) => {
      if (event.keyCode === 13) {
        event.preventDefault();
        joinTeamsWithCode();
      }
    });
  }

  const hamburger = document.getElementById('hamburger');
  if (hamburger) {
    const menu = document.getElementById('menu');
    const content = document.getElementById('content');
    hamburger.addEventListener('click', () => {
      const width = parseInt(`${getWindowWidth()}`, 10);
      if (menu.style.transform) {
        menu.style.transform = '';
        if (width < 600) {
          content.style.display = 'none';
        }
        content.style.marginLeft = '280px';
      } else {
        if (width < 600) {
          menu.style.transform = 'translateX(-100%)';
        } else {
          menu.style.transform = 'translateX(-280px)';
        }
        content.style.marginLeft = '0px';
        content.style.display = 'block';
      }
    });
  }

  document.querySelectorAll('#remove-favorite').forEach((value) => {
    value.addEventListener('click', () => {
      const box = value.parentNode.parentNode;
      const id = box.getAttribute('data-id');
      removeFavorite(id, (data) => {
        if (data) {
          box.classList.add('out');
          setTimeout(() => {
            box.parentNode.removeChild(box);
          }, 600);
        }
      });
    });
  });
  $('#join-team').on('click', () => {
    joinTeamsWithCode();
  });
});

document.querySelectorAll('.bookmark').forEach((value) => {
  value.addEventListener('contextmenu', (event) => {
    event.preventDefault();
    const contextElement = document.getElementById('context-menu');
    contextElement.style.top = `${event.clientY}px`;
    contextElement.style.left = `${event.clientX}px`;
    contextElement.classList.add('active');
  });
});

window.addEventListener('click', () => {
  document.getElementById('context-menu').classList.remove('active');
});
