import GTM from '../gtm';

const loader = '<div class="loading-animation"><span></span></div>';

export default function (formType, event) {
  event.preventDefault();

  if (this.form === undefined) {
    console.error(`Form for type "${formType}" is not defined`);

    return;
  }

  const {
    action,
    dataset: { gtmEventPrefix },
    parentElement: formContainer,
  } = this.form;

  formContainer.innerHTML = loader;

  const options = { method: 'POST', body: new FormData(this.form) };
  fetch(action, options)
    .then((response) => response.text())
    .then((text) => {
      formContainer.innerHTML = text.trim();

      if (this.onSuccess !== undefined) {
        this.onSuccess();
      }

      GTM.push(gtmEventPrefix, GTM.EVENTS.SUBMITTED);
    })
    .catch((error) => {
      console.error(`Form type "${formType}" submit failed: ${error}`);

      if (this.onFailure !== undefined) {
        this.onFailure();
      }

      GTM.push(gtmEventPrefix, GTM.EVENTS.FAILED);
    });
}
