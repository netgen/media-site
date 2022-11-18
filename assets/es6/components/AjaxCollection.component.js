import VideoPoster from "./VideoPoster.component";
import LazyLoading from "./LazyLoading.component";

export default class AjaxCollection {
    constructor(element, options) {
        this.el = element;
        this.options = options;

        this.onInit();
    }

    onInit() {
        this.el.addEventListener('ajax-paging-added', () => {
            const posters = this.el.querySelectorAll(this.options.posters);

            posters.forEach(poster => {
                new VideoPoster(poster, {
                    vimeoClass: this.options.vimeoClass,
                    dailymotionClass: this.options.dailymotionClass,
                    posterLinkElement: this.options.posterLinkElement
                })
            })

            this.el.querySelectorAll('img[data-src]').forEach((img) => {
                LazyLoading.lazyImageLoad(img)
            })
        }, false)
    }
}