/**
 * Note: this file contains functions that must be global.
 * If it's anything else - it does not belong here.
 */

/**
 * Put input[type=file] selected file names into a separate container.
 * Used from element's "onchange" attribute.
 */
window.updateSelectedFileNames = (event) => {
  const input = event.target;

  if (!(input instanceof HTMLInputElement) || input.getAttribute('type') !== 'file') {
    console.error('Given target is not a file input:', input);

    return;
  }

  const container = input.parentElement.querySelector('.js-selected-file-names');

  if (container === null) {
    console.error('Could not find selected file names container from the given target:', input);

    return;
  }

  container.innerHTML = [...input.files].map((elem)=> elem.name).join('<br/>');
}
