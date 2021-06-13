import { addFavorite, deleteBookmark, getBookmarkInfo } from './api';
import { flash } from './elements/Alert';

export default function itemMenu() {
  const btnMenu = document.querySelectorAll('.item');

  btnMenu.forEach((value) => {
    value.addEventListener('click', () => {
      const bookmarkId = value.parentNode.children[0].value;
      if (value.hasAttribute('favorite')) {
        addFavorite(bookmarkId).then((response) => {
          flash(response.message, 'success', 2);
        });
      } else if (value.hasAttribute('delete')) {
        deleteBookmark(bookmarkId, (response) => {
          flash(response.message, 'success', 2);
        });
      } else if (value.hasAttribute('edit')) {
        const modal = document.getElementById('modal');
        getBookmarkInfo(bookmarkId).then((response) => {
          document.getElementById('title-modal').value = response.data[0].title;
          document.getElementById('thumbnail-modal').value = response.data[0].thumbnail;
          document.getElementById('link-modal').value = response.data[0].link;
          document.getElementById('difficulty-modal').value = response.data[0].difficulty;
          document.getElementById('id_bookmarks').value = response.data[0].id;
          modal.style.transform = 'translateX(0px)';
        });
      } else if (value.hasAttribute('info')) {
        const content = document.getElementById('content');
        content.style.marginRight = '280px';

        const menuInfo = document.getElementById('menu-info');
        menuInfo.style.transform = 'translateX(0px)';
      }
    });
  });
}

function closeModalInfo() {
  const menuInfo = document.getElementById('menu-info');
  menuInfo.style.transform = 'translateX(280px)';

  setTimeout(() => {
    const content = document.getElementById('content');
    content.style.marginRight = '0px';
  }, 100);
}

const close = document.getElementsByClassName('info-close')[0];

if (close) {
  close.onclick = () => {
    closeModalInfo();
  };
}
