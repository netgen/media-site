/* eslint-disable import/no-unresolved */
import Swiper from 'swiper/bundle';

export default class SwiperBase {
  constructor(element, options) {
    this.element = element;
    this.dataset = element.dataset;

    this.swiper = false;
    this.swiperConfig = options.swiperConfig;

    this.swiperPreviousButton = element.querySelector(options.swiperPreviousButton);
    this.swiperNextButton = element.querySelector(options.swiperNextButton);
    this.swiperPagination = element.querySelector(options.swiperPagination);
    this.swiperPaginationType = options.swiperPaginationType;

    this.init();
  }

  init() {
    const { loop, autoplay, effect, slidesPerView, slidesPerGroup } = this.dataset;

    this.swiper = new Swiper(this.element, {
      navigation: {
        nextEl: this.swiperNextButton,
        prevEl: this.swiperPreviousButton,
      },
      pagination: {
        el: this.swiperPagination,
        clickable: true,
        type: this.swiperPaginationType ? this.swiperPaginationType : 'fraction',
      },
      loop,
      effect,
      autoplay: autoplay ? { delay: autoplay * 1000 } : false,
      slidesPerView: slidesPerView ? parseInt(slidesPerView, 10) : 1,
      slidesPerGroup: slidesPerGroup
        ? parseInt(slidesPerGroup, 10)
        : parseInt(slidesPerView, 10) || 1,
      on: {
        lazyImageReady() {
          this.updateAutoHeight();
        },
      },
      ...this.swiperConfig,
    });
  }
}
