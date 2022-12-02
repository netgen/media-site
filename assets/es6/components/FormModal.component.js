import { Modal } from 'bootstrap';

export default class FormModalComponent {
  constructor(trigger, options) {
    this.options = options;
    this.trigger = trigger;
    this.submitted = false;
    this.init();
  }

  init() {
    this.trigger.addEventListener('click', (e) => {
      e.preventDefault();

      fetch(this.trigger.getAttribute('data-url'))
        .then((response) => response.text())
        .then((text) => this.openModal(text));
    });
  }

  openModal(text) {
    const template = document.createElement('template');
    template.innerHTML = text.trim();
    const modalElement = template.content.firstElementChild;
    const modal = new Modal(modalElement);
    const form = modalElement.querySelector('form');
    const gtmEventPrefix = form.getAttribute('data-gtm-event-prefix');

    form.addEventListener('submit', (event) => this.submit(event));
    modalElement.addEventListener('hidden.bs.modal', () => this.closeModal(modal, modalElement, gtmEventPrefix));
    document.body.appendChild(template.content);

    modal.show();
    this.submitted = false;
    this.gtmPush(gtmEventPrefix, 'opened');
  }

  closeModal(modal, modalElement, gtmEventPrefix) {
    modal.dispose();
    modalElement.remove();
    this.gtmPush(gtmEventPrefix, 'cancelled', !this.submitted);
  }

  submit(e) {
    e.preventDefault();
    const form = e.target;

    const gtmEventPrefix = form.getAttribute('data-gtm-event-prefix');
    const formContainer = form.parentElement;

    formContainer.innerHTML = '<div class="loading-animation"><span></span></div>';

    fetch(form.getAttribute('action'), { method: 'POST', body: new FormData(form) })
      .then((response) => response.text())
      .then((text) => {
        formContainer.innerHTML = text.trim();
        this.submitted = true;
        this.gtmPush(gtmEventPrefix, 'submitted');
      }).catch((error) => {
        this.gtmPush(gtmEventPrefix, 'failed');
        // eslint-disable-next-line no-console
        console.error('Error: ', error)
      });
  }

  // eslint-disable-next-line class-methods-use-this
  gtmPush(prefix, suffix, condition = true) {
    if (!condition) {
      return;
    }

    if (typeof prefix === 'undefined') {
      // eslint-disable-next-line no-console
      console.warn(`GTM prefix is not defined`);

      return;
    }

    if (!('dataLayer' in window)) {
      // eslint-disable-next-line no-console
      console.warn(`GTM data layer is not available`);

      return;
    }

    window.dataLayer.push({ event: `${prefix}-${suffix}` });
    // eslint-disable-next-line no-console
    console.info(`GTM event pushed: ${prefix}-${suffix}`);
  }
}
