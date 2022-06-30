// If you use some global variables that aren't defined in the file,
// add this type of comment ("global" or "globals") to the top of your file,
// exchanging variable_name with the global variable
// /* global <variable_name> */
import '@babel/polyfill';
import $ from 'jquery';
import './ngsite';
import '../sass/style.scss';
import LazyLoading from './components/LazyLoading.component';

const components = [
  {
    class: LazyLoading,
    options: {
      triggerElement: 'img'
    },
    selector: 'html'
  },
];

window.addEventListener('DOMContentLoaded', (e) => {
  components.forEach((component) => {
    if (document.querySelector(component.selector) !== null) {
      document.querySelectorAll(component.selector).forEach(
        element => new component.class(element, component.options)
      );
    }
  });
});

$(() => {
  // Put your code here
});
