import $ from 'jquery';
import { getCookie, setCookie } from '@netgen/javascript-cookie-control/js/helpers';

export default class FormModalComponent {
  constructor(element, options) {
    this.options = options;
    this.onInit(element);
  }

  onInit(element) {
    const self = this;

    $(element).on('click', function (e) {
      e.preventDefault();

      const url = $(this).data('url');

      $.ajax({
        url,
        type: 'GET',
        cache: false,
        success(response) {
          $('body').append(response);
          const modal = $('#form-modal');
          modal.modal('show');
          const formIdentifier = $('#form-modal-body form').data('identifier');
          window.dataLayer.push({ event: `${formIdentifier}-opened` });
          setCookie(`${formIdentifier}-submitted`, 'false');
          // console.log(`${formIdentifier}-opened`);
          modal.on('hidden.bs.modal', () => {
            modal.modal('dispose');
            modal.remove();
            const submitted = getCookie(`${formIdentifier}-submitted`);
            if (submitted === 'false') {
              window.dataLayer.push({ event: `${formIdentifier}-canceled` });
              // console.log(`${formIdentifier}-canceled`);
            }
          });
          self.handleFormSubmit();
        },
        error(XMLHttpRequest, textStatus, errorThrown) {
          // eslint-disable-next-line no-alert
          alert(`Error: ${errorThrown}`);
        },
      });
    });
  }

  // eslint-disable-next-line class-methods-use-this
  handleFormSubmit() {
    $('#form-modal-body').on('submit', 'form', function (e) {
      e.preventDefault();

      const $formContainer = $('#form-modal-body');
      const formData = new FormData(this);
      const formIdentifier = $('#form-modal-body form').data('identifier');
      const formUrl = $(this).attr('action');
      const $loaderGif = $('<div class="loading-animation"><span></span></div>');

      $formContainer.html($loaderGif);

      $.ajax({
        url: formUrl,
        type: 'POST',
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        success(response) {
          $formContainer.html(response);
          window.dataLayer.push({ event: `${formIdentifier}-submitted` });
          setCookie(`${formIdentifier}-submitted`, 'true');
          // console.log(`${formIdentifier}-submitted`);
        },
        error(XMLHttpRequest, textStatus, errorThrown) {
          window.dataLayer.push({ event: `${formIdentifier}-failed` });
          // console.log(`${formIdentifier}-failed`);
          // eslint-disable-next-line no-alert
          alert(`Error: ${errorThrown}`);
        },
      });
    });
  }
}
