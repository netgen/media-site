export const videoSourceTemplate = (collectedVideoOptions) =>
  `<source src="${collectedVideoOptions.fileLink}" type="${collectedVideoOptions.mimeType}" />`;

export const uploadVideoTemplate = (collectedVideoOptions, videoSource, autoplayAttribute) =>
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

export const youtubeVideoTemplate = (videoIdentifier, autoplayAttribute) =>
  `<div class="video-youtube iframe-video ratio ratio-16x9">
    <iframe frameborder="0" src="https://www.youtube.com/embed/${videoIdentifier}?autoplay=1" ${autoplayAttribute} width="770" height="433" allowfullscreen></iframe>
  </div>`;

export const vimeoVideoTemplate = (videoIdentifier, autoplayAttribute) =>
  `<div class="video-vimeo iframe-video ratio ratio-16x9">
    <iframe frameborder="0" src="https://player.vimeo.com/video/${videoIdentifier}?autoplay=1" ${autoplayAttribute} width="770" height="433" allowfullscreen></iframe>
  </div>`;

export const dailymotionVideoTemplate = (videoIdentifier, autoplayAttribute) =>
  `<div class="video-dailymotion iframe-video ratio ratio-16x9">
    <iframe frameborder="0" src="https://www.dailymotion.com/embed/video/${videoIdentifier}?autoplay=1" ${autoplayAttribute} width="770" height="433" allowfullscreen></iframe>
  </div>`;

export const videoModalStyleTemplate = () =>
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
