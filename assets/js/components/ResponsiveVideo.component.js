export default class VideoHandler {
  constructor(element) {
    this.element = element;

    this.init();
  }

  init() {
    this.setSourceBasedOnWindowSize();
  }

  setSourceBasedOnWindowSize() {
    let srcDatasetKey = 'desktopVid';
    if (window.innerWidth < 768) {
      srcDatasetKey = 'mobileVid';
    }

    if (this.element.dataset[srcDatasetKey] === undefined) {
      return;
    }

    const source = document.createElement('source');
    source.src = this.element.dataset[srcDatasetKey];
    source.type = 'video/mp4';

    this.element.appendChild(source);
  }
}
