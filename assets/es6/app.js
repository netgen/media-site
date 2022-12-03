// If you use some global variables that aren't defined in the file,
// add this type of comment ("global" or "globals") to the top of your file,
// exchanging variable_name with the global variable
// /* global <variable_name> */

import '../sass/style.scss';

import $ from 'jquery';
import 'magnific-popup';
import 'bootstrap';
import './globals'

import componentConfiguration from './component.configuration'

window.$ = $;
window.jQuery = $;

const componentsInit = () => {
  componentConfiguration.forEach((configuration) => {
    if (document.querySelector(configuration.selector) !== null) {
      document.querySelectorAll(configuration.selector).forEach(
        element => new configuration.Component(element, configuration.options)
      );
    }
  });
}

document.addEventListener('DOMContentLoaded', () => { componentsInit() });
document.addEventListener('ngl:preview:block:refresh', () => { componentsInit() });
