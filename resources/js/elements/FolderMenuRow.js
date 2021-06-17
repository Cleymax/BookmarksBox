import { getChildFolder } from '../api';
import Skeleton from './Skeleton';

export default class FolderMenuRow extends HTMLElement {
  constructor({
    id, name, color, parentFolderId,
  } = {}) {
    super();
    this.id = id;
    this.name = name;
    this.color = color || '#FFFFFF';
    this.parentFolderId = parentFolderId;
    this.close = this.close.bind(this);
  }

  connectedCallback() {
    this.color = this.color === '#FFFFFF' ? this.getAttribute('color') : this.color;
    this.id = this.getAttribute('folder-id') || this.id;
    this.name = this.getAttribute('name') || this.name;
    this.parentFolderId = this.getAttribute('parent-id') || this.parentFolderId;

    this.setAttribute('name', this.name);
    this.setAttribute('parent-id', this.parentFolderId);
    this.setAttribute('color', this.color);
    this.setAttribute('folder-id', this.id);

    const menuRow = document.createElement('div');
    menuRow.classList.add('menu__row');

    const a = document.createElement('a');
    a.setAttribute('href', `https://public.test/folder/${this.id}`);

    const icon = document.createElement('span');
    icon.classList.add('material-icons');
    icon.innerText = 'folder';
    icon.style.color = `${this.color}`;

    const text = document.createElement('span');
    if (this.name.length > 20) {
      text.classList.add('tooltipped');
      text.setAttribute('data-text', this.name);
    }
    text.innerText = this.name;

    a.appendChild(icon);
    a.appendChild(text);
    menuRow.appendChild(a);
    this.appendChild(menuRow);

    icon.addEventListener('click', (event) => {
      event.preventDefault();
      event.stopPropagation();
      if (this.getAttribute('open') === null || this.getAttribute('open') === 'false') {
        this.setAttribute('open', 'true');
        const div = document.createElement('div');
        const a = document.createElement('a');
        div.classList.add('menu__row');
        const round = new Skeleton();
        round.setAttribute('rounded', 'true');
        round.setAttribute('height', '20px');
        round.setAttribute('width', '20px');
        const skeleton = new Skeleton();
        skeleton.setAttribute('width', '140px');
        skeleton.style.marginLeft = '5px';
        a.appendChild(round);
        a.appendChild(skeleton);
        div.appendChild(a);
        this.insertAdjacentElement('afterend', div);
        getChildFolder(this.id, (folders) => {
          this.parentNode.removeChild(div);
          if (folders) {
            // eslint-disable-next-line no-restricted-syntax
            for (const folder of folders.data) {
              const row = new FolderMenuRow({
                id: folder.id, color: folder.color, name: folder.name, parentFolderId: folder.parent_id_folder,
              });
              this.insertAdjacentElement('afterend', row);
            }
          }
        });
      } else {
        this.setAttribute('open', 'false');
        const childFolder = document.querySelectorAll(`[parent-id="${this.id}"]`);
        childFolder.forEach((value) => value.parentNode.removeChild(value));
      }
      const select = document.querySelector("folder-menu-row[moveSelected]");
      if(select){
        select.removeAttribute('moveSelected');
      }
      event.target.parentNode.parentNode.parentNode.setAttribute('moveSelected', '');
    });
  }

  close() {
    this.classList.add('out');
    window.setTimeout(async () => {
      if (this.parentElement !== null) {
        this.parentElement.removeChild(this);
      }
    }, 500);
  }
}
