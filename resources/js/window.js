import { getWindowWidth } from './dom';

export default function registerWindowHeightCSS() {
  let windowHeight = window.innerHeight;
  document.documentElement.style.setProperty('--vh', `${window.innerHeight * 0.01}px`);
  document.documentElement.style.setProperty('--windowHeight', `${window.innerHeight}px`);

  window.addEventListener('resize', () => {
    if (windowHeight === window.innerHeight) {
      return;
    }
    windowHeight = window.innerHeight;
    document.documentElement.style.setProperty('--vh', `${window.innerHeight * 0.01}px`);
    document.documentElement.style.setProperty('--windowHeight', `${window.innerHeight}px`);

    const width = parseInt(`${getWindowWidth()}`, 10);
    if (width < 700) {
      const menu = document.getElementById('menu');
      const content = document.getElementById('content');
      menu.style.transform = 'translateX(-100%)';
      content.style.marginLeft = '0px';
      content.style.display = 'block';
    }
  });
}
