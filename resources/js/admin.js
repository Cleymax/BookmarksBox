import $ from 'jquery';
import {
  addMemberToTeam, changeRole, deleteMember, getUser,
} from './api';
import { flash } from './elements/Alert';

export default function registerAdminChangeRole() {
  $(document).ready(() => {
    const recherchemembers = document.getElementById('q-members');
    const resultMembers = document.getElementById('result-members');
    if (recherchemembers) {
      $(recherchemembers).on('keyup', function () {
        const q = this.value;
        getUser((e) => {
          const members = e.data;
          let text = '';
          members.forEach((member) => {
            text += `<tr data-user-id="${member.id}"><td>${member.last_name}</td><td>${member.first_name}</td><td>${member.username}</td><td><a id="add-member" class="add"><span style="color: var(--green)" class="material-icons">add</span></a></td></tr>`;
          });
          resultMembers.innerHTML = text;

          const addMember = document.querySelectorAll('#add-member');
          addMember.forEach((add) => {
            $(add).on('click', function () {
              const th = this.parentNode.parentNode;
              const userId = th.getAttribute('data-user-id');
              const teamId = document.querySelector('main').getAttribute('data-id');
              addMemberToTeam(teamId, userId, (data) => {
                flash('Un membre vient d\'être ajouter à l\'équipe !', 'success', 10);
                const membersList = document.getElementById('members-list');
                let content = membersList.innerHTML;
                content += `<tr data-user-id="${data.member.id}">
                    <td>
                        <img src="${data.member.avatar}" height="20px" width="20px" alt="Username Avatar">
                        <span style="margin-left: 10px">${data.member.username}</span>
                    </td>
                    <td>
                        <span>${data.member.first_name}</span>
                    </td>
                    <td>
                        <span>${data.member.last_name}</span>
                    </td>
                    <td>
                        <select name="role" id="change-role">
                          <option selected="" value="MEMBER">Membre</option>
                          <option value="EDITOR">Editeur</option>
                          <option value="MANAGER">Manager</option>
                          <option value="OWNER">Propriétaire</option>
                        </select>
                    </td>
                    <td>
                       <a id="delete-member"><span class="material-icons">delete</span> </a>
                    </td>
                </tr>`;
                membersList.innerHTML = content;
              });
            });
          });
        }, q);
      });
    }
    const deleteMembers = document.querySelectorAll('#delete-member');
    deleteMembers.forEach((del) => {
      del.addEventListener('click', () => {
        const th = del.parentNode.parentNode;
        const userId = th.getAttribute('data-user-id');
        const teamId = th.parentNode.parentNode.getAttribute('data-team-id');
        deleteMember(teamId, userId, (e) => {
          if (e) {
            th.classList.add('out-opacity');
            setTimeout(() => {
              th.parentNode.removeChild(th);
            }, 600);
          }
        });
      });
    });
    const changeRoles = document.querySelectorAll('#change-role');
    changeRoles.forEach((selector) => {
      $(selector).on('change', function () {
        const th = selector.parentNode.parentNode;
        const userId = th.getAttribute('data-user-id');
        const teamId = th.parentNode.parentNode.getAttribute('data-team-id');
        const valueSelected = this.value;
        changeRole(teamId, userId, valueSelected, (retur) => {
          if (retur) {
            flash('Modification du rôle avec succès !', 'success', 5);
          }
        });
      });
    });
  });
}
