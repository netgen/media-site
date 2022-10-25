// If you use some global variables that aren't defined in the file,
// add this type of comment ("global" or "globals") to the top of your file,
// exchanging variable_name with the global variable
// /* global <variable_name> */
import "core-js/stable";
import "regenerator-runtime/runtime";
import $ from 'jquery';
import ResponsiveVideoComponent from './components/ResponsiveVideo.component';
import './ngsite';
import '../sass/style.scss';

const components = [
  {
    class: ResponsiveVideoComponent,
    selector: '.js-responsive-video',
  },
];

$(() => {

  components.forEach((component) => {
    if (document.querySelector(component.selector) !== null) {
      document.querySelectorAll(component.selector).forEach(
        element => new component.class(element, component.options)
      );
    }
  });

  // Put your code inside ./components
});
