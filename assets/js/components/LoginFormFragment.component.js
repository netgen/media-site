export default class LoginFormFragment {
  constructor(form) {
    this.form = form;

    this.init();
  }

  init() {
    this.form.action += window.location.hash;
  }
}
