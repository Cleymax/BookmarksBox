import { addFavorite, removeFavorite, deleteBookmark, getBookmarkInfo, isFavorite, moveBookmark } from './api';
import { flash } from './elements/Alert';
import moment from 'moment';

export default function itemMenu() {
  const btnMenu = document.querySelectorAll('.item');

  btnMenu.forEach((value) => {
    value.addEventListener('click', (event) => {
      const bookmarkId = value.parentNode.children[0].value;
      if (value.hasAttribute('favorite')) {
        isFavorite(bookmarkId, (response) => {
          if(response.isFavorite === false){
            console.log("isFavorite is false");
            addFavorite(bookmarkId, (response) => {
              flash(response.message, 'success', 2);
            });
          }else{
            removeFavorite(bookmarkId, (response) => {
              flash(response.message, 'success', 2);
            });
          }
        });
      } else if (value.hasAttribute('delete')) {
        deleteBookmark(bookmarkId, (response) => {
          if(response.message === "Vous avez bien supprime cette bookmarks"){
            const bookmarks = document.querySelectorAll('.bookmark')
            bookmarks.forEach((bookmark) => {
              if(bookmark.getAttribute('bookmark-id') === bookmarkId){
                bookmark.parentNode.removeChild(bookmark);
              }
            });
            flash(response.message, 'success', 2);
          }else{
            flash(response.message, 'error', 2);
          }
        });
      } else if (value.hasAttribute('edit')) {
        const modal = document.getElementById('modal');
        getBookmarkInfo(bookmarkId).then((response) => {
          document.getElementById('title-modal').value = response.data[0].title;
          document.getElementById('thumbnail-modal').value = response.data[0].thumbnail;
          document.getElementById('link-modal').value = response.data[0].link;
          document.getElementById('difficulty-modal').value = response.data[0].difficulty;
          document.getElementById('id_bookmarks').value = response.data[0].id;
          modal.style.display = 'block';
        });
      } else if (value.hasAttribute('info')) {
        const content = document.getElementById('content');
        content.style.marginRight = '280px';
        const menuInfo = document.getElementById('menu-info');
        menuInfo.style.transform = 'translateX(0px)';
      }else if (value.hasAttribute('move')) {
        const menuMove = document.getElementById('moveMenu');
        menuMove.style.top = `${event.clientY}px`;
        menuMove.style.left = `${event.clientX-80}px`;
        moveBookmark();
        menuMove.parentNode.style.display = 'block';
      }
    });
  });
}

function closeModalInfo() {
  const menuInfo = document.getElementById('menu-info');
  menuInfo.style.transform = 'translateX(280px)';

  const content = document.getElementById('content');
  content.style.marginRight = '0px';
}

const close = document.getElementsByClassName('info-close')[0];

if (close) {
  close.onclick = closeModalInfo;
}

// window.onclick = function (event) {
//   if (event.target === modal) {
//     modal.style.display = 'none';
//   }
// };
