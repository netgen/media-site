var localPlayer = new MediaElementPlayer('video-local-1', {
    pluginPath: 'https://cdnjs.com/libraries/mediaelement/',
    // When using `MediaElementPlayer`, an `instance` argument
    // is available in the `success` callback
    shimScriptAccess: 'always',
    stretching: 'responsive',
    success: function(mediaElement, originalNode, instance) {
      // do things

      // Notice: podcastLabel is not used in GTM
      // var podcastLabel = $(".ramiropodcast").data("podcast-label");

      // mediaElement.addEventListener('play',
      //   function(e){
      //     dataLayer.push({
      //         "event": "audio-play",
      //         "eventLabel": podcastLabel
      //     });
      // }, false);
      // mediaElement.addEventListener('ended',
      //   function(e){
      //     dataLayer.push({
      //         "event": "audio-ended",
      //         "eventLabel": podcastLabel
      //     });
      // }, false);
    }
  });