// If you use some global variables that aren't defined in the file,
// add this type of comment ("global" or "globals") to the top of your file,
// exchanging variable_name with the global variable
// /* global <variable_name> */
import $ from 'jquery';
import ResponsiveVideoComponent from './components/ResponsiveVideo.component';
import './ngsite';
import './blocks/blocks';
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

  $.getScript('../../../../bundles/netgenlayoutsstandard/dev/js/app.js', function () { // getting script from vendors - temoporarely - will need better way
    dispatchEvent(new Event('load')); // triggering load event manually to execute code

    // console.log("Getting script from vendors");
    // "vendor/netgen/layouts-standard/bundle/Resources/es6/app.js" path to vendor source script
  });

});
