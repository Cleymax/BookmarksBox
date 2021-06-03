export function getDocumentScrollTop() {
  return window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
}

export function getDocumentScrollLeft() {
  return window.pageXOffset || document.documentElement.scrollLeft || document.body.scrollLeft || 0;
}

export function getWindowHeight() {
  return window.innerHeight;
}

export function getWindowWidth() {
  return window.innerWidth;
}

/**
 * @typedef {Object} Bounding
 * @property {Number} left  left offset coordinate
 * @property {Number} top  top offset coordinate
 * @property {Number} width
 * @property {Number} height
 */

/**
 * @typedef {Object} Edges
 * @property {Boolean} top  If the top edge was exceeded
 * @property {Boolean} right  If the right edge was exceeded
 * @property {Boolean} bottom  If the bottom edge was exceeded
 * @property {Boolean} left  If the left edge was exceeded
 */

/**
 * @param {Element} container  Container element that acts as the boundary
 * @param {Bounding} bounding  element bounding that is being checked
 * @param {Number} offset  offset from edge that counts as exceeding
 * @returns {Edges}
 */
export function checkWithinContainer(container, bounding, offset) {
  const edges = {
    top: false,
    right: false,
    bottom: false,
    left: false,
  };

  const containerRect = container.getBoundingClientRect();

  const containerBottom = container === document.body
    ? Math.max(containerRect.bottom, window.innerHeight)
    : containerRect.bottom;

  const { scrollLeft } = container;
  const { scrollTop } = container;

  const scrolledX = bounding.left - scrollLeft;
  const scrolledY = bounding.top - scrollTop;

  // Check for container and viewport for each edge
  if (scrolledX < containerRect.left + offset || scrolledX < offset) {
    edges.left = true;
  }

  if (
    scrolledX + bounding.width > containerRect.right - offset
    || scrolledX + bounding.width > window.innerWidth - offset
  ) {
    edges.right = true;
  }

  if (scrolledY < containerRect.top + offset || scrolledY < offset) {
    edges.top = true;
  }

  if (
    scrolledY + bounding.height > containerBottom - offset
    || scrolledY + bounding.height > window.innerHeight - offset
  ) {
    edges.bottom = true;
  }

  return edges;
}
