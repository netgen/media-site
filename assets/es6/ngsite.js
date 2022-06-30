/* global jwplayer, page, dataLayer */
import $ from 'jquery';
import 'magnific-popup';
import Swiper from 'swiper/dist/js/swiper';
import 'bootstrap';

global.$ = global.jQuery = $; // eslint-disable-line no-multi-assign
global.Swiper = Swiper;

/* CHECK WHEN ELEMENT IS IN VIEWPORT  -----------------------------------------------*/
(() => {
  function isElementInViewport(element) {
    // special bonus for those using jQuery
    const el = typeof $ === 'function' && element instanceof $ ? element[0] : element;

    const rect = el.getBoundingClientRect();
    return (
      rect.top >= 0 &&
      rect.left >= 0 &&
      rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
      rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
  }

  window.isElementInViewport = isElementInViewport;

  let to;
  $(window).on('scroll', () => {
    if (to) clearTimeout(to);
    to = setTimeout(() => {
      $(window).trigger('scroll:end');
    }, 200);
  });

  $.fn.in_viewport = function (cb) {
    return $(this).each(function () {
      const $this = $(this);

      if ($this.hasClass('in_viewport')) return;
      $(window)
        .on('scroll:end', () => {
          if (isElementInViewport($this)) {
            $this.trigger('in_viewport');
            if (cb) cb.call($this);
          }
        })
        .addClass('in_viewport');
    });
  };
})();
/* /CHECK WHEN ELEMENT IS IN VIEWPORT  -----------------------------------------------*/

/* JWPLAYER INIT  -----------------------------------------------*/

function jwplayerInit(videoObjectClass, videoObj) {
  const videoObject = videoObj === false ? $(videoObjectClass) : videoObj;
  if (!videoObject.length) return;

  let sources = false;
  const videoId = videoObject.data('video_player_id');
  const aspectRatio = '16:9';
  const width = '100%';

  if (videoObject.data('videotype') === 'local') {
    sources = [
      {
        file: videoObject.data('file'),
        type: videoObject.data('mimetype'),
      },
    ];
  } else {
    sources = [
      {
        file: videoObject.data('file'),
      },
    ];
  }

  jwplayer(videoId).setup({
    primary: 'flash',
    width,
    aspectratio: aspectRatio,
    autostart: videoObject.data('autostart'),
    controlbar: [{ idlehide: 'true' }],
    playlist: [
      {
        sources,
        image: videoObject.data('image'),
      },
    ],
  });
}
/* /JWPLAYER INIT  -----------------------------------------------*/

$(document).ready(() => {
  const $loginform = $('form[name="loginform"]');
  $loginform.attr('action', $loginform.attr('action') + window.location.hash);

  /* JWPLAYER GLOBAL INITIALIZATION -----------------------------------------------*/
  $('div.video-container').each(function () {
    const videoObjectClass = $(this).attr('id');
    if ($(this).prev().hasClass('video-config') && $(this).prev().hasClass(videoObjectClass)) {
      jwplayerInit(`.${videoObjectClass}`, false);
    } else {
      $(this).remove();
    }
  });
  /* /JWPLAYER GLOBAL INITIALIZATION -----------------------------------------------*/


  /* header actions */
  /* plugin for click outside */
  // prettier-ignore
  (function($,c,b){$.map('click dblclick mousemove mousedown mouseup mouseover mouseout change select submit keydown keypress keyup'.split(' '),function(d){a(d)});a('focusin','focus'+b);a('focusout','blur'+b);$.addOutsideEvent=a;function a(g,e){e=e||g+b;var d=$(),h=g+'.'+e+'-special-event';$.event.special[e]={setup:function(){d=d.add(this);if(d.length===1){$(c).bind(h,f)}},teardown:function(){d=d.not(this);if(d.length===0){$(c).unbind(h)}},add:function(i){var j=i.handler;i.handler=function(l,k){l.target=k;j.apply(this,arguments)}}};function f(i){$(d).each(function(){var j=$(this);if(this!==i.target&&!j.has(i.target).length){j.triggerHandler(e,[i.target])}})}}})($,document,'outside'); // eslint-disable-line
  /* /plugin for click outside */

});
