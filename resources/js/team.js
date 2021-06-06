import $ from 'jquery';
import { changeTeamFavorite } from './api';
import { flash } from './elements/Alert';

export default function registerTeam() {
  const btns = document.querySelectorAll('#change-favorite');

  $(document).ready(() => {
    btns.forEach((value) => {
      value.addEventListener('click', () => {
        const teamId = value.parentNode.parentNode.getAttribute('data-id');
        changeTeamFavorite(teamId, (e) => {
          if (e) {
            if (e.new_state) {
              flash('Equipe maintenant en favoris !', 'info', 3);
            } else {
              flash('L\'équipe ne fait plus partie de vos favoris !', 'info', 3);
            }
          }
        });
      });
    });
  });
}
