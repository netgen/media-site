export const videoSourceTemplate = (collectedVideoOptions) =>
  `<source src="${collectedVideoOptions.fileLink}" type="${collectedVideoOptions.mimeType}" />`;

export const uploadVideoTemplate = ({ collectedVideoOptions, videoSource, autoplayAttribute }) =>
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

const iframeEmbedTemplate = ({ videoTitle, src, srcParameters, autoplayAttribute, type }) =>
  `<div class="video-${type} iframe-video ratio ratio-16x9">
      <iframe frameborder="0" src="${src}?${srcParameters}" title="${videoTitle}" ${autoplayAttribute} allowfullscreen></iframe>
  </div>`;

export const youtubeVideoTemplate = ({ videoTitle, videoIdentifier, autoplayAttribute, type }) =>
  iframeEmbedTemplate({
    src: `https://www.youtube.com/embed/${videoIdentifier}`,
    srcParameters: 'autoplay=1',
    autoplayAttribute,
    type,
    videoTitle,
  });

export const vimeoVideoTemplate = ({ videoTitle, videoIdentifier, autoplayAttribute, type }) =>
  iframeEmbedTemplate({
    src: `https://player.vimeo.com/video/${videoIdentifier}`,
    srcParameters: 'autoplay=1',
    autoplayAttribute,
    type,
    videoTitle,
  });

export const dailymotionVideoTemplate = ({
  videoTitle,
  videoIdentifier,
  autoplayAttribute,
  type,
}) =>
  iframeEmbedTemplate({
    src: `https://www.dailymotion.com/embed/video/${videoIdentifier}`,
    srcParameters: 'autoplay=1',
    autoplayAttribute,
    type,
    videoTitle,
  });

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
  .modal.video-modal .modal-content .modal-body .iframe-video {
    max-height: 100%;
  }
  .modal.video-modal .modal-content .modal-body .video-js {
    max-height: 100%;
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
