import { Modal } from 'bootstrap';

const externalVideoTemplate = ( videoFileLink, videoMimeType, videoPoster, videoAutoplay, videoAutoResize) =>
  `<video
    id="video-${videoFileLink}"
    class="video-js ${videoAutoResize}"
    controls
    preload="auto"
    width="1024"
    ${videoAutoplay}
    poster="${videoPoster}"
   
    <source
      src="${videoFileLink}"
      type="${videoMimeType}"
    />
  </video>

  <link href="https://vjs.zencdn.net/7.20.3/video-js.css" rel="stylesheet" />
  <script src="https://vjs.zencdn.net/7.20.3/video.min.js"></script>`;
const youtubeVideoTemplate = (videoIdentifier, videoAutoplay) =>
  `<div class="video-youtube ratio ratio-16x9">
    <iframe frameborder="0" src="https://www.youtube.com/embed/${videoIdentifier} ${videoAutoplay}" width="770" height="433" allowfullscreen></iframe>
  </div>`;
const vimeoVideoTemplate = (videoIdentifier, videoAutoplay) =>
  `<div class="video-vimeo ratio ratio-16x9">
    <iframe frameborder="0" src="https://player.vimeo.com/video/${videoIdentifier} ${videoAutoplay}" width="770" height="433" allowfullscreen></iframe>
  </div>`;
const dailymotionVideoTemplate = (videoIdentifier, videoAutoplay) =>
  `<div class="video-dailymotion ratio ratio-16x9">
    <iframe frameborder="0" src="https://www.dailymotion.com/embed/video/${videoIdentifier} ${videoAutoplay}" width="770" height="433" allowfullscreen></iframe>
  </div>`;

export default class VideoModalComponent {
  constructor(trigger, options) {
    this.options = options;
    this.trigger = trigger;
    this.modalPlaceholder = document.querySelector('#modal-placeholder')
    this.modal = new Modal(this.modalPlaceholder);
    this.init();
  }

  init() {
    this.trigger.addEventListener('click', (e) => {
      e.preventDefault();
      const videoType = this.trigger.getAttribute('data-video-type');
      const videoIdentifier = this.trigger.getAttribute('data-video-identifier');
      let videoAutoResize = '';
      const autoResize = this.trigger.getAttribute('data-video-autoresize');
      if (autoResize === 'fit') {
        videoAutoResize = `vjs-fluid`;
      } else if (autoResize === 'fill') {
        videoAutoResize = `vjs-fill`;
      }
      videoAutoResize = `vjs-fill`;
      let videoAutoplay = '';
      const autoplayBool = this.trigger.getAttribute('data-video-autoplay');
      if (autoplayBool) {
        videoAutoplay = 'autoplay'
      }
      let modalContent = null;
      if (videoType === 'upload') {
        const videoFileLink = this.trigger.getAttribute('data-video-file-link');
        const videoMimeType = this.trigger.getAttribute('data-video-mime-type');
        const videoPoster = this.trigger.getAttribute('data-video-poster');
        modalContent = externalVideoTemplate(videoFileLink, videoMimeType, videoPoster, videoAutoplay, videoAutoResize)
      } else if (videoType === 'youtube') {
        modalContent = youtubeVideoTemplate(videoIdentifier, videoAutoplay)
      } else if (videoType === 'vimeo') {
        modalContent = vimeoVideoTemplate(videoIdentifier, videoAutoplay)
      } else if (videoType === 'dailymotion') {
        modalContent = dailymotionVideoTemplate(videoIdentifier, videoAutoplay)
      }
      this.openModal(modalContent);
    });
  }

  openModal(modalContent) {

    this.modalPlaceholder.addEventListener('hidden.bs.modal', () => this.closeModal(this.modal, this.modalPlaceholder));
    this.modalPlaceholder.querySelector('.modal-body').innerHTML = modalContent;
    this.modal.show();
  }

  closeModal() {
    this.modal.hide();
  }
}
