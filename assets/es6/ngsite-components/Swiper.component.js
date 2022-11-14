export default class SwiperClass {
    constructor(element, options) {
        this.el = element;
        this.options = options;
        
        this.data = element.dataset;
        this.swiper = false;
        this.swiperPrevBtn = element.querySelector(options.swiperPrevBtn)
        this.swiperNextBtn = element.querySelector(options.swiperNextBtn)
        this.swiperPagination = element.querySelector(options.swiperPagination)

        this.onInit()
    }

    onInit() {
        const self = this;

        self.swiper = new Swiper(self.el, {
            navigation: {
              nextEl: this.swiperNextBtn,
              prevEl: this.swiperPrevBtn,
            },
            pagination: {
              el: this.swiperPagination,
              type: 'fraction',
            },
            preloadImages: false,
            loop: self.data.loop,
            effect: self.data.effect,
            autoplay: self.data.autoplay ? { delay: self.data.autoplay * 1000 } : false,
            lazy: {
              loadPrevNext: true,
              loadPrevNextAmount: 1,
              loadOnTransitionStart: true,
            },
            autoHeight: true,
            on: {
              lazyImageReady() {
                this.updateAutoHeight();
              },
            },
          })
    }
}