export default class LoginFormFragment {
    constructor(form, options) {
        this.form = form;
        this.options = options;

        this.init();
    }

    init() {
        this.form.setAttribute('action', this.form.getAttribute('action') + window.location.hash);
    }
}
