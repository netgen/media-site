export default class SubscriptionWidget {
  constructor(element, options) {
    this.el = element;
    this.options = options;
    this.modalBody = this.el.querySelector(options.modalBody);
    this.subscriptionForm = this.el.querySelector(options.subscriptionForm);
    this.digestType = this.el.querySelector(options.digestType);
    this.digestOptions = this.el.querySelector(options.digestOptions);
    this.buttonSubscribe = this.el.querySelector(options.buttonSubscribe);

    this.onInit();
  }

  onInit() {
    if (this.digestType && this.digestType.checked) {
      this.digestOptions.classList.remove('d-none');
      SubscriptionWidget.activateSubscribeButton(this.buttonSubscribe);
    }

    this.el.addEventListener('click', (e) => {
      const buttonSubscribe = e.currentTarget.querySelector(this.options.buttonSubscribe);

      if (e.target.matches(this.options.subscriptionTypeRadios)) {
        this.changeType(e);
        SubscriptionWidget.activateSubscribeButton(buttonSubscribe);
      }
    });

    this.el.addEventListener('submit', (e) => {
      if (e.target.matches(this.options.subscriptionForm)) {
        const modalBody = e.currentTarget.querySelector(this.options.modalBody);

        e.preventDefault();
        this.formSubmit(modalBody, e.target);
      }
    });
  }

  changeType(e) {
    const digestOptions = e.currentTarget.querySelector(this.options.digestOptions);
    const digestType = e.currentTarget.querySelector(this.options.digestType);

    if (e.target === digestType || digestType.checked) {
      digestOptions.classList.remove('d-none');
    } else {
      digestOptions.classList.add('d-none');
    }
  }

  static activateSubscribeButton(button) {
    if (button) {
      button.removeAttribute('disabled');
      button.classList.replace('btn-tertiary', 'btn-primary');
    }
  }

  formSubmit(modalBody, subscriptionForm) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', this.subscriptionForm.action);
    xhr.onload = function (event) {
      modalBody.innerHTML = event.target.response;
      const errorMessage = modalBody.querySelector('.errors');
      const subscribeButton = modalBody.querySelector('button[type=submit]');

      if (errorMessage && errorMessage.closest('.digest-options')) {
        errorMessage.closest('.digest-options').classList.remove('d-none');
      }

      SubscriptionWidget.activateSubscribeButton(subscribeButton);
    };
    const formData = new FormData(subscriptionForm);
    xhr.send(formData);
  }
}
