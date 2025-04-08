import { Modal } from 'bootstrap';
import GTM from '../utilities/gtm';
import submit from '../utilities/form/submit';
import validateForm from '../utilities/form/validate';
import setupUpdateSelectedFilesList from '../utilities/form/setup-update-selected-files-list';

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
    this.trigger.addEventListener('click', this.handleOpenModal.bind(this));
  }

  handleOpenModal(event) {
    event.preventDefault();

    const { url } = this.trigger.dataset;

    fetch(url)
      .then((response) => {
        if (!response.ok) {
          throw new Error();
        }

        return response.text();
      })
      .then(this.openModal.bind(this))
      .catch((error) => {
        console.error(`Failed to open modal form: ${error}`);
      });
  }

  openModal(text) {
    const template = document.createElement('template');
    template.innerHTML = text.trim();

    this.modalElement = template.content.firstElementChild;
    this.modal = new Modal(this.modalElement);
    this.form = this.modalElement.querySelector('form');

    this.modalElement.addEventListener('hidden.bs.modal', this.closeModal.bind(this));

    setupUpdateSelectedFilesList(this.form);
    this.form.addEventListener('submit', this.handleSubmit.bind(this));

    document.body.appendChild(template.content);

    this.submitted = false;
    this.modal.show();

    const { gtmEventPrefix } = this.form.dataset;
    GTM.push(gtmEventPrefix, GTM.EVENTS.OPENED);
  }

  handleSubmit(e) {
    e.preventDefault();

    if (validateForm(this.form)) {
      submit.bind(this, 'modal', e)();
    }
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
