/* globals jwplayer */
import $ from 'jquery';
import 'magnific-popup';
import Swiper from 'swiper/dist/js/swiper';
import 'bootstrap';
import CookieControl from '@netgen/javascript-cookie-control';

global.$ = global.jQuery = $; // eslint-disable-line no-multi-assign
global.Swiper = Swiper;

/* CHECK WHEN ELEMENT IS IN VIEWPORT  -----------------------------------------------*/
(() => {
  function isElementInViewport(element) {
    // special bonus for those using jQuery
    const el = typeof $ === 'function' && element instanceof $ ? element[0] : element;

    const rect = el.getBoundingClientRect();
    return (
      rect.top >= 0
      && rect.left >= 0
      && rect.bottom <= (window.innerHeight || document.documentElement.clientHeight)
      && rect.right <= (window.innerWidth || document.documentElement.clientWidth)
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
      $(window).on('scroll:end', () => {
        if (isElementInViewport($this)) {
          $this.trigger('in_viewport');
          if (cb) cb.call($this);
        }
      }).addClass('in_viewport');
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
    sources = [{
      file: videoObject.data('file'),
      type: videoObject.data('mimetype'),
    }];
  } else {
    sources = [{
      file: videoObject.data('file'),
    }];
  }

  jwplayer(videoId).setup({
    primary: 'flash',
    width,
    aspectratio: aspectRatio,
    autostart: videoObject.data('autostart'),
    controlbar: [{ idlehide: 'true' }],
    playlist: [{
      sources,
      image: videoObject.data('image'),
    }],
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

  /* idangero.us swiper */
  const relatedSwiper = [];
  $('.related-multimedia.swiper-container').each(function (index) {
    const swiperId = `relatedMultimediaSwiper-${index + 1}`;
    const data = $(this).data();
    $(this).attr('id', swiperId);
    relatedSwiper.push(
      new Swiper($(this), {
        navigation: {
          nextEl: `#${swiperId} .swiper-button-next`,
          prevEl: `#${swiperId} .swiper-button-prev`,
        },
        pagination: {
          el: `#${swiperId} .swiper-pagination`,
          type: 'fraction',
        },
        preloadImages: false,
        loop: data.loop,
        effect: data.effect,
        autoplay: data.autoplay ? { delay: data.autoplay * 1000 } : false,
        lazy: {
          loadPrevNext: true,
          loadPrevNextAmount: 1,
          loadOnTransitionStart: true,
        },
        autoHeight: true,
        on: {
          lazyImageReady() {
            this.updateAutoHeight();
          },
        },
      })
    );
  });
  /* /idangero.us swiper */

  /* header actions */
  /* plugin for click outside */
  (function($,c,b){$.map('click dblclick mousemove mousedown mouseup mouseover mouseout change select submit keydown keypress keyup'.split(' '),function(d){a(d)});a('focusin','focus'+b);a('focusout','blur'+b);$.addOutsideEvent=a;function a(g,e){e=e||g+b;var d=$(),h=g+'.'+e+'-special-event';$.event.special[e]={setup:function(){d=d.add(this);if(d.length===1){$(c).bind(h,f)}},teardown:function(){d=d.not(this);if(d.length===0){$(c).unbind(h)}},add:function(i){var j=i.handler;i.handler=function(l,k){l.target=k;j.apply(this,arguments)}}};function f(i){$(d).each(function(){var j=$(this);if(this!==i.target&&!j.has(i.target).length){j.triggerHandler(e,[i.target])}})}}})($,document,'outside'); // eslint-disable-line
  /* /plugin for click outside */

  (() => {
    const page = $('#page');
    const navToggle = $('.mainnav-toggle');
    const searchToggle = $('.searchbox-toggle');
    const searchForm = $('.header-search');
    const searchInput = searchForm.find('input.search-query');
    const pageToggleClass = (e, classToToggle, classToRemove) => {
      e.preventDefault();
      page.toggleClass(classToToggle);
      if (classToRemove) {
        page.removeClass(classToRemove);
      }
    };
    /* toggle mobile menu */
    navToggle.on('click', (e) => {
      pageToggleClass(e, 'mainnav-active');
    });
    /* toggle searchbox */
    searchToggle.on('click', (e) => {
      pageToggleClass(e, 'searchbox-active', 'mainnav-active');
      searchInput.focus();
    });
    searchForm.on('clickoutside', () => {
      page.removeClass('searchbox-active');
    });
    searchInput.on('input', function () {
      if ($(this).val() !== '') {
        searchForm.addClass('filled');
      } else {
        searchForm.removeClass('filled');
      }
    });

    /* toggle mobile sumbmenu */
    const mainNav = $('.main-navigation').find('ul.navbar-nav');
    const submenuTrigContent = $('<i class="submenu-trigger"></i>');
    mainNav.find('.menu_level_1').before(submenuTrigContent).parent('li').attr('data-submenu', 'true');
    mainNav.on('click', 'i.submenu-trigger', function () {
      $(this).parent('li').toggleClass('submenu-active');
    });
  })();

  /* /header actions */

  /* lazy image loading */
  const lazyImageLoad = (image) => {
    if (image.hasAttribute('data-src')) image.setAttribute('src', image.getAttribute('data-src'));
    if (image.hasAttribute('data-srcset')) image.setAttribute('srcset', image.getAttribute('data-srcset'));
    image.onload = () => {
      image.removeAttribute('data-src');
      image.removeAttribute('data-srcset');
    };
  };
  const loadAllLazy = () => {
    [].forEach.call(document.querySelectorAll('img[data-src]'), img => lazyImageLoad(img));
  };
  if ('IntersectionObserver' in window) {
    const lazyImageObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          const lazyImage = entry.target;
          lazyImageLoad(lazyImage);
          lazyImageObserver.unobserve(lazyImage);
        }
      });
    });

    [].forEach.call(document.querySelectorAll('img[data-src]'), (lazyImage) => {
      lazyImageObserver.observe(lazyImage);
    });
  } else {
    loadAllLazy();
  }
  /* /lazy image loading */

  /* get video poster */
  const getVideoPoster = (el, service) => {
    if (el.attr('src')) return;
    const videoID = el.attr('data-id');
    const thumbname = el.attr('data-thumbname');
    let url;
    let getUrl;
    if (service === 'dailymotion') {
      url = `https://api.dailymotion.com/video/${videoID}?fields=${thumbname}`;
      getUrl = obj => obj[thumbname];
    } else if (service === 'vimeo') {
      url = `https://vimeo.com/api/v2/video/${videoID}.json`;
      getUrl = obj => obj[0][thumbname];
    }
    $.ajax({
      type: 'GET',
      url,
      jsonp: 'callback',
      dataType: 'jsonp',
      success: (data) => {
        el.attr('src', getUrl(data));
        el.trigger('poster:loaded');
      },
    });
  };
  $('img.vimeo-poster').each(function () {
    getVideoPoster($(this), 'vimeo');
  });
  $('img.dailymotion-poster').each(function () {
    getVideoPoster($(this), 'dailymotion');
  });
  /* /get video poster */

  Array.prototype.filter.call(document.getElementsByClassName('ajax-collection'), (el) => {
    el.addEventListener('ajax-paging-added', () => {
      $(el).find('img.vimeo-poster').each(function () {
        getVideoPoster($(this), 'vimeo');
      });
      $(el).find('img.dailymotion-poster').each(function () {
        getVideoPoster($(this), 'dailymotion');
      });
      /* load lazy images for results */
      [].forEach.call(el.querySelectorAll('img[data-src]'), img => lazyImageLoad(img));
    }, false);
  });

  $(document).on('poster:loaded', function () {
    const $link = $(this).closest('.js-video-poster');
    $link.attr('href', this.src);
  });

  $('.js-video-poster').each(function () {
    this.href = $('img', this).attr('src');
  });

  const cookieControl = new CookieControl(window.__ngCcConfig); // eslint-disable-line no-underscore-dangle
  cookieControl.init();
});
