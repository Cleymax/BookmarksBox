import $ from 'jquery';
import { flash } from './elements/Alert';

export default function registerCopyClipboard() {
  $(document).ready(() => {
    const copy = document.querySelectorAll('[data-copy]');
    copy.forEach((value) => {
      // eslint-disable-next-line func-names
      $(value).on('click', function () {
        const text = this.getAttribute('data-copy');
        navigator.clipboard.writeText(text).then(() => {
          flash('Lien copié !', 'success', 3);
        }, () => {
          flash('Erreur lors du copi du lien !', 'error', 3);
        });
      });
    });
  });
}
