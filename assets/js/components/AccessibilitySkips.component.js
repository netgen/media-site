export default class AccessibilitySkips {
  constructor(trigger, options) {
    this.trigger = trigger;
    this.targetElement = document.querySelector(`.${this.trigger.dataset.targetClass}`);
    this.options = options;
    this.init();
  }

  init() {
    // general funcionality
    this.trigger.addEventListener('click', this.handleTriggerClick.bind(this));
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
