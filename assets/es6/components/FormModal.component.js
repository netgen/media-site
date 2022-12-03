import { Modal } from 'bootstrap';
import GTM from '../utils/gtm';

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

      const url = this.trigger.getAttribute('data-url');

      fetch(url)
        .then((response) => response.text())
        .then((text) => this.openModal(text))
        .catch((error) => {
          // eslint-disable-next-line no-console
          console.error('Modal form open failed: ', error);
        });
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
    GTM.push(gtmEventPrefix, 'opened');
  }

  closeModal(modal, modalElement, gtmEventPrefix) {
    modal.dispose();
    modalElement.remove();
    !this.submitted && GTM.push(gtmEventPrefix, 'cancelled');
  }

  submit(e) {
    e.preventDefault();
    const form = e.target;

    const action = form.getAttribute('action');
    const options = { method: 'POST', body: new FormData(form) };
    const gtmEventPrefix = form.getAttribute('data-gtm-event-prefix');
    const formContainer = form.parentElement;

    formContainer.innerHTML = '<div class="loading-animation"><span></span></div>';

    fetch(action, options)
      .then((response) => response.text())
      .then((text) => {
        formContainer.innerHTML = text.trim();
        this.submitted = true;
        GTM.push(gtmEventPrefix, 'submitted');
      }).catch((error) => {
        GTM.push(gtmEventPrefix, 'failed');
        console.error('Modal form submit failed: ', error);
      });
  }
}
