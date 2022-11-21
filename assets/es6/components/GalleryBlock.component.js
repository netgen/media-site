export default class GalleryBlock {
    constructor(element, options) {
        this.el = element; 
        this.options = options;

        this.layoutAsFlexElements = element.querySelectorAll(options.layoutAsFlexElements)
        this.lightboxEnabledElements = element.querySelectorAll(options.lightboxEnabledElements)

        this.onInit()
    }

    onInit() {
        this.layoutAsFlexElements.forEach(el => {
            const hasChildren = el.querySelectorAll('*');
            if(!hasChildren) el.remove();
        });
        
        this.lightboxEnabledElements.forEach(el => {
            el.magnificPopup({
                delegate: this.options.popupToggle, // child items selector, by clicking on it popup will open
                type: 'image',
                zoom: {
                    enabled: true,
                },
                gallery: {
                    enabled: true,
                },
            });
        });
    }
}