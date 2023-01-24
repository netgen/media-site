import { Modal } from 'bootstrap';
import GTM from '../utilities/gtm';
import submit from '../utilities/form/submit';

export default class FormModal {
  constructor(trigger) {
    this.trigger = trigger;
    this.submitted = false;

    this.form = null;
    this.modal = null;
    this.modalElement = null;

    this.init();
  }

  init() {
    this.trigger.addEventListener('click', (event) => {
      event.preventDefault();

      const { url } = this.trigger.dataset;

      fetch(url)
        .then((response) => response.text())
        .then(this.openModal.bind(this))
        .catch((error) => {
          console.error(`Failed to open modal form: ${error}`);
        });
    });
  }

  openModal(text) {
    const template = document.createElement('template');
    template.innerHTML = text.trim();

    this.modalElement = template.content.firstElementChild;
    this.modal = new Modal(this.modalElement);
    this.form = this.modalElement.querySelector('form');

    this.modalElement.addEventListener('hidden.bs.modal', this.closeModal.bind(this));
    this.form.addEventListener('submit', submit.bind(this, 'modal'));

    document.body.appendChild(template.content);

    this.submitted = false;
    this.modal.show();

    const { gtmEventPrefix } = this.form.dataset;
    GTM.push(gtmEventPrefix, GTM.EVENTS.OPENED);
  }

  closeModal() {
    this.modal.dispose();
    this.modalElement.remove();

    if (this.submitted === false) {
      const { gtmEventPrefix } = this.form.dataset;
      GTM.push(gtmEventPrefix, GTM.EVENTS.CANCELLED);
    }
  }

  onSuccess() {
    this.submitted = true;
  }
}
