export default class ModalFormSubmitComponent {
  constructor(form, options) {
    this.form = form;
    this.options = options;
    this.init();
  }

  init() {
    this.form.addEventListener('submit', (e) => {
      e.preventDefault();

      const gtmEventPrefix = this.form.getAttribute('data-gtm-event-prefix');
      const formContainer = this.form.parentElement;

      formContainer.innerHTML = '<div class="loading-animation"><span></span></div>';

      fetch(this.form.getAttribute('action'), { method: 'POST', body: new FormData(this.form) })
        .then((response) => response.text())
        .then((text) => {
          formContainer.innerHTML = text.trim();
          this.gtmPush(gtmEventPrefix, 'submitted');
        }).catch((error) => {
          this.gtmPush(gtmEventPrefix, 'failed');
          // eslint-disable-next-line no-console
          console.error('Embedded form submit failed: ', error);
        });
    });
  }

  // eslint-disable-next-line class-methods-use-this
  gtmPush(prefix, suffix) {
    if (typeof prefix === 'undefined') {
      // eslint-disable-next-line no-console
      console.warn(`GTM push failed: prefix is not defined (${suffix})`);

      return;
    }

    const eventName = `${prefix}-${suffix}`;

    if (!('dataLayer' in window)) {
      // eslint-disable-next-line no-console
      console.warn(`GTM push failed: data layer is not available (${eventName})`);

      return;
    }

    window.dataLayer.push({ event: eventName });
    // eslint-disable-next-line no-console
    console.info(`GTM event pushed: ${eventName}`);
  }
}
