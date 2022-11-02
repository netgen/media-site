// If you use some global variables that aren't defined in the file,
// add this type of comment ("global" or "globals") to the top of your file,
// exchanging variable_name with the global variable
// /* global <variable_name> */
import '@babel/polyfill';
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

const componentsInit = () => {
  components.forEach((component) => {
    if (document.querySelector(component.selector) !== null) {
      document.querySelectorAll(component.selector).forEach(
        element => new component.class(element, component.options)
      );
    }
  });
}

$(componentsInit); // initialize on DOM ready
document.addEventListener('ngl:refresh', (e) => { // initialize on special event
  componentsInit();
});
