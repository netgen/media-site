/* eslint-disable import/no-unresolved */
import Swiper from 'swiper/bundle';

export default class SwiperThumb {
  constructor(element, options) {
    this.options = options;

    this.swiper = false;

    this.swiperPreviousButton = element.querySelector(options.swiperPreviousButton);
    this.swiperNextButton = element.querySelector(options.swiperNextButton);

    this.topSwiper = element.querySelector(options.topSwiper);
    this.topSwiperConfig = options.topSwiperConfig;
    this.topDataset = this.topSwiper.dataset;

    this.thumbnailsSwiper = element.querySelector(options.thumbnailsSwiper);
    this.thumbnailsSwiperConfig = options.thumbnailsSwiperConfig;

    this.init();
  }

  init() {
    const { loop, autoplay, effect, length } = this.topDataset;

    const galleryTop = new Swiper(this.topSwiper, {
      navigation: {
        nextEl: this.swiperNextButton,
        prevEl: this.swiperPreviousButton,
      },
      loop,
      effect,
      autoplay: autoplay ? { delay: autoplay * 1000 } : false,
      loopedSlides: loop ? length : null,
      on: {
        lazyImageReady() {
          this.updateAutoHeight();
        },
      },
      ...this.topSwiperConfig,
    });

    const galleryThumbs = new Swiper(this.thumbnailsSwiper, {
      loop,
      ...this.thumbnailsSwiperConfig,
    });

    galleryTop.controller.control = galleryThumbs;
    galleryThumbs.controller.control = galleryTop;
  }
}
