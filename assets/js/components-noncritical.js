// This file contains only import, configuration and initialization of components
import addDocumentEventListeners from './utilities/components/add-document-event-listeners';

// Import
import AjaxCollection from './components/AjaxCollection.component';
import CookieControl from './components/CookieControl.component';
import FormEmbed from './components/FormEmbed.component';
import VideoModal from './components/VideoModal.component';
import FormModal from './components/FormModal.component';
import GalleryBlock from './components/GalleryBlock.component';
import GoogleMap from './components/GoogleMap.component';
import PageHeader from './components/PageHeader.component';
import LoginFormFragment from './components/LoginFormFragment.component';
import ResponsiveVideo from './components/ResponsiveVideo.component';
import SwiperBase from './components/SwiperBase.component';
import SwiperThumb from './components/SwiperThumb.component';
import VideoPoster from './components/VideoPoster.component';
import SkipToMainContent from './components/SkipToMainContent.component';

// Configuration
const componentConfiguration = [
  {
    Component: AjaxCollection,
    selector: '.ajax-collection',
    options: {
      posters: 'img.video-poster',
      vimeoClass: 'vimeo-poster',
      dailymotionClass: 'dailymotion-poster',
      posterLinkElement: '.js-video-poster',
    },
  },
  {
    Component: CookieControl,
    selector: '#ng-cc',
    options: {
      optionalSaveBtn: '#ng-cc-accept, #ng-cc-optional-save',
      optionalList: '.ng-cc-optional-list',
      optionalListToggle: '.optional-list-toggle',
      rotateArrowClass: 'rotate-arrow',
      shownClass: 'shown',
    },
  },
  {
    Component: VideoModal,
    selector: '.js-modal-video-trigger',
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
  },
  {
    Component: GoogleMap,
    selector: '.nglayouts-map-embed',
  },
  {
    Component: PageHeader,
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
      submenuDataParam: 'submenu',
      submenuActiveClass: 'submenu-active',
      navigationList: 'ul.nav.navbar-nav',
      filledClass: 'filled',
      languageSelector: '.site-header .language-selector',
    },
  },
  {
    Component: LoginFormFragment,
    selector: 'form[name="loginform"]',
  },
  {
    Component: ResponsiveVideo,
    selector: '.js-responsive-video',
  },
  {
    Component: SwiperBase,
    selector: '.related-multimedia.swiper-container',
    options: {
      swiperPreviousButton: '.swiper-button-prev',
      swiperNextButton: '.swiper-button-next',
      swiperPagination: '.swiper-pagination',
    },
  },
  {
    Component: SwiperBase,
    selector: '.sushi-swiper',
    options: {
      swiperPreviousButton: '.swiper-button-prev',
      swiperNextButton: '.swiper-button-next',
      swiperPagination: '.swiper-pagination',
      swiperPaginationType: 'bullets',
      swiperConfig: {
        loopFillGroupWithBlank: true,
        watchSlidesVisibility: true,
        spaceBetween: 30,
        breakpoints: {
          767: {
            slidesPerView: 1.2,
          },
          1199: {
            slidesPerView: 2.2,
          },
        },
      },
    },
  },
  {
    Component: SwiperBase,
    selector: '.default-swiper',
    options: {
      swiperPreviousButton: '.swiper-button-prev',
      swiperNextButton: '.swiper-button-next',
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
        breakpoints: {
          1199: {
            slidesPerView: 1.2,
            spaceBetween: 30,
          },
        },
      },
    },
  },
  {
    Component: SwiperBase,
    selector: '.quote-swiper',
    options: {
      swiperPreviousButton: '.swiper-button-prev',
      swiperNextButton: '.swiper-button-next',
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
      },
    },
  },
  {
    Component: SwiperThumb,
    selector: '.thumb-swiper',
    options: {
      swiperPreviousButton: '.swiper-button-prev',
      swiperNextButton: '.swiper-button-next',
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
        slidesPerView: '4',
        touchRatio: 0.2,
        slideToClickedSlide: true,
      },
    },
  },
  {
    Component: VideoPoster,
    selector: '.video-poster',
    options: {
      vimeoClass: 'vimeo-poster',
      dailymotionClass: 'dailymotion-poster',
      posterLinkElement: '.js-video-poster',
    },
  },
  {
    Component: SkipToMainContent,
    selector: '#skip-to-main-content',
  },
];

addDocumentEventListeners(componentConfiguration);
