import $ from 'jquery';
import CookieControl from '@netgen/javascript-cookie-control';

export default class CookieControlClass {
    constructor(element, options) {
        this.options = options;

        this.optionalSaveBtn = element.querySelector(options.optionalSaveBtn);
        this.optionalList = element.querySelector(options.optionalList);
        this.optionalListToggle = element.querySelector(options.optionalListToggle);

        this.onInit();
    }

    onInit() {
      const self = this;
        const cookieControl = new CookieControl(window.__ngCcConfig); // eslint-disable-line no-underscore-dangle
        cookieControl.init();

        /* cookie control optional list toggle */
        $(self.optionalListToggle).on('click', function (e) {
          e.preventDefault();

          $(this).toggleClass(self.options.rotateArrowClass);
          const list = $(self.optionalList);

          if (list.hasClass(self.options.shownClass)) {
            list.removeClass(self.options.shownClass).slideUp();
          } else {
            list.addClass(self.options.shownClass).slideDown();
          }
        });
        /* /cookie control optional list toggle */

        /* cookie consent changed */
        $(self.optionalSaveBtn).on('click', (e) => {
          e.preventDefault();
          dataLayer.push({
            event: 'ngcc-changed',
          });
        });
    }
}