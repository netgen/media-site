import { Modal } from 'bootstrap';

const bootstrapModalTemplate = (
  modalCssClasses,
  dialogCssClasses,
  modalId = 'bootstrap-modal-placeholder',
  transition = 'fade'
) =>
  `<div id="${modalId}" class="modal ${transition} ${modalCssClasses}" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog ${dialogCssClasses}" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body"></div>
      </div>
    </div>
</div>`;

export default class BootstrapModalDynamic {
  constructor({
    receivedContent,
    modalCssClasses,
    dialogCssClasses,
    modalId,
    transition,
    onOpenCallback,
    onCloseCallback,
  }) {
    this.modalCssClasses = modalCssClasses;
    this.dialogCssClasses = dialogCssClasses;
    this.modalId = modalId;
    this.transition = transition;
    this.receivedContent = receivedContent;
    this.onOpenCallback = onOpenCallback;
    this.onCloseCallback = onCloseCallback;
    this.modalShown = false;
    this.modalElement = null;
    this.bootstrapModal = null;
    this.tempModal = document.createElement('tempModal');
    this.contentElement = null;
    this.tempContent = document.createElement('tempContent');

    this.init();
  }

  init() {
    if (this.modalShown) {
      this.closeModal();
    }
    this.createModal();
  }

  createModal() {
    this.tempModal.innerHTML = bootstrapModalTemplate(
      this.modalCssClasses,
      this.dialogCssClasses,
      this.modalId,
      this.transition
    );
    this.modalElement = this.tempModal.firstChild;
    this.bootstrapModal = new Modal(this.modalElement);
    this.setupContent();
  }

  setupContent() {
    this.tempContent.innerHTML = this.receivedContent.trim();
    this.contentElement = this.tempContent.firstChild;
    this.modalElement.querySelector('.modal-body').appendChild(this.contentElement);
    this.setupEvents();
  }

  setupEvents() {
    this.modalElement.addEventListener('hidden.bs.modal', () => this.closeModal());
    this.insertModalToDom();
  }

  insertModalToDom() {
    document.body.appendChild(this.modalElement);
    this.openModal();
  }

  openModal() {
    this.bootstrapModal.show();
    this.modalShown = true;
    if (this.onOpenCallback && this.onOpenCallback instanceof Function) {
      this.onOpenCallback();
    }
  }

  closeModal() {
    this.bootstrapModal.dispose();
    this.modalElement.remove();
    this.modalShown = false;
    if (this.onCloseCallback && this.onCloseCallback instanceof Function) {
      this.onCloseCallback();
    }
  }
}
