export default class ImageUpload {
  constructor(element, options) {
    this.inputElement = element;
    this.removeElement = document.querySelector(options.removeButtonSelector);
    this.filenameElement = document.querySelector(options.imageFilenameSelector);
    this.filenameWrapper = document.querySelector(options.filenameWrapper);
    this.replaceImageBtn = document.querySelector(options.replaceImageBtnSelector);
    this.imagePreview = document.querySelector(options.imagePreviewSelector);
    this.onInit();
  }

  onInit() {
    this.initialFilename = this.filenameElement.innerHTML.trim();
    this.initialImageSrc = this.imagePreview.src;

    this.inputElement.addEventListener('input', this.handleFileChange.bind(this));

    this.replaceImageBtn.addEventListener('click', () => this.inputElement.click());

    this.removeElement.addEventListener('change', this.handleRemoveImageChange.bind(this));
  }

  handleFileChange(e) {
    const that = this;

    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (event) {
          that.imagePreview.setAttribute('src', event.target.result);
        };

        reader.readAsDataURL(input.files[0]);
      }
    }

    if (e.target.value) {
      readURL(e.target);
      this.replaceImageBtn.classList.remove('hidden');
      this.inputElement.classList.add('hidden');
      this.filenameWrapper.classList.remove('hidden');
      if (e.target.value) {
        this.filenameElement.innerHTML = this.inputElement.files[0].name;
      }
      if (this.removeElement.checked) {
        this.removeElement.click();
      }
    }
  }

  handleRemoveImageChange(e) {
    if (!e.target.checked) return;

    if (this.inputElement.value && this.initialFilename) {
      e.target.checked = false;
      this.filenameElement.innerHTML = this.initialFilename;
      this.imagePreview.setAttribute('src', this.initialImageSrc);
    } else {
      this.replaceImageBtn.classList.add('hidden');
      this.inputElement.classList.remove('hidden');
      this.filenameWrapper.classList.add('hidden');
      this.imagePreview.setAttribute('src', '');
    }

    this.inputElement.value = '';
  }
}
