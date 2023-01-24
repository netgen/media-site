import submit from '../utilities/form/submit';
import setupUpdateSelectedFilesList from '../utilities/form/setup-update-selected-files-list';

export default class FormEmbed {
  constructor(form) {
    this.form = form;

    this.init();
  }

  init() {
    setupUpdateSelectedFilesList(this.form);
    this.form.addEventListener('submit', submit.bind(this, 'embed'));
  }
}
