import { Modal } from 'bootstrap';

export default class FormModalComponent {
  constructor(trigger, options) {
    this.options = options;
    this.trigger = trigger;
    this.submitted = null;
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
    this.gtmOpened(gtmEventPrefix);
  }

  closeModal(modal, modalElement, gtmEventPrefix) {
    modal.dispose();
    modalElement.remove();
    this.gtmClosed(gtmEventPrefix);
  }

  submit(e) {
    const form = e.target;
    e.preventDefault();

    const gtmEventPrefix = form.getAttribute('data-gtm-event-prefix');
    const formContainer = form.parentElement;

    formContainer.innerHTML = '<div class="loading-animation"><span></span></div>';

    fetch(form.getAttribute('action'), { method: 'POST', body: new FormData(form) })
      .then((response) => response.text())
      .then((text) => {
        formContainer.innerHTML = text.trim();
        this.gtmSubmitted(gtmEventPrefix);
      }).catch((error) => {
        this.gtmFailed(gtmEventPrefix);
        // eslint-disable-next-line no-console
        console.log('Error: ', error)
      });
  }

  gtmOpened(prefix) {
    if (!this.gtmCheck(prefix)) {
      return;
    }

    window.dataLayer.push({ event: `${prefix}-opened` });
    this.submitted = false;
    // eslint-disable-next-line no-console
    console.log(`GTM event pushed: ${prefix}-opened`);
  }

  gtmClosed(prefix) {
    if (!this.gtmCheck(prefix)) {
      return;
    }

    if (this.submitted === false) {
      window.dataLayer.push({ event: `${prefix}-canceled` });
      // eslint-disable-next-line no-console
      console.log(`GTM event pushed: ${prefix}-canceled`);
    }
  }

  gtmSubmitted(prefix) {
    if (!this.gtmCheck(prefix)) {
      return;
    }

    window.dataLayer.push({ event: `${prefix}-submitted` });
    this.submitted = true;
    // eslint-disable-next-line no-console
    console.log(`GTM event pushed: ${prefix}-submitted`);
  }

  gtmFailed(prefix) {
    if (!this.gtmCheck(prefix)) {
      return;
    }

    window.dataLayer.push({ event: `${prefix}-failed` });
    // eslint-disable-next-line no-console
    console.log(`GTM event pushed: ${prefix}-failed`);
  }

  // eslint-disable-next-line class-methods-use-this
  gtmCheck(prefix) {
    if (typeof prefix === 'undefined') {
      // eslint-disable-next-line no-console
      console.warn(`GTM prefix is not defined`);

      return false;
    }

    if (!('dataLayer' in window)) {
      // eslint-disable-next-line no-console
      console.warn(`GTM data layer is not available`);

      return false;
    }

    return true;
  }
}
