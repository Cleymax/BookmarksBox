import {
  addFavorite,
  createBookmark,
  createFolder,
  deleteBookmark,
  deleteFolder,
  getBookmarkInfo,
  isFavorite,
  isFolder,
  moveBookmark,
  moveFolder,
  moveItem,
  removeFavorite,
  scrape,
} from './api';
import {flash} from './elements/Alert';

const btnMenu = document.querySelectorAll('.item');

btnMenu.forEach((value) => {
  value.addEventListener('click', (event) => {
    const bookmarkId = value.parentNode.children[0].value;
    if (value.hasAttribute('favorite')) {
      isFavorite(bookmarkId, (response) => {
        if (response.isFavorite === false) {
          addFavorite(bookmarkId, (response) => {
            flash(response.message, 'success', 2);
          });
        } else {
          removeFavorite(bookmarkId, (response) => {
            flash(response.message, 'success', 2);
          });
        }
      });
    } else if (value.hasAttribute('delete')) {
      isFolder(bookmarkId).then((isFolder) => {
        if (isFolder.result != false) {
          deleteBookmark(bookmarkId, (response) => {
            if (response.message === 'Vous avez bien supprime cette bookmarks') {
              const bookmarks = document.querySelectorAll('.bookmark');
              bookmarks.forEach((bookmark) => {
                if (bookmark.getAttribute('bookmark-id') === bookmarkId) {
                  bookmark.parentNode.removeChild(bookmark);
                }
              });
              flash(response.message, 'success', 2);
            } else {
              flash(response.message, 'error', 2);
            }
          });
        } else {
          deleteFolder(bookmarkId, (response) => {
            const folder = document.querySelectorAll('.folder');
            folder.forEach((folder) => {
              if (folder.getAttribute('folder-id') === bookmarkId) {
                console.log(folder.parentNode);
                folder.parentNode.parentNode.removeChild(folder.parentNode);
              }
            });
            flash(response.message, 'success', 2);
          });
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
    } else if (value.hasAttribute('move')) {
      const menuMove = document.getElementById('moveMenu');
      menuMove.children[0].value = bookmarkId;
      menuMove.style.top = `${event.clientY}px`;
      menuMove.style.left = `${event.clientX - 80}px`;
      moveBookmark();
      menuMove.parentNode.style.display = 'block';
    }
  });
});

const btn = document.getElementById('move-btn');
if (btn) {
  btn.addEventListener('click', () => {
    const folderId = document.querySelector('folder-menu-row[moveSelected]').getAttribute('folder-id');
    const bookmarkId = document.getElementById('moveMenu').children[0].value;
    isFolder(bookmarkId).then((isFolder) => {
      if (isFolder.result !== false) {
        moveItem(bookmarkId, folderId).then((response) => {
          const bookmarks = document.querySelectorAll('.bookmark');
          bookmarks.forEach((bookmark) => {
            if (bookmark.getAttribute('bookmark-id') === bookmarkId) {
              bookmark.parentNode.removeChild(bookmark);
            }
          });
          const menuMove = document.getElementById('moveMenu');
          menuMove.parentNode.style.display = 'none';
          flash(response.json().message, 'success', 2); /* Casser ça flash pas */
        });
      } else {
        moveFolder(bookmarkId, folderId).then((response) => {
          const folders = document.querySelectorAll('.folder');
          folders.forEach((folder) => {
            if (folder.getAttribute('folder-id') === bookmarkId) {
              folder.parentNode.parentNode.removeChild(folder.parentNode);
            }
          });
          const menuMove = document.getElementById('moveMenu');
          menuMove.parentNode.style.display = 'none';
          flash(response.json().message, 'success', 2); /* Casser ça flash pas */
        });
      }
    });
  });
}

const btnAddBookmark = document.getElementById('btnAddBookmark');
if (btnAddBookmark) {
  btnAddBookmark.addEventListener('click', () => {
    const input = document.getElementById('link-addModal');
    scrape(input.value).then((response) => {
      const modalAdd = document.getElementById('modal-add');
      modalAdd.style.display = 'none';
      const modal = document.getElementById('final-modal');
      document.getElementById('title-Finalmodal').value = response.data.title;
      document.getElementById('thumbnail-Finalmodal').value = response.data.image;
      document.getElementById('description-Finalmodal').value = response.data.description;
      document.getElementById('link-Finalmodal').value = input.value;
      modal.style.display = 'block';
    });
  });
}

const btnaddFolders = document.getElementById('addFolders');
if (btnaddFolders) {
  btnaddFolders.addEventListener('click', () => {
    const modal = document.getElementById('modal-add-folder');
    modal.style.display = 'block';
  });
}
const btnAddBookmark2 = document.getElementById('addBookmarks');
if (btnAddBookmark2) {
  btnAddBookmark2.addEventListener('click', () => {
    const modal = document.getElementById('modal-add');
    const title = document.getElementById('title-addModal');
    const link = document.getElementById('link-addModal');
    link.parentNode.style.display = 'block';
    title.parentNode.style.display = 'none';
    modal.style.display = 'block';
    document.getElementById('titleModalAdd').innerHTML = 'Ajouter un bookmark';
  });
}
const btnAddFolder = document.getElementById('btnAddFolder');
if (btnAddFolder) {
  btnAddFolder.addEventListener('click', () => {
    const color = document.getElementById('color-addModal').value;
    const name = document.getElementById('title-addModal').value;
    createFolder(name, color, window.BB.FOLDER_ID, window.BB.TEAM_ID).then(() => {
      document.location.reload();
    });
  });
}
const finalBtnAdd = document.getElementById('finalBtnAdd');
if (finalBtnAdd) {
  finalBtnAdd.addEventListener('click', () => {
    const title = document.getElementById('title-Finalmodal').value;
    const thumbnail = document.getElementById('thumbnail-Finalmodal').value;
    const description = document.getElementById('description-Finalmodal').value;
    const difficulty = document.getElementById('difficulty-Finalmodal').value;
    const link = document.getElementById('link-Finalmodal').value;
    createBookmark(title, link, thumbnail, difficulty, description, window.BB.FOLDER_ID, window.BB.TEAM_ID).then((response) => {
      document.location.reload();
      flash('ça marche', 'success', 2);
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

const closeMove = document.getElementById('closeMove');
if (closeMove) {
  closeMove.addEventListener('click', () => {
    closeMove.parentNode.parentNode.style.display = 'none';
  });
}
const modal = document.getElementById('modal');
if (modal) {
  window.onclick = function (event) {
    if (event.target === modal) {
      modal.style.display = 'none';
    }
    const modalAdd = document.getElementById('modal-add');
    if (event.target === modalAdd) {
      modalAdd.style.display = 'none';
    }
    const modalAdd2 = document.getElementById('modal-add-folder');
    if (event.target === modalAdd2) {
      modalAdd2.style.display = 'none';
    }
  };
}
