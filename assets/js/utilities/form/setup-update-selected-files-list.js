/**
 * Put input[type=file] element selected file names into a separate container.
 */
function updateSelectedFilesList(event) {
  const input = event.target;

  if (!(input instanceof HTMLInputElement) || input.getAttribute('type') !== 'file') {
    console.error('Given target is not a file input:', input);

    return;
  }

  const container = input.closest('.file-group').querySelector('.js-selected-file-names');

  if (container === null) {
    console.error('Could not find selected file names container from the given target:', input);

    return;
  }

  container.innerHTML = [...input.files].map((file) => file.name).join('<br/>');
  container.classList.add('mt-2');
}

export default function (form) {
  form.querySelectorAll('input[type="file"]').forEach((inputElement) => {
    inputElement.addEventListener('change', updateSelectedFilesList);
  });
}
