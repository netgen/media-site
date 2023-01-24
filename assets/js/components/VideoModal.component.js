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
  `<div class="video-youtube ratio ratio-16x9">
    <iframe frameborder="0" src="https://www.youtube.com/embed/${videoIdentifier}" ${autoplayAttribute} width="770" height="433" allowfullscreen></iframe>
  </div>`;
const vimeoVideoTemplate = (videoIdentifier, autoplayAttribute) =>
  `<div class="video-vimeo ratio ratio-16x9">
    <iframe frameborder="0" src="https://player.vimeo.com/video/${videoIdentifier}" ${autoplayAttribute} width="770" height="433" allowfullscreen></iframe>
  </div>`;
const dailymotionVideoTemplate = (videoIdentifier, autoplayAttribute) =>
  `<div class="video-dailymotion ratio ratio-16x9">
    <iframe frameborder="0" src="https://www.dailymotion.com/embed/video/${videoIdentifier}" ${autoplayAttribute} width="770" height="433" allowfullscreen></iframe>
  </div>`;

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

    this.init();
  }

  init() {
    this.trigger.addEventListener('click', (e) => {
      e.preventDefault();

      // this.setupVideo();

      this.externalAssets.forEach((asset) => {
        if(document.getElementById(`video-js-${asset.type}`) === null) {
          this.promises.push(this.loadExternalAssets(asset));
        }
      });

      Promise.all(this.promises)
        .then(() => {
          console.log('all scripts loaded');
          this.setupVideo();
        })
        .catch((script) => {
          console.log(`${script} failed to load`);
        });
    });
  }

  setupVideo() {
    this.collectedVideoOptions = JSON.parse(this.trigger.getAttribute('data-video-options'));
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
    if (this.collectedVideoOptions.autoplay) {
      autoplayAttribute = 'autoplay';
    }

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

  loadExternalAssets() {
    const head = document.getElementsByTagName('head')[0];
    return new Promise((resolve, reject) => {
      this.externalAssets.forEach((asset) => {
        if(document.getElementById(`video-js-${asset.type}`) === null) {
          let fileRef = null;
          if (asset.type === 'text/javascript') {
            fileRef = document.createElement('script');
          } else if (asset.type === 'text/css') {
            fileRef = document.createElement('link');
            fileRef.setAttribute('rel', 'stylesheet');
          }
          fileRef.setAttribute('id', `video-js-${asset.type}`);
          fileRef.setAttribute('type', asset.type);
          fileRef.setAttribute('src', asset.url);
          if (asset.type === 'text/javascript') {
            fileRef.onload = () => {
              resolve();
            }
            fileRef.onerror = () => {
              reject(new Error(`cannot load file ${ asset.url}`));
            }
          } else {
            resolve();
          }
          head.appendChild(fileRef);
        }
      });
    })
  }
}
