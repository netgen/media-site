import submit from '../utilities/form/submit';

export default class FormEmbed {
  constructor(form) {
    this.form = form;

    this.init();
  }

  init() {
    this.form.addEventListener('submit', submit.bind(this, 'embed'));
  }
}
