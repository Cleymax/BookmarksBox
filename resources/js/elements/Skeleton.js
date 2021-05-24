export default class Skeleton extends HTMLElement {
  constructor() {
    super();
    this.root = this.attachShadow({ mode: 'open' });
  }

  // eslint-disable-next-line class-methods-use-this
  size(size, fallbackTo100) {
    if (size) {
      if (size.match(/^[0-9]+$/)) {
        size += 'px';
      }
      return size;
    } if (fallbackTo100) {
      return '100%';
    }
    return 'auto';
  }

  connectedCallback() {
    const crypto = window.crypto || window.msCrypto;
    const text = this.getAttribute('text');
    const lines = this.getAttribute('lines');
    const rounded = this.getAttribute('rounded');
    const width = this.size(this.getAttribute('width'), text === null || lines !== null);
    const height = this.size(this.getAttribute('height'), text === null);
    let spans = '<span></span>';
    if (lines) {
      for (let i = 1; i < parseInt(lines, 10); i += 1) {
        spans += '<span></span>';
      }
    }
    this.root.innerHTML = `<style>
      :host {
        display: block;
      }
      div {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        width: ${width};
        height: ${height};
      }
      span {
        display: ${width || height ? 'block' : 'inline-block'};
        position: relative;
        width: ${width};
        height: ${height};
        border-radius: ${rounded !== null ? '50%' : '4px'};
        transform-origin: 0 60%;
        background-color: var(--white, #0000001c);
        overflow: hidden;
        transform: ${lines || text ? 'scale(1, 0.60)' : 'none'};
        animation: pulse 1.5s ease-in-out 0.5s infinite;
      }

      span:last-child {
        width: ${lines ? (`${20 + (crypto.getRandomValues(new Uint32Array(1)) * 60)}%`) : 'inherit'};
      }
      span::before {
        content: "${this.getAttribute('text')}";
        opacity: 0;
      }
      span::after {
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        content: "";
        position: absolute;
        animation: waves 1.6s linear 0.5s infinite;
        transform: translateX(-100%);
        background: linear-gradient(90deg, transparent, var(--white-skeleton), transparent);
      }
      @keyframes waves {
        0% {
          transform: translateX(-100%);
        }
        60% {
          transform: translateX(100%);
        }
        100% {
          transform: translateX(100%);
        }
      }
    </style><div>${spans}</div>`;
  }
}
