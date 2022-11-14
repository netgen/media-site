export default class LazyLoading {
    constructor(element, options) {
        this.options = options;
        this.onInit();
    }

    onInit() {
        if ('IntersectionObserver' in window) {
            const lazyImageObserver = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        const lazyImage = entry.target;
                        this.lazyImageLoad(lazyImage);
                        lazyImageObserver.unobserve(lazyImage);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach((lazyImage) => {
                lazyImageObserver.observe(lazyImage);
            });
        } else {
            this.loadAllLazy();
        }
    }

    lazyImageLoad(image) {
        if (image.hasAttribute('data-src')) image.setAttribute('src', image.getAttribute('data-src'));
        if (image.hasAttribute('data-srcset')) {
            image.setAttribute('srcset', image.getAttribute('data-srcset'));
        }

        image.onload = () => {
            image.removeAttribute('data-src');
            image.removeAttribute('data-srcset');
        };
    };

    loadAllLazy() {
        document.querySelectorAll('img[data-src]').forEach((img) => {
            this.lazyImageLoad(img)
        });
    };
}

