// If you use some global variables that aren't defined in the file,
// add this type of comment ("global" or "globals") to the top of your file,
// exchanging variable_name with the global variable
// /* global <variable_name> */
import "core-js/stable";
import "regenerator-runtime/runtime";
import $ from 'jquery';
import './ngsite';
import '../sass/style.scss';

import HeaderNav from './components/HeaderNav.component';
import LazyLoading from './components/LazyLoading.component';
import CookieControlClass from './components/CookieControl.component';
import VideoPoster from './components/VideoPoster.component';
import AjaxCollection from './components/AjaxCollection.component';

const components = [
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
      submenuActiveClass: 'submenu-active'
    }
  },
  {
    class: LazyLoading,
    selector: 'html',
    options: {}
  },
  {
    class: CookieControlClass,
    selector: 'html',
    options: {}
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
    options: {}
  }
]

window.addEventListener('DOMContentLoaded', (e) => {
  components.forEach((component) => {
    if (document.querySelector(component.selector) !== null) {
      document.querySelectorAll(component.selector).forEach(
        element => new component.class(element, component.options)
      );
    }
  });
});

