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
  });
}
