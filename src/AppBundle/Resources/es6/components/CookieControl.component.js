import CookieControl from '@netgen/javascript-cookie-control';
import $ from 'jquery';

export default class CookieControlClass {
    constructor(element, options) {
        this.options = options;

        this.onInit();
    }

    onInit() {
        const cookieControl = new CookieControl(window.__ngCcConfig); // eslint-disable-line no-underscore-dangle
        cookieControl.init();

        /* cookie control optional list toggle */
        $('.optional-list-toggle').on('click', function (e) {
          e.preventDefault();

          $(this).toggleClass('rotate-arrow');
          const list = $('.ng-cc-optional-list');

          if (list.hasClass('shown')) {
            list.removeClass('shown').slideUp();
          } else {
            list.addClass('shown').slideDown();
          }
        });
        /* /cookie control optional list toggle */

        /* cookie consent changed */
        $('#ng-cc-accept, #ng-cc-optional-save').on('click', (e) => {
          e.preventDefault();
          dataLayer.push({
            event: 'ngcc-changed',
          });
        });
    }
}