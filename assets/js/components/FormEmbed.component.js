import submit from '../utilities/form/submit';
import validateForm from '../utilities/form/validate';
import setupUpdateSelectedFilesList from '../utilities/form/setup-update-selected-files-list';

export default class FormEmbed {
  constructor(form) {
    this.form = form;
    this.init();
  }

  init() {
    setupUpdateSelectedFilesList(this.form);
    this.form.addEventListener('submit', this.handleSubmit.bind(this));
  }

  handleSubmit(e) {
    e.preventDefault();

    if (validateForm(this.form)) {
      submit.bind(this, 'embed', e)();
    }
  }
}
