import $ from 'jquery';

export default class ModalFormSubmitComponent {
  constructor(element, options) {
    this.options = options;
    this.onInit(element);
  }

  // eslint-disable-next-line class-methods-use-this
  onInit(element) {
    $(element).on('submit', 'form', function (e) {
      e.preventDefault();

      const formData = new FormData(this);
      const formUrl = $(this).attr('action');
      const gtmEventPrefix = $(this).data('gtm-event-prefix');
      const $loaderGif = $('<div class="loading-animation"><span></span></div>');

      $([document.documentElement, document.body]).animate( { scrollTop: $(element).offset().top - 100, }, 500 );

      $(element).html($loaderGif);

      $.ajax({
        url: formUrl,
        type: 'POST',
        enctype: 'multipart/form-data',
        data: formData,
        processData: false,
        contentType: false,
        cache: false,
        success(response) {
          $(element).html(response);

          if (typeof gtmEventPrefix !== 'undefined') {
            window.dataLayer.push({ event: `${gtmEventPrefix}-submitted` });
            // console.log(`GTM event pushed: ${gtmEventPrefix}-submitted`);
          }
        },
        error(XMLHttpRequest, textStatus, errorThrown) {
          if (typeof gtmEventPrefix !== 'undefined') {
            window.dataLayer.push({ event: `${gtmEventPrefix}-failed` });
            // console.log(`GTM event pushed: ${gtmEventPrefix}-failed`);
          }

          // eslint-disable-next-line no-alert
          alert(`Error: ${errorThrown}`);
        },
      });
    });
  }
}
