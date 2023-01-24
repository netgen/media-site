import VideoPoster from './VideoPoster.component';

export default class AjaxCollection {
  constructor(element, options) {
    this.element = element;
    this.options = options;

    this.init();
  }

  init() {
    this.element.addEventListener(
      'ajax-paging-added',
      this.handleAjaxPagingAddedEvent.bind(this),
      false
    );
  }

  handleAjaxPagingAddedEvent() {
    const posters = this.element.querySelectorAll(this.options.posters);

    posters.forEach((poster) => {
      // eslint-disable-next-line no-new
      new VideoPoster(poster, {
        vimeoClass: this.options.vimeoClass,
        dailymotionClass: this.options.dailymotionClass,
        posterLinkElement: this.options.posterLinkElement,
      });
    });
  }
}
