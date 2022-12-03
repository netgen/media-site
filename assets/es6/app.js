// If you use some global variables that aren't defined in the file,
// add this type of comment ("global" or "globals") to the top of your file,
// exchanging variable_name with the global variable
// /* global <variable_name> */

import '../sass/style.scss';

import $ from 'jquery';
import 'magnific-popup';
import 'bootstrap';
import './globals'

import {ngComponents} from './ngsite'

window.$ = $;
window.jQuery = $;

const components = [
    ...ngComponents
]

/* INITIALIZE ALL COMPONENTS -----------------------------------------------*/
const componentsInit = () => {
    components.forEach((component) => {
      if (document.querySelector(component.selector) !== null) {
        document.querySelectorAll(component.selector).forEach(
          element => new component.class(element, component.options)
        );
      }
    });
  }
  /* INITIALIZE ALL COMPONENTS -----------------------------------------------*/

  document.addEventListener('DOMContentLoaded', (e) => {  // initialize on DOM ready
    componentsInit();
  });

  document.addEventListener('ngl:preview:block:refresh', (e) => { // initialize on special event
    componentsInit();
  });
