import { flash } from './elements/Alert';

export default function joinTeamsWithCode() {
  const code = document.getElementById('code').value;
  if (!code || code.length !== 6 || !code.match(/[A-Za-z0-9]{6}/)) {
    flash('Code invalide !', 'error');
  } else {
    window.location += `/invite/${code}`;
  }
}
