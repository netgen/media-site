export default class SkipToMainContent {
  constructor(trigger) {
    this.trigger = trigger;
    this.targetElement = document.querySelector(`.${this.trigger.dataset.targetClass}`);

    this.init();
  }

  init() {
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
