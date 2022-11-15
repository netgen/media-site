export default class SwiperThumb {
    constructor(element, options) {
        this.el = element;
        this.options = options;
        
        this.data = element.dataset;
        this.swiper = false;
        this.swiperPrevBtn = element.querySelector(options.swiperPrevBtn)
        this.swiperNextBtn = element.querySelector(options.swiperNextBtn)

        this.topSwiper = element.querySelectorAll(options.topSwiper)[0]
        this.topSwiperConfig = options.topSwiperConfig;
        this.thumbnailsSwiper = element.querySelectorAll(options.thumbnailsSwiper)[0]
        this.thumbnailsSwiperConfig = options.thumbnailsSwiperConfig;
        
        this.onInit()
    }

    onInit() {
        const self = this;

        const { loop, autoplay, effect, slidesPerView, slidesPerGroup } = self.data;

        const galleryTop = new Swiper(this.topSwiper, {
            navigation: {
              nextEl: this.swiperPrevBtn,
              prevEl: this.swiperNextBtn,
            },
            loop: loop,
            effect: effect,
            autoplay: autoplay ? { delay: autoplay * 1000 } : false,
            loopedSlides: loop ? length : null,
            on: {
              lazyImageReady() {
                this.updateAutoHeight();
              },
            },
            ...this.topSwiperConfig
        })

        const galleryThumbs = new Swiper(this.thumbnailsSwiper, {
            loop: loop,
            ...this.thumbnailsSwiperConfig
        });

        galleryTop.controller.control = galleryThumbs;
        galleryThumbs.controller.control = galleryTop;
    }
}