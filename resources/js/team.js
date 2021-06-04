import $ from 'jquery';
import { changeTeamFavorite } from './api';

export default function registerTeam() {
  const btns = document.querySelectorAll('#change-favorite');

  $(document).ready(() => {
    btns.forEach((value) => {
      value.addEventListener('click', () => {
        const teamId = value.parentNode.parentNode.getAttribute('data-id');
        changeTeamFavorite(teamId, () => {
          console.log('ok');
        });
      });
    });
  });
}
