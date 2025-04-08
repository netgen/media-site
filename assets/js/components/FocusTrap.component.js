export default class FocusTrap {
  constructor(element, options) {
    this.options = options;
    this.element = element;

    this.focusableSelectors = `
      button, [href], input, select, textarea, iframe,
      [tabindex]:not([tabindex="-1"])
    `;

    this.focusableElements = [];
    this.firstElement = null;
    this.lastElement = null;
    this.previousFocusElement = null;
    this.isKeyboardClick = false;

    this.init();
  }

  init() {
    if (!this.element) {
      console.warn('FocusTrap: container element does not exist.');
      return;
    }

    this.checkFocusTrapState();

    this.deactivateElements = this.element.querySelectorAll(this.options.deactivateSelectors);
    this.deactivateElements.forEach((el) => {
      el.addEventListener('keydown', (event) => {
        this.isKeyboardClick = event.key === 'Enter';
        this.deactivateFocusTrap(el);
      });
      el.addEventListener('click', () => {
        this.deactivateFocusTrap(el);
      });
    });

    new MutationObserver(this.checkFocusTrapState.bind(this)).observe(this.element, {
      attributes: true,
      attributeFilter: ['open'],
    });
  }

  checkFocusTrapState() {
    if (this.element.hasAttribute('open')) {
      this.activateFocusTrap();
    }
  }

  activateFocusTrap() {
    this.previousFocusElement = document.activeElement;
    this.focusableElements = Array.from(this.element.querySelectorAll(this.focusableSelectors));
    if (!this.focusableElements.length) return;

    [this.firstElement, this.lastElement] = [
      this.focusableElements[0],
      this.focusableElements[this.focusableElements.length - 1],
    ];

    this.firstElement.focus();

    document.addEventListener('keydown', this.handleTabKey.bind(this));
  }

  deactivateFocusTrap(el) {
    document.removeEventListener('keydown', this.handleTabKey.bind(this));

    if (this.isKeyboardClick) {
      el.click();
      FocusTrap.resetFocusToTop();
      this.isKeyboardClick = false;
    }
  }

  static resetFocusToTop() {
    window.scrollTo(0, 0);

    const topElement = document.querySelector('#skip-to-main-content');

    if (topElement) {
      topElement.focus();
    } else {
      document.body.focus();
    }
  }

  handleTabKey(event) {
    if (event.key !== 'Tab') return;

    if (event.shiftKey) {
      if (document.activeElement === this.firstElement) {
        event.preventDefault();
        this.lastElement.focus();
      }
    } else if (document.activeElement === this.lastElement) {
      event.preventDefault();
      this.firstElement.focus();
    }
  }
}
