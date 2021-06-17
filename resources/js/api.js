import Swal from 'sweetalert2';
import $ from 'jquery';
import { flash } from './elements/Alert';

const BASE_URL = `${window.BB.BASE_URL}/api/`;

export default async function jsonFetch(url, params = {}) {
  if (params.body instanceof FormData) {
    params.body = Object.fromEntries(params.body);
  }

  if (params.body && typeof params.body === 'object') {
    params.body = JSON.stringify(params.body);
  }
  params = {
    headers: {
      'Content-Type': 'application/json',
      Accept: 'application/json',
      'X-Requested-With': 'XMLHttpRequest',
    },
    ...params,
  };

  const response = await fetch(`${BASE_URL}${url}`, params);
  if (response.status === 204) {
    return null;
  }
  const data = await response.json();
  if (data.status === 'ok') {
    return data.response;
  }

  flash(data.message, 'error', 5);
  return null;
}

/**
 * Change the role of a user in a team.
 * @param teamId The Team
 * @param userId The user
 * @param roleId The role
 * @returns {boolean} if success
 */
export function changeRole(teamId, userId, roleId, response) {
  jsonFetch(`teams/${teamId}/members/${userId}/role`, {
    body: {
      role: roleId,
    },
    method: 'post',
  })
    .then(response);
}

export async function changeTeamFavorite(team, response) {
  jsonFetch(`user/teams/${team}/favorite`, { method: 'PUT' })
    .then(response);
}

export async function addMemberToTeam(teamId, userId, response) {
  jsonFetch(`teams/${teamId}/members/${userId}`, { method: 'PUT' })
    .then(response);
}

export async function deleteMember(teamId, userId, response) {
  Swal.fire({
    icon: 'warning',
    title: 'Voulez vous vraiment supprimer cette personne de l\'équipe ?',
    showDenyButton: true,
    confirmButtonText: 'Oui',
    denyButtonText: 'Non',
  })
    .then((result) => {
      if (result.isConfirmed) {
        jsonFetch(`teams/${teamId}/members/${userId}`, { method: 'DELETE' })
          .then(response);
      } else if (result.isDenied) {
        response(false);
      }
    });
}

export async function getUser(response, q) {
  await jsonFetch(`users?q=${encodeURI(q)}`)
    .then(response);
}

export function getFolder(response) {
  jsonFetch('folders')
    .then(response);
}

export function getChildFolder(parentFolderId, response) {
  jsonFetch(`folders/${parentFolderId}`)
    .then(response);
}

export function initFolder() {
  $(document)
    .ready(() => {
      const foldersDiv = document.getElementById('folders');
      if (foldersDiv) {
        getFolder((folders) => {
          if (folders) {
            let contentDiv = '';
            for (const folder of folders.data) {
              contentDiv += `<folder-menu-row folder-id="${folder.id}" name="${folder.name}" color="${folder.color}" parent-id="${folder.parent_id_folder}"></folder-menu-row>`;
            }
            foldersDiv.innerHTML = contentDiv;
          } else {
            flash('Erreur lors du chargement des dossiers !');
          }
        });
      }
    });
}

export function moveBookmark() {
  const foldersDiv = document.getElementById('foldersMove');
  if (foldersDiv) {
    getFolder((folders) => {
      if (folders) {
        let contentDiv = '';
        for (const folder of folders.data) {
          contentDiv += `<folder-menu-row folder-id="${folder.id}" name="${folder.name}" color="${folder.color}" parent-id="${folder.parent_id_folder}"></folder-menu-row>`;
        }
        foldersDiv.innerHTML = contentDiv;
      } else {
        flash('Erreur lors du chargement des dossiers !');
      }
    });
  }
}

export async function moveItem(bookmarkId, folderId) {
  return jsonFetch(`bookmark/${bookmarkId}/move/${folderId}`, { method: 'GET' });
}

export async function isFavorite(bookmarkId, response) {
  return jsonFetch(`bookmark/${bookmarkId}/favorite/isFavorite`, { method: 'GET' })
    .then(response);
}

export async function addFavorite(bookmarkId, response) {
  return jsonFetch(`bookmark/${bookmarkId}/favorite/add`, { method: 'GET' })
    .then(response);
}

export async function removeFavorite(bookmarkId, response) {
  Swal.fire({
    icon: 'warning',
    title: 'Voulez vous vraiment le supprimer de vos favoris ?',
    showDenyButton: true,
    confirmButtonText: 'Oui',
    denyButtonText: 'Non',
  })
    .then((result) => {
      if (result.isConfirmed) {
        console.log('is confirm');
        jsonFetch(`bookmark/${bookmarkId}/favorite/remove`, { method: 'GET' })
          .then(response);
      } else if (result.isDenied) {
        console.log('is Denied');
        response(false);
      }
    });
}

export async function deleteBookmark(bookmarkId, response) {
  Swal.fire({
    icon: 'warning',
    title: 'Voulez vous vraiment supprimer cette bookmark ?',
    showDenyButton: true,
    confirmButtonText: 'Oui',
    denyButtonText: 'Non',
  })
    .then((result) => {
      if (result.isConfirmed) {
        jsonFetch(`bookmark/${bookmarkId}/delete`, { method: 'GET' })
          .then(response);
      } else if (result.isDenied) {
        response(false);
      }
    });
}

export async function getBookmarkInfo(bookmarkId) {
  return jsonFetch(`bookmark/${bookmarkId}?fields=id,title,link,difficulty,thumbnail`, {
    method: 'get',
  });
}

export async function scrape(query) {
  return jsonFetch(`scrape?q=${query}`, {
    method: 'get',
  });
}

export async function createBookmark(title, link, thumbnail, difficulty, description){
  return jsonFetch(`bookmark`, {
    method: 'post',
    body: {
      titleFinal: title,
      linkFinal: link,
      thumbnailFinal: thumbnail,
      difficultyFinal: difficulty,
      descriptionFinal: description
    }
  });
}

export async function createFolder(name, color, parent_id = null) {
  return jsonFetch(`folder`, {
    method: 'post',
    body: {
      name: name,
      color: color,
      parent: parent_id,
    }
  });
}
