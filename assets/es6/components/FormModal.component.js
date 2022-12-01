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

          const gtmEventPrefix = $('#form-modal-body form').data('gtm-event-prefix');

          if (typeof gtmEventPrefix !== 'undefined') {
            window.dataLayer && window.dataLayer.push({ event: `${gtmEventPrefix}-opened` });
            setCookie(`${gtmEventPrefix}-submitted`, 'false');
            // console.log(`GTM event pushed: ${gtmEventPrefix}-opened`);
          }

          modal.on('hidden.bs.modal', () => {
            modal.modal('dispose');
            modal.remove();

            if (typeof gtmEventPrefix !== 'undefined') {
              const submitted = getCookie(`${gtmEventPrefix}-submitted`);

              if (submitted === 'false') {
                window.dataLayer && window.dataLayer.push({ event: `${gtmEventPrefix}-canceled` });
                // console.log(`GTM event pushed: ${formIdentifier}-canceled`);
              }
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
      const gtmEventPrefix = $('#form-modal-body form').data('gtm-event-prefix');
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

          if (typeof gtmEventPrefix !== 'undefined') {
            window.dataLayer && window.dataLayer.push({ event: `${gtmEventPrefix}-submitted` });
            setCookie(`${gtmEventPrefix}-submitted`, 'true');
            // console.log(`GTM event pushed: ${formIdentifier}-submitted`);
          }
        },
        error(XMLHttpRequest, textStatus, errorThrown) {
          if (typeof gtmEventPrefix !== 'undefined') {
            window.dataLayer && window.dataLayer.push({ event: `${gtmEventPrefix}-failed` });
            // console.log(`GTM event pushed: ${gtmEventPrefix}-failed`);
          }

          // eslint-disable-next-line no-alert
          alert(`Error: ${errorThrown}`);
        },
      });
    });
  }
}
