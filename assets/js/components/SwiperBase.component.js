import Swiper from 'swiper/dist/js/swiper';

export default class SwiperBase {
    constructor(element, options) {
        this.el = element;
        this.options = options;

        this.data = element.dataset;
        this.swiper = false;
        this.swiperConfig = options.swiperConfig;
        this.swiperPrevBtn = element.querySelector(options.swiperPrevBtn)
        this.swiperNextBtn = element.querySelector(options.swiperNextBtn)
        this.swiperPagination = element.querySelector(options.swiperPagination)

        this.onInit()
    }

    onInit() {
        const self = this;

        const { loop, autoplay, effect, slidesPerView, slidesPerGroup } = self.data;

        self.swiper = new Swiper(self.el, {
            navigation: {
              nextEl: this.swiperNextBtn,
              prevEl: this.swiperPrevBtn,
            },
            pagination: {
              el: this.swiperPagination,
              clickable: true,
            },
            loop,
            effect,
            autoplay: autoplay ? { delay: autoplay * 1000 } : false,
            slidesPerView: slidesPerView ? parseInt(slidesPerView, 10) : 1,
            slidesPerGroup: slidesPerGroup ? parseInt(slidesPerGroup, 10) : parseInt(slidesPerView, 10) || 1,
            on: {
              lazyImageReady() {
                this.updateAutoHeight();
              },
            },
            ...this.swiperConfig
          })
    }
}
