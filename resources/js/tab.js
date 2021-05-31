import $ from 'jquery';

function selectTab(tabs, tabContents, tab) {
  const target = document.querySelector(tab.dataset.tabTarget);
  tabContents.forEach((tabContent) => tabContent.classList.remove('active'));
  tabs.forEach((tabb) => tabb.classList.remove('active'));
  target.classList.add('active');
  tab.classList.add('active');
}

export default function registerTab() {
  $(document).ready(() => {
    const queryString = window.location.hash;

    const tabs = document.querySelectorAll('[data-tab-target]');
    const tabContents = document.querySelectorAll('[data-tab-content]');

    tabs.forEach((tab) => {
      if (queryString.startsWith(tab.dataset.tabTarget)) {
        selectTab(tabs, tabContents, tab);
      }
      tab.addEventListener('click', () => {
        selectTab(tabs, tabContents, tab);
      });
    });
  });
}
