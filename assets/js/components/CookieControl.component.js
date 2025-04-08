import NetgenCookieControl from '@netgen/javascript-cookie-control';
import GTM from '../utilities/gtm';

export default class CookieControl {
  constructor(element, options) {
    this.options = options;

    this.modal = element.querySelector(options.modal);
    this.optionalSaveBtn = element.querySelectorAll(options.optionalSaveBtn);
    this.optionalList = element.querySelector(options.optionalList);
    this.optionalListToggle = element.querySelector(options.optionalListToggle);

    this.init();
  }

  init() {
    CookieControl.initNetgenCookieControl(this.modal);
    this.optionalListToggle.addEventListener('click', this.handleOptionalListToggle.bind(this));
    this.optionalSaveBtn.forEach((element) =>
      element.addEventListener('click', CookieControl.handleConsentChange)
    );
  }

  static initNetgenCookieControl(modal) {
    // eslint-disable-next-line no-underscore-dangle
    const cookieControl = new NetgenCookieControl(window.__ngCcConfig);

    cookieControl.open = function (e) {
      e && e.preventDefault();
      this.el.setAttribute('open', '');

      this.opened = true;
      modal.focus();
    };

    cookieControl.init();
  }

  handleOptionalListToggle(event) {
    event.preventDefault();

    this.optionalList.classList.toggle(this.options.shownClass);
    const isVisible = this.optionalList.classList.contains(this.options.shownClass);
    this.optionalList.style.maxHeight = isVisible ? `100vh` : '0px';

    this.optionalListToggle.classList.toggle(this.options.rotateArrowClass);
    this.optionalListToggle.classList.toggle(this.options.shownClass);
  }

  static handleConsentChange(event) {
    event.preventDefault();

    GTM.push('ngcc', GTM.EVENTS.CHANGED);

    // check if analytics and marketing cookie status changed and update consents
    if (typeof window.getCookieStatus === 'function') {
      const analyticsStatus = window.getCookieStatus('ng-cc-analytics');
      if (window.lastAnalyticsStatus !== analyticsStatus) {
        window.gtag('consent', 'update', {
          ad_storage: analyticsStatus,
          analytics_storage: analyticsStatus,
        });
        window.lastAnalyticsStatus = analyticsStatus;
        const bcolor = analyticsStatus === 'granted' ? 'green' : 'orange';
        console.info(
          `%cAnalytics cookie changed to: ${analyticsStatus}`,
          `color: white; background-color: ${bcolor}`
        );
      }
      const marketingStatus = window.getCookieStatus('ng-cc-marketing');
      if (window.lastMarketingStatus !== marketingStatus) {
        window.gtag('consent', 'update', {
          ad_user_data: marketingStatus,
          ad_personalization: marketingStatus,
        });
        window.lastMarketingStatus = marketingStatus;
        const bcolor = marketingStatus === 'granted' ? 'green' : 'orange';
        console.info(
          `%cMarketing cookie changed to: ${marketingStatus}`,
          `color: white; background-color: ${bcolor}`
        );
      }
    }
  }
}
