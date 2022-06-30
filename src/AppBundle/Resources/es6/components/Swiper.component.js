export default class SwiperClass {
    constructor(element, options) {
        this.el = element;
        this.data = element.dataset;
        this.swiperId = `relatedMultimediaSwiper-1`; // @todo: figure out how to have a uniqe id (index??) / Is it even needed?
        this.options = options;

        this.swiper = false;

        this.onInit()
    }

    onInit() {
        const self = this;

        self.swiper = new Swiper(self.el, {
            navigation: {
              nextEl: self.el.querySelector('.swiper-button-next'),
              prevEl: self.el.querySelector('.swiper-button-prev'),
            },
            pagination: {
              el: self.el.querySelector('.swiper-pagination'),
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