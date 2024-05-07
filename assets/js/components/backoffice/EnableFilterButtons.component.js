export default class EnableFilterButtons {
  constructor(element, options) {
    this.form = element;
    this.options = options;
    this.onInit();
  }

  onInit() {
    this.searchParams = new URLSearchParams(window.location.search);
    this.inputs = this.form.querySelectorAll('input, select');

    if ([...this.searchParams.keys()].some((query) => query !== 'page')) {
      this.form.classList.add(this.options.hasActiveFilterClass);
    }

    this.form.addEventListener('input', this.checkInputs.bind(this));

    this.checkInputs();
  }

  checkInputs() {
    const action = [...this.inputs].some(
      ({ value, name }) => value && value !== this.searchParams.get(name)
    )
      ? 'add'
      : 'remove';

    this.form.classList[action](this.options.hasNewInputClass);
  }
}
