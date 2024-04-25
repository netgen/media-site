export default class ModalConfirmControl {
  constructor(element) {
    this.element = element;
    this.modalElement = document.querySelector(element.dataset.modalElementSelector);
    this.getFormUrl = element.dataset.getFormUrl;

    this.init();
  }

  init() {
    const that = this;

    this.element.addEventListener('click', () => {
      that.modalElement.innerHTML =
        '<div class="modal-body"><div id="spinner" class="spinner show"></div></div>';

      that.fetchFormHTML().then((data) => {
        that.modalElement.innerHTML = data;
      });
    });
  }

  fetchFormHTML() {
    return fetch(this.getFormUrl, { method: 'GET' }).then((response) => response.text());
  }
}
