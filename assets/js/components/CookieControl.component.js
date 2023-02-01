import NetgenCookieControl from '@netgen/javascript-cookie-control';
import GTM from '../utilities/gtm';

export default class CookieControl {
  constructor(element, options) {
    this.options = options;

    this.optionalSaveBtn = element.querySelector(options.optionalSaveBtn);
    this.optionalList = element.querySelector(options.optionalList);
    this.optionalListToggle = element.querySelector(options.optionalListToggle);

    this.init();
  }

  init() {
    this.initNetgenCookieControl();
    this.optionalListToggle.addEventListener('click', this.handleOptionalListToggle.bind(this));
    this.optionalSaveBtn.addEventListener('click', this.handleConsentChange);
  }

  initNetgenCookieControl() {
    // eslint-disable-next-line no-underscore-dangle
    const cookieControl = new NetgenCookieControl(window.__ngCcConfig);
    cookieControl.init();
  }

  handleOptionalListToggle(event) {
    event.preventDefault();

    this.optionalList.classList.toggle(this.options.shownClass);
    const isVisible = this.optionalList.classList.contains(this.options.shownClass);
    this.optionalList.style.maxHeight = isVisible ? `100vh` : '0px';

    this.optionalListToggle.classList.toggle(this.options.rotateArrowClass);
    this.optionalListToggle.classList.toggle(this.options.shownClass);
  }

  handleConsentChange(event) {
    event.preventDefault();

    GTM.push('ngcc', GTM.EVENTS.CHANGED);
  }
}
