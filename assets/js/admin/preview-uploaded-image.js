const inputElement = document.getElementById(
  'ezplatform_content_forms_content_edit_fieldsData_image_value_file'
);
const imageElement = document.getElementById('img-preview');
const removeImageButton = document.getElementById('btn-remove-img');
const removeImageCheckbox = document.getElementsByClassName('js-remove-image')[0];

function updateSource() {
  const file = this.files[0];
  if (file !== undefined) {
    imageElement.src = URL.createObjectURL(file);
  } else {
    imageElement.src = '';
  }
}

function removeImage() {
  removeImageCheckbox.checked = true;
  inputElement.value = '';
  imageElement.src = '';
}

if (inputElement) {
  inputElement.addEventListener('change', updateSource, false);
}

if (removeImageButton) {
  removeImageButton.addEventListener('click', removeImage, false);
}
