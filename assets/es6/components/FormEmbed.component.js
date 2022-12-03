import GTM from '../utils/gtm';

export default class ModalFormSubmitComponent {
  constructor(form, options) {
    this.form = form;
    this.options = options;
    this.init();
  }

  init() {
    this.form.addEventListener('submit', (e) => {
      e.preventDefault();

      const action = this.form.getAttribute('action');
      const options = { method: 'POST', body: new FormData(this.form) };
      const gtmEventPrefix = this.form.getAttribute('data-gtm-event-prefix');
      const formContainer = this.form.parentElement;

      formContainer.innerHTML = '<div class="loading-animation"><span></span></div>';

      fetch(action, options)
        .then((response) => response.text())
        .then((text) => {
          formContainer.innerHTML = text.trim();
          GTM.push(gtmEventPrefix, 'submitted');
        }).catch((error) => {
          GTM.push(gtmEventPrefix, 'failed');
          console.error('Embedded form submit failed: ', error);
        });
    });
  }
}
