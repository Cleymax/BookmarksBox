import { checkWithinContainer, getDocumentScrollLeft, getDocumentScrollTop } from '../dom';

export default class Tooltip {
  /**
   * @param element Element
   */
  constructor(element) {
    this.element = element;
    this.getAttributes();
    this.contentHtml = this.data;
    this.create();
    this.registerListeners();
  }

  create() {
    const el = document.createElement('div');
    el.classList.add('tooltip');
    this.el = el;

    const content = document.createElement('div');
    content.classList.add('tooltip__content');
    content.innerHTML = this.contentHtml;
    el.appendChild(content);
    document.body.appendChild(el);
  }

  registerListeners() {
    this.onMouseEnterB = this.onMouseEnter.bind(this);
    this.onMouseLeaveB = this.onMouseLeave.bind(this);
    this.element.addEventListener('mouseenter', this.onMouseEnterB);
    this.element.addEventListener('mouseleave', this.onMouseLeaveB);
  }

  unregisterListeners() {
    this.element.removeEventListener('mouseenter', this.onMouseEnterB);
    this.element.removeEventListener('mouseleave', this.onMouseLeaveB);
  }

  onMouseLeave() {
    this.isHover = false;
    this.close();
  }

  openTooltip() {
    this.isOpen = true;
    this.updateContent();
    this.getAttributes();

    setTimeout(() => {
      if (!this.isHover) {
        return;
      }
      this.animateIn();
    }, 200);
  }

  updateContent() {
    this.el.querySelector('.tooltip__content').innerHTML = this.contentHtml;
  }

  onMouseEnter() {
    this.isHover = true;
    this.openTooltip();
  }

  getAttributes() {
    this.position = this.element.getAttribute('data-tooltip');
    this.data = this.element.getAttribute('data-text');
  }

  animateIn() {
    this.el.classList.remove('out');
    this.updatePosition();
    this.el.style.visibility = 'visible';
    this.el.style.opacity = '1';
  }

  // eslint-disable-next-line class-methods-use-this
  reposition(x, y, w, h) {
    const scrollLeft = getDocumentScrollLeft();
    const scrollTop = getDocumentScrollTop();

    let newX = x - scrollLeft;
    let newY = y - scrollTop;

    const bounding = {
      left: newX,
      top: newY,
      width: w,
      height: h,
    };

    const offset = 15;
    const edges = checkWithinContainer(document.body, bounding, offset);

    if (edges.left) {
      newX = offset;
    } else if (edges.right) {
      newX -= newX + w - window.innerWidth;
    }

    if (edges.top) {
      newY = offset;
    } else if (edges.bottom) {
      newY -= newY + h - window.innerHeight;
    }

    return {
      x: newX + scrollLeft,
      y: newY + scrollTop,
    };
  }

  updatePosition() {
    const origin = this.element;
    const tooltip = this.el;
    const originHeight = origin.offsetHeight;
    const originWidth = origin.offsetWidth;
    const tooltipHeight = tooltip.offsetHeight;
    const tooltipWidth = tooltip.offsetWidth;
    const margin = 5;
    let targetTop;
    let targetLeft;

    targetTop = origin.getBoundingClientRect().top + getDocumentScrollTop();
    targetLeft = origin.getBoundingClientRect().left + getDocumentScrollLeft();

    if (this.position === 'top') {
      targetTop += -tooltipHeight - margin;
      targetLeft += originWidth / 2 - tooltipWidth / 2;
    } else if (this.position === 'right') {
      targetTop += originHeight / 2 - tooltipHeight / 2;
      targetLeft += originWidth + margin;
    } else if (this.position === 'left') {
      targetTop += originHeight / 2 - tooltipHeight / 2;
      targetLeft += -tooltipWidth - margin;
    } else {
      targetTop += originHeight + margin;
      targetLeft += originWidth / 2 - tooltipWidth / 2;
    }

    const newCoordinates = this.reposition(
      targetLeft,
      targetTop,
      tooltipWidth,
      tooltipHeight,
    );

    tooltip.style.top = `${newCoordinates.y}px`;
    tooltip.style.left = `${newCoordinates.x}px`;
  }

  close() {
    if (!this.isOpen) {
      return;
    }
    this.isHover = false;
    this.isOpen = false;
    this.animateOut();
  }

  animateOut() {
    setTimeout(() => {
      if (this.isHover) {
        return;
      }
      this.el.classList.add('out');
    }, 200);
  }
}
