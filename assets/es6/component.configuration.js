import AjaxCollection from './components/AjaxCollection.component';
import CookieControlClass from './components/CookieControl.component';
import FormEmbed from "./components/FormEmbed.component";
import FormModal from "./components/FormModal.component";
import GalleryBlock from './components/GalleryBlock.component';
import GoogleMap from './components/GoogleMap.component';
import HeaderNav from './components/HeaderNav.component';
import LoginFormFragment from './components/LoginFormFragment.component';
import ResponsiveVideoComponent from './components/ResponsiveVideo.component';
import SwiperBase from './components/SwiperBase.component';
import SwiperThumb from './components/SwiperThumb.component';
import VideoPoster from './components/VideoPoster.component';

export default [
  {
    Component: AjaxCollection,
    selector: '.ajax-collection',
    options: {
      posters: 'img.video-poster',
      vimeoClass: 'vimeo-poster',
      dailymotionClass: 'dailymotion-poster',
      posterLinkElement: '.js-video-poster'
    }
  },
  {
    Component: CookieControlClass,
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
    Component: FormModal,
    selector: '.js-form-modal-trigger',
  },
  {
    Component: FormEmbed,
    selector: '.js-form-embed',
  },
  {
    Component: GalleryBlock,
    selector: '.ngl-vt-grid_gallery',
    options: {
      layoutAsFlexElements: '.nglayouts-as-flex',
      lightboxEnabledElements: '.js-lightbox-enabled',
      popupToggle: '.js-mfp-item',
    }
  },
  {
    Component: GoogleMap,
    selector: '.nglayouts-map-embed',
  },
  {
    Component: HeaderNav,
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
    Component: LoginFormFragment,
    selector: 'form[name="loginform"]',
    options: {}
  },
  {
    Component: ResponsiveVideoComponent,
    selector: '.js-responsive-video',
  },
  {
    Component: SwiperBase,
    selector: '.related-multimedia.swiper-container',
    options: {
      swiperPrevBtn: '.swiper-button-prev',
      swiperNextBtn: '.swiper-button-next',
      swiperPagination: '.swiper-pagination',
    }
  },
  {
    Component: SwiperBase,
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
    Component: SwiperBase,
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
    Component: SwiperBase,
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
    Component: SwiperThumb,
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
  },
  {
    Component: VideoPoster,
    selector: '.video-poster',
    options: {
      vimeoClass: 'vimeo-poster',
      dailymotionClass: 'dailymotion-poster',
      posterLinkElement: '.js-video-poster'
    }
  },
]
