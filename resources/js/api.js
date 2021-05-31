import Swal from 'sweetalert2';
import { flash } from './elements/Alert';

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

  const response = await fetch(url, params);
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
export function changeRole(teamId, userId, roleId) {
  jsonFetch(`https://public.test/api/teams/${teamId}/members/${userId}/role`, {
    body: {
      role: roleId,
    },
    method: 'post',
  });
}

export async function addMemberToTeam(teamId, userId, response) {
  jsonFetch(`https://public.test/api/teams/${teamId}/members/${userId}`, { method: 'PUT' }).then(response);
}

export async function deleteMember(teamId, userId, response) {
  Swal.fire({
    icon: 'warning',
    iconColor: '#B01917',
    title: "Voulez vous vraiment supprimer cette personne de l'équipe ?",
    showDenyButton: true,
    confirmButtonText: 'Oui',
    denyButtonText: 'Non',
  }).then((result) => {
    if (result.isConfirmed) {
      jsonFetch(`https://public.test/api/teams/${teamId}/members/${userId}`, { method: 'DELETE' }).then(response);
    } else if (result.isDenied) {
      response(false);
    }
  });
}

export async function getUser(response, q) {
  await jsonFetch(`https://public.test/api/users?q=${encodeURI(q)}`).then(response);
}
