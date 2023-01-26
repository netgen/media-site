import BootstrapModalDynamic from '../utilities/bootstrap-modal-dynamic/bootstrap-modal-dynamic';

const videoSourceTemplate = (collectedVideoOptions) =>
  `<source src="${collectedVideoOptions.fileLink}" type="${collectedVideoOptions.mimeType}" />`;
const uploadVideoTemplate = (collectedVideoOptions, videoSource, autoplayAttribute) =>
  `<video
    id="${collectedVideoOptions.id}"
    data-setup='{}'
    class="video-js ${collectedVideoOptions.class}"
    ${collectedVideoOptions.controls}
    preload="${collectedVideoOptions.preload}"
    width="${collectedVideoOptions.width}"
    poster="${collectedVideoOptions.poster}"
    ${autoplayAttribute}>
    ${videoSource}
  </video>`;
const youtubeVideoTemplate = (videoIdentifier, autoplayAttribute) =>
  `<div class="video-youtube iframe-video ratio ratio-16x9">
    <iframe frameborder="0" src="https://www.youtube.com/embed/${videoIdentifier}?autoplay=1" ${autoplayAttribute} width="770" height="433" allowfullscreen></iframe>
  </div>`;
const vimeoVideoTemplate = (videoIdentifier, autoplayAttribute) =>
  `<div class="video-vimeo iframe-video ratio ratio-16x9">
    <iframe frameborder="0" src="https://player.vimeo.com/video/${videoIdentifier}?autoplay=1" ${autoplayAttribute} width="770" height="433" allowfullscreen></iframe>
  </div>`;
const dailymotionVideoTemplate = (videoIdentifier, autoplayAttribute) =>
  `<div class="video-dailymotion iframe-video ratio ratio-16x9">
    <iframe frameborder="0" src="https://www.dailymotion.com/embed/video/${videoIdentifier}" ${autoplayAttribute} width="770" height="433" allowfullscreen></iframe>
  </div>`;
const videoModalStyle = () =>
  `.modal.video-modal .modal-content {
    background-color: #000;
 }
  .modal.video-modal .modal-content .modal-header {
    border: 0;
    justify-content: center;
 }
  .modal.video-modal .modal-content .modal-header .btn-close {
    margin: 0;
    filter: brightness(0) saturate(100%) invert(100%) sepia(19%) saturate(0%) hue-rotate(206deg) brightness(103%) contrast(101%);
 }
  .modal.video-modal .modal-content .modal-body {
    order: -1;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
 }
  .modal.video-modal .modal-content .modal-body .video-js {
    height: 100%;
 }
  .modal.video-modal .modal-content .modal-body .video-js .vjs-big-play-button {
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    border: 0;
    font-size: 5rem;
    height: 2em;
    width: 2em;
    line-height: 2em;
    border-radius: 50%;
    filter: brightness(75%);
 }`;

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
    this.internalAssets = [
      { url: 'local-modal-style', type: 'text/css' }
    ]

    this.init();
  }

  init() {
    this.trigger.addEventListener('click', this.handleButtonClick.bind(this));
  }

  handleButtonClick(e) {
    e.preventDefault();
    this.collectedVideoOptions = JSON.parse(this.trigger.getAttribute('data-video-options'));
    this.getResources();
    this.setupVideoData();
  }

  getResources() {
    this.internalAssets.forEach((asset) => {
      if(document.querySelector(`[data-id="video-${asset.url}"]`) === null) {
        VideoModalComponent.loadResources(asset, videoModalStyle());
      }
    });
    if (this.collectedVideoOptions.type === 'upload') {
      this.externalAssets.forEach((asset) => {
        if(document.querySelector(`[data-id="video-${asset.url}"]`) === null) {
          fetch(asset.url)
            .then((response) => {
              if (!response.ok) {
                throw new Error();
              }
              return response.text();
            })
            .then(data => VideoModalComponent.loadResources(asset, data))
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
    if (this.collectedVideoOptions.autoResize === 'fit') {
      this.collectedVideoOptions.class = 'vjs-fluid';
    } else if (this.collectedVideoOptions.autoResize === 'fill') {
      this.collectedVideoOptions.class = 'vjs-fill';
    }
    this.collectedVideoOptions.class = 'vjs-fill';

    let autoplayAttribute = '';
    if (this.collectedVideoOptions.type === 'upload' && this.collectedVideoOptions.autoplay) {
      autoplayAttribute = 'autoplay';
    } else {
      autoplayAttribute = 'autoplay allow="autoplay"';
    }

    this.createModalContent(autoplayAttribute);

  }

  createModalContent(autoplayAttribute) {
    if (this.collectedVideoOptions.type === 'upload') {
      this.modalContent = uploadVideoTemplate(this.collectedVideoOptions, videoSourceTemplate(this.collectedVideoOptions), autoplayAttribute);
    } else if (this.collectedVideoOptions.type === 'youtube') {
      this.modalContent = youtubeVideoTemplate(this.collectedVideoOptions.identifier, autoplayAttribute);
    } else if (this.collectedVideoOptions.type === 'vimeo') {
      this.modalContent = vimeoVideoTemplate(this.collectedVideoOptions.identifier, autoplayAttribute);
    } else if (this.collectedVideoOptions.type === 'dailymotion') {
      this.modalContent = dailymotionVideoTemplate(this.collectedVideoOptions.identifier, autoplayAttribute);
    }

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
