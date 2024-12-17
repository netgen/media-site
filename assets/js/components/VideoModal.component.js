import BootstrapModalDynamic from '../utilities/bootstrap-modal-dynamic/bootstrap-modal-dynamic';
import * as templates from '../utilities/video-modal/templates';

export default class VideoModalComponent {
  constructor(trigger, options) {
    this.options = options;
    this.trigger = trigger;
    this.collectedVideoOptions = null;
    this.bootstrapModalDynamic = null;
    this.modalContent = null;
    this.promises = [];
    this.externalAssets = [
      { url: 'https://vjs.zencdn.net/7.20.3/video-js.min.css', type: 'text/css' },
      { url: 'https://vjs.zencdn.net/7.20.3/video.min.js', type: 'text/javascript' },
    ];
    this.internalAssets = [{ url: 'local-modal-style', type: 'text/css' }];

    this.init();
  }

  init() {
    this.trigger.addEventListener('click', this.handleButtonClick.bind(this));
  }

  handleButtonClick(event) {
    event.preventDefault();

    this.collectedVideoOptions = JSON.parse(this.trigger.getAttribute('data-video-options'));
    this.getResources();
    this.setupVideoData();
  }

  getResources() {
    this.internalAssets.forEach((asset) => {
      if (document.querySelector(`[data-id="video-${asset.url}"]`) === null) {
        VideoModalComponent.loadResources(asset, templates.videoModalStyleTemplate());
      }
    });

    if (this.collectedVideoOptions.type === 'upload') {
      this.externalAssets.forEach((asset) => {
        if (document.querySelector(`[data-id="video-${asset.url}"]`) === null) {
          fetch(asset.url)
            .then((response) => {
              if (!response.ok) {
                throw new Error();
              }

              return response.text();
            })
            .then((data) => VideoModalComponent.loadResources(asset, data))
            .catch((error) => {
              console.error(`Failed to load resources: ${error}`);
            });
        }
      });
    }
  }

  setupVideoData() {
    this.collectedVideoOptions.id = `video-js-${this.collectedVideoOptions.type}`;
    this.collectedVideoOptions.width = '100%';
    this.collectedVideoOptions.controls = 'controls';
    this.collectedVideoOptions.preload = 'auto';

    this.collectedVideoOptions.class = 'vjs-fill';

    let autoplayAttribute = '';
    if (this.collectedVideoOptions.autoplay === true) {
      autoplayAttribute = 'autoplay';

      if (this.collectedVideoOptions.type !== 'upload') {
        autoplayAttribute += ' allow="autoplay"';
      }
    }

    this.createModalContent(autoplayAttribute);
  }

  createModalContent(autoplayAttribute) {
    const template = templates[`${this.collectedVideoOptions.type}VideoTemplate`];
    const templateOptions = {
      autoplayAttribute,
    };

    if (this.collectedVideoOptions.type === 'upload') {
      templateOptions.collectedVideoOptions = this.collectedVideoOptions;
      templateOptions.videoSource = templates.videoSourceTemplate(this.collectedVideoOptions);
    } else {
      templateOptions.videoIdentifier = this.collectedVideoOptions.identifier;
      templateOptions.type = this.collectedVideoOptions.type;
      templateOptions.videoTitle = this.collectedVideoOptions.videoTitle;
    }

    this.modalContent = template(templateOptions);

    this.createBootstrapModalDynamic();
  }

  createBootstrapModalDynamic() {
    this.bootstrapModalDynamic = new BootstrapModalDynamic({
      receivedContent: this.modalContent,
      modalCssClasses: 'video-modal',
      dialogCssClasses: 'modal-dialog-centered modal-fullscreen',
      modalId: 'video-js-modal',
      transition: 'fade',
    });
  }

  static loadResources(asset, data) {
    let fileRef = null;
    if (asset.type === 'text/javascript') {
      fileRef = document.createElement('script');
    } else if (asset.type === 'text/css') {
      fileRef = document.createElement('style');
    }

    fileRef.innerHTML = data;
    fileRef.setAttribute('data-id', `video-${asset.url}`);
    fileRef.setAttribute('type', asset.type);

    document.body.appendChild(fileRef);
  }
}
