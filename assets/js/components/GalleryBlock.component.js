// eslint-disable-next-line import/no-unresolved
import PhotoSwipeLightbox from 'photoswipe/lightbox';

export default class GalleryBlock {
  constructor(gallery) {
    this.gallery = gallery;

    this.init();
  }

  init() {
    this.setupLightbox();
  }

  setupLightbox() {
    const lightboxGallery = this.gallery.querySelector('.js-lightbox-enabled');
    if (lightboxGallery === null) {
      return;
    }

    const lightbox = new PhotoSwipeLightbox({
      gallery: lightboxGallery,
      children: '.js-lightbox-item',
      pswpModule: () => import('photoswipe'),
    });
    lightbox.init();
  }
}
