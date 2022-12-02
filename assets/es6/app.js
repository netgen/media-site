// If you use some global variables that aren't defined in the file,
// add this type of comment ("global" or "globals") to the top of your file,
// exchanging variable_name with the global variable
// /* global <variable_name> */
import $ from 'jquery';

// import './ngsite';
import '../sass/style.scss';

import {ngComponents} from './ngsite'

const components = [
    ...ngComponents
]

// todo: cleanup
window.handleSelectedFile = (event) => {
  const input = event.target;

  if (!input.files) {
    return;
  }

  const selectedFileElement = input.parentElement.querySelector('.js-selected-file');

  selectedFileElement.innerHTML = "";

  for (let i = 0; i < input.files.length; i++) {
    selectedFileElement.innerHTML += `${input.files[i].name}<br/>`;
  }
}

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
