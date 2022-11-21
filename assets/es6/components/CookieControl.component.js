import $ from 'jquery';
import CookieControl from '@netgen/javascript-cookie-control';

export default class CookieControlClass {
    constructor(element, options) {
        this.el = element
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
        this.optionalListToggle.addEventListener('click', (e) => {
          e.preventDefault();

          this.optionalListToggle.classList.toggle(this.options.rotateArrowClass);
          const isVisible = [...this.optionalList].classList.includes(this.options.shownClass)
          
          if(isVisible) {
            this.optionalListToggle.slideUp()
          } else {
            this.optionalListToggle.slideDown()
          }

          this.optionalListToggle.classList.toggle(this.options.shownClass);
        })
        /* /cookie control optional list toggle */

        /* cookie consent changed */
        this.optionalSaveBtn.addEventListener('click', (e) => {
          e.preventDefault();
          
          if(!dataLayer) {
            console.warn('Data layer is not defined!');
            return;
          }
          
          dataLayer.push({
            event: 'ngcc-changed',
          });
        })
        /* /cookie consent changed */
    }
}