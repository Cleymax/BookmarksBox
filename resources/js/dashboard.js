import {
  addFavorite,
  removeFavorite,
  deleteBookmark,
  getBookmarkInfo,
  isFavorite,
  moveBookmark,
  moveItem,
  scrape,
  createBookmark
} from './api';
import { flash } from './elements/Alert';
import moment from 'moment';

OnClickMove();
onAdd();
scrapper();

export default function itemMenu() {
  const btnMenu = document.querySelectorAll('.item');

  btnMenu.forEach((value) => {
    value.addEventListener('click', (event) => {
      const bookmarkId = value.parentNode.children[0].value;
      if (value.hasAttribute('favorite')) {
        isFavorite(bookmarkId, (response) => {
          if(response.isFavorite === false){
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
        menuMove.children[0].value =  bookmarkId;
        menuMove.style.top = `${event.clientY}px`;
        menuMove.style.left = `${event.clientX-80}px`;
        moveBookmark();
        menuMove.parentNode.style.display = 'block';
      }
    });
  });
}

export function OnClickMove(){
  const btn = document.getElementById('move-btn');
  if(btn){
    btn.addEventListener('click', () => {
      const folderId = document.querySelector("folder-menu-row[moveSelected]").getAttribute('folder-id');
      const bookmarkId = document.getElementById('moveMenu').children[0].value;
      moveItem(bookmarkId, folderId).then((response) => {
        const bookmarks = document.querySelectorAll('.bookmark')
        bookmarks.forEach((bookmark) => {
          if(bookmark.getAttribute('bookmark-id') === bookmarkId){
            bookmark.parentNode.removeChild(bookmark);
          }
        });
        const menuMove = document.getElementById('moveMenu');
        menuMove.parentNode.style.display = 'none';
        flash(response.json().message, 'success', 2); /* Casser ça flash pas*/
      });
    });
  }
}

export function scrapper(){
  const btnAddBookmark = document.getElementById('btnAddBookmark');
  if(btnAddBookmark){
    btnAddBookmark.addEventListener('click', () => {
      const input = document.getElementById('link-addModal');
      scrape(input.value).then((response) => {
        const modalAdd = document.getElementById('modal-add');
        modalAdd.style.display = "none";
        const modal = document.getElementById('final-modal');
        document.getElementById('title-Finalmodal').value = response.data["title"];
        document.getElementById('thumbnail-Finalmodal').value = response.data["image"];
        document.getElementById('description-Finalmodal').value = response.data["description"];
        document.getElementById('link-Finalmodal').value = input.value;
        modal.style.display = 'block';
      });
    });
  }
}

export function onAdd(){
  const btnaddFolders = document.getElementById('addFolders');
  if(btnaddFolders){
    btnaddFolders.addEventListener('click', () => {
      const modal = document.getElementById('modal-add');
      const color = document.getElementById('color-addModal');
      const btn = document.getElementById('btnAddBookmark')
      if(btn){
        btn.setAttribute("id", "btnAddFolder")
      }
      color.parentNode.style.display = 'block';
      modal.style.display = 'block';
      document.getElementById('titleModalAdd').innerHTML = "Ajouter un dossier";
    });
  }
  const btnAddBookmark = document.getElementById('addBookmarks');
  if(btnAddBookmark){
    btnAddBookmark.addEventListener('click', () => {
      const btn = document.getElementById('btnAddFolder')
      if(btn){
        btn.setAttribute("id", "btnAddBookmark")
      }
      const modal = document.getElementById('modal-add');
      const title = document.getElementById('title-addModal');
      const link = document.getElementById('link-addModal');
      link.parentNode.style.display = 'block';
      title.parentNode.style.display = 'none';
      modal.style.display = 'block';
      document.getElementById('titleModalAdd').innerHTML = "Ajouter un bookmark";
    });
  }
  const finalBtnAdd = document.getElementById('finalBtnAdd');
  if(finalBtnAdd){
    finalBtnAdd.addEventListener('click', () => {
      const title = document.getElementById('title-Finalmodal').value;
      const thumbnail = document.getElementById('thumbnail-Finalmodal').value;
      const description = document.getElementById('description-Finalmodal').value;
      const difficulty = document.getElementById('difficulty-Finalmodal').value;
      const link = document.getElementById('link-Finalmodal').value;
      createBookmark(title, link, thumbnail, difficulty, description).then((response) => {
        flash("ça marche", "success", 2);
      });
      document.location.reload();
    });
  }
}

function resetAddModal(){
  const title = document.getElementById('title-addModal');
  const color = document.getElementById('color-addModal');
  const link = document.getElementById('link-addModal');
  title.parentNode.style.display = 'block';
  link.parentNode.style.display = 'none';
  color.parentNode.style.display = 'none';
  document.getElementById('titleModalAdd').innerHTML = "Ajouter un bookmark";
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

const closeMove = document.getElementById('closeMove');
if(closeMove) {
  closeMove.addEventListener('click', () => {
    closeMove.parentNode.parentNode.style.display = 'none';
  });
}

window.onclick = function (event) {
  if (event.target === modal) {
    modal.style.display = 'none';
  }
  const modalAdd = document.getElementById('modal-add');
  if(event.target === modalAdd){
    resetAddModal()
    modalAdd.style.display = 'none';
  }
};
