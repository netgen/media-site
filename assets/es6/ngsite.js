/* page, dataLayer */
import $ from 'jquery';
import 'magnific-popup';
import Swiper from 'swiper/dist/js/swiper';
import 'bootstrap';
import 'mediaelement';

global.$ = global.jQuery = $; // eslint-disable-line no-multi-assign
global.Swiper = Swiper;

// components
import ResponsiveVideoComponent from './components/ResponsiveVideo.component';
import HeaderNav from './components/HeaderNav.component';
import LazyLoading from './components/LazyLoading.component';
import CookieControlClass from './components/CookieControl.component';
import VideoPoster from './components/VideoPoster.component';
import AjaxCollection from './components/AjaxCollection.component';
import SwiperBase from './components/SwiperBase.component';
import SwiperThumb from './components/SwiperThumb.component';
import GoogleMap from './components/GoogleMap.component';

const components = [
  {
    class: GoogleMap,
    selector: '.nglayouts-map-embed',
  },
  {
    class: ResponsiveVideoComponent,
    selector: '.js-responsive-video',
  },
  {
    class: HeaderNav,
    selector: 'html',
    options: {
      pageWrapper: '#page',
      navToggle: '.mainnav-toggle',
      searchToggle: '.searchbox-toggle',
      headerSearch: '.header-search',
      searchInput: 'input.search-query',
      mainNav: '.main-navigation ul.navbar-nav',
      menuLevel1: '.menu_level_1',
      navActiveClass: 'mainnav-active',
      searchboxActiveClass: 'searchbox-active',
      submenuTriggerElement: 'i',
      submenuTriggerClass: 'submenu-trigger',
      submenuDataParam: 'data-submenu',
      submenuActiveClass: 'submenu-active',
      navigationList: 'ul.nav.navbar-nav',
      filledClass: 'filled'
    }
  },
  {
    class: LazyLoading,
    selector: 'html',
    options: {}
  },
  {
    class: CookieControlClass,
    selector: '#ng-cc',
    options: {
      optionalSaveBtn: '#ng-cc-accept, #ng-cc-optional-save',
      optionalList: '.ng-cc-optional-list',
      optionalListToggle: ".optional-list-toggle",
      rotateArrowClass: "rotate-arrow",
      shownClass: 'shown',
    }
  },
  {
    class: VideoPoster,
    selector: '.video-poster',
    options: {
      vimeoClass: 'vimeo-poster',
      dailymotionClass: 'dailymotion-poster',
      posterLinkElement: '.js-video-poster'
    }
  },
  {
    class: AjaxCollection,
    selector: '.ajax-collection',
    options: {
      posters: 'img.video-poster',
      vimeoClass: 'vimeo-poster',
      dailymotionClass: 'dailymotion-poster',
      posterLinkElement: '.js-video-poster'
    }
  },
  {
    class: SwiperBase,
    selector: '.related-multimedia.swiper-container',
    options: {
      swiperPrevBtn: '.swiper-button-prev',
      swiperNextBtn: '.swiper-button-next',
      swiperPagination: '.swiper-pagination',
    }
  },
  {
    class: SwiperBase,
    selector: '.sushi-swiper',
    options: {
      swiperPrevBtn: '.swiper-button-prev',
      swiperNextBtn: '.swiper-button-next',
      swiperPagination: '.swiper-pagination',
      swiperConfig: {
        loopFillGroupWithBlank: true,
        watchSlidesVisibility: true,
        spaceBetween: 30,
        breakpoints: {
          991: {
            slidesPerView: 2,
          },
          480: {
            slidesPerView: 1,
          },
        }
      }
    }
  },
  {
    class: SwiperBase,
    selector: '.default-swiper',
    options: {
      swiperPrevBtn: '.swiper-button-prev',
      swiperNextBtn: '.swiper-button-next',
      swiperPagination: '.swiper-pagination',
      swiperConfig: {
        preloadImages: false,
        watchSlidesVisibility: true,
        lazy: {
          loadPrevNext: true,
          loadPrevNextAmount: 1,
          loadOnTransitionStart: true,
        },
        keyboard: {
          enabled: true,
        },
        autoHeight: true,
      }
    }
  },
  {
    class: SwiperBase,
    selector: '.quote-swiper',
    options: {
      swiperPrevBtn: '.swiper-button-prev',
      swiperNextBtn: '.swiper-button-next',
      swiperPagination: '.swiper-pagination',
      swiperConfig: {
        preloadImages: false,
        watchSlidesVisibility: true,
        lazy: {
          loadPrevNext: true,
          loadPrevNextAmount: 1,
          loadOnTransitionStart: true,
        },
        keyboard: {
          enabled: true,
        },
      }
    }
  },
  {
    class: SwiperThumb,
    selector: '.thumb-swiper',
    options: {
      swiperPrevBtn: '.swiper-button-prev',
      swiperNextBtn: '.swiper-button-next',
      topSwiper: '.gallery-top',
      topSwiperConfig: {
        spaceBetween: 10,
        preloadImages: false,
        watchSlidesVisibility: true,
        lazy: {
          loadPrevNext: true,
          loadPrevNextAmount: 1,
          loadOnTransitionStart: true,
        },
        autoHeight: true,
      },
      thumbnailsSwiper: '.gallery-thumbs',
      thumbnailsSwiperConfig: {
        spaceBetween: 10,
        centeredSlides: true,
        slidesPerView: 'auto',
        touchRatio: 0.2,
        slideToClickedSlide: true,
      }
    }
  }
]

/* INITIALIZE ALL COMPONENTS -----------------------------------------------*/
const componentsInit = () => {
  components.forEach((component) => {
    if (document.querySelector(component.selector) !== null) {
      document.querySelectorAll(component.selector).forEach(
        element => {
          new component.class(element, component.options)
          console.log(element)
        }
      );
    }
  });
}
/* INITIALIZE ALL COMPONENTS -----------------------------------------------*/

document.addEventListener('DOMContentLoaded', (e) => {  // initialize on DOM ready
  componentsInit();
});

document.addEventListener('ngl:refresh', (e) => { // initialize on special event
  componentsInit();
});
