export default class AccessibilitySkips {
  constructor(trigger, options) {
    this.trigger = trigger;
    this.targetElement = document.querySelector(`.${this.trigger.dataset.targetClass}`);
    this.options = options;
    this.cookieBanner = document.querySelector(options.cookieBanner);
    this.skipCookieBanner = document.querySelector(options.skipCookieBanner);

    this.init();
  }

  init() {
    this.trigger.addEventListener('click', this.handleTriggerClick.bind(this));

    if (this.cookieBanner && this.skipCookieBanner) {
      const updateSkipCookieBanner = () => {
        if (this.cookieBanner.hasAttribute('open')) {
          this.skipCookieBanner.tabIndex = 0;
        } else {
          this.skipCookieBanner.tabIndex = -1;
        }
      };
      updateSkipCookieBanner();
      const observer = new MutationObserver(() => {
        updateSkipCookieBanner();
      });
      observer.observe(this.cookieBanner, { attributes: true, attributeFilter: ['open'] });
    }
  }

  handleTriggerClick() {
    this.targetElement.setAttribute('tabindex', '0');
    this.targetElement.scrollIntoView({
      behavior: 'smooth',
      block: 'start',
      inline: 'nearest',
    });
    this.trigger.blur();
    this.targetElement.focus();
  }
}
