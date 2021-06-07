export default class FilesUploader extends HTMLElement {
  constructor({ multiple, accept } = {}) {
    super();
    if (multiple !== undefined) {
      this.multiple = multiple;
    }
    this.accept = accept ?? 'image/*';
  }

  connectedCallback() {
    this.multiple = this.multiple || this.hasAttribute('multiple');
    this.classList.add('file-upload');

    const input = document.createElement('input');
    input.classList.add('file-upload-input');
    input.setAttribute('type', 'file');
    if (this.multiple) {
      input.setAttribute('multiple', 'true');
    }
    if (this.accept) {
      input.setAttribute('accept', this.accept);
    }
    input.addEventListener('change', this.onChangeFile(this));

    this.input = input;

    const uploadBtn = document.createElement('button');
    uploadBtn.classList.add('file-upload-btn');
    uploadBtn.setAttribute('type', 'button');
    uploadBtn.addEventListener('click', () => this.input.click());
    uploadBtn.innerText = `Insérer ${this.multiple ? 'des' : 'un'} fichier${this.multiple ? 's' : ''}`;
    this.uploadBtn = uploadBtn;

    const wrapDiv = document.createElement('div');
    wrapDiv.classList.add('image-upload-wrap');
    wrapDiv.addEventListener('dragover', () => wrapDiv.classList.add('image-dropping'));
    wrapDiv.addEventListener('dragleave', () => wrapDiv.classList.remove('image-dropping'));
    wrapDiv.appendChild(this.input);
    this.wrapDiv = wrapDiv;

    const dragDiv = document.createElement('div');
    dragDiv.classList.add('drag-text');
    dragDiv.innerHTML = `<h3>Déplacer ${this.multiple ? 'des' : 'un'} fichier${this.multiple ? 's' : ''} ici !</h3>`;
    wrapDiv.appendChild(dragDiv);

    const contentDiv = document.createElement('div');
    contentDiv.classList.add('file-upload-content');
    this.contentDiv = contentDiv;

    const renderImg = document.createElement('img');
    renderImg.setAttribute('src', '#');
    renderImg.classList.add('file-upload-image');
    renderImg.setAttribute('alt', `${this.multiple ? 'Vos' : 'Votre'} fichier${this.multiple ? 's' : ''}`);
    this.renderImg = renderImg;

    const imageWrap = document.createElement('div');
    imageWrap.classList.add('image-title-wrap');
    this.imageWrap = imageWrap;

    const deleteBtn = document.createElement('button');
    deleteBtn.setAttribute('type', 'button');
    deleteBtn.classList.add('remove-image');
    deleteBtn.innerHTML = 'Supprimer <span class="image-title"></span>';

    imageWrap.appendChild(deleteBtn);
    this.contentDiv.appendChild(this.renderImg);
    this.contentDiv.appendChild(this.imageWrap);

    this.appendChild(this.uploadBtn);
    this.appendChild(wrapDiv);
    this.appendChild(this.contentDiv);
  }

  onChangeFile(ev) {
    if (ev.files && ev.files[0]) {
      const reader = new FileReader();
      reader.onload = (e) => {
        this.wrapDiv.style.display = 'none';
        this.renderImg.setAttribute('src', e.target.result);
        this.contentDiv.style.display = 'block';
        document.getElementById('image-title').innerText = ev.files[0].name;
      };
      reader.readAsDataURL(ev.files[0]);
    }
  }
}
