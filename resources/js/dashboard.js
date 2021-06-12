import $ from 'jquery';
import {
  addFavorite, deleteBookmark, getBookmarkInfo,
} from './api';
import { flash } from './elements/Alert';

export default function itemMenu() {
  const btnMenu = document.querySelectorAll('.item');

  btnMenu.forEach((value) => {
    value.addEventListener('click', () => {
      const bookmarkId = value.parentNode.children[0].value;
      if (value.hasAttribute('favorite')) {
        addFavorite(bookmarkId, (response) => {
          flash(response.message, 'success', 2);
        });
      } else if (value.hasAttribute('delete')) {
        deleteBookmark(bookmarkId, (response) => {
          flash(response.message, 'success', 2);
        });
      } else if (value.hasAttribute('edit')) {
        const modal = document.getElementById('modal');
        getBookmarkInfo(bookmarkId, (response) => {
          document.getElementById('title-modal').value = response.data[0].title;
          document.getElementById('thumbnail-modal').value = response.data[0].thumbnail;
          document.getElementById('link-modal').value = response.data[0].link;
          document.getElementById('difficulty-modal').value = response.data[0].difficulty;
          document.getElementById('id_bookmarks').value = response.data[0].id;
          modal.style.display = 'block';
        });
      }else if (value.hasAttribute('info')) {
          const menu = document.getElementsByClassName('menu-info')[0];
          menu.parentNode.children[2].style.marginRight = '280px';
          menu.style.display = 'block';
      }
    });
  });
}
const close = document.getElementsByClassName('info-close')[0];

close.onclick = function(){
  close.parentNode.style.display = 'none';
  close.parentNode.parentNode.children[2].style.marginRight = '0px';
}

window.onclick = function (event) {
  if (event.target === modal) {
    modal.style.display = 'none';
  }
};
