export default class AccessibilitySkips {
  constructor(trigger, options) {
    this.trigger = trigger;
    this.targetElement = document.querySelector(`.${this.trigger.dataset.targetClass}`);
    this.options = options;
    this.cookieBanner = options?.cookieBanner ? document.querySelector(options.cookieBanner) : null;
    this.init();
  }

  init() {
    //general funcionality
    this.trigger.addEventListener('click', this.handleTriggerClick.bind(this));

    //specific funcionality for skipping to cookie banner
    if (this.cookieBanner && this.trigger) {
      const updateSkipCookieBanner = () => {
        if (this.cookieBanner.hasAttribute('open')) {
          this.trigger.tabIndex = 0;
        } else {
          this.trigger.tabIndex = -1;
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
