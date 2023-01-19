import PhotoSwipeLightbox from 'photoswipe/lightbox';

export default class GalleryBlock {
    constructor(element, options) {
        this.el = element;
        this.options = options;

        this.layoutAsFlexElements = element.querySelectorAll(options.layoutAsFlexElements)
        this.lightboxEnabledElements = element.querySelectorAll(options.lightboxEnabledElements)

        this.onInit()
    }

    onInit() {
        this.lightboxEnabledElements.forEach((element) => {
            const lightbox = new PhotoSwipeLightbox({
                gallery: element,
                children: '.js-lightbox-item',
                pswpModule: () => import('photoswipe'),
            });

            lightbox.init();
        });
    }
}
