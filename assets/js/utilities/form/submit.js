import GTM from '../gtm';

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

  if (formContainer.classList.contains('loading')) {
    return;
  }

  formContainer.classList.add('loading');

  const options = { method: 'POST', body: new FormData(this.form) };
  fetch(action, options)
    .then((response) => {
      if (!response.ok) {
        throw new Error('Something went wrong');
      }

      return response.text();
    })
    .then((text) => {
      formContainer.innerHTML = text.trim();
      formContainer.classList.remove('loading');
      formContainer.scrollIntoView({
        behavior: 'smooth',
      });

      if (this.onSuccess !== undefined) {
        this.onSuccess();
      }

      GTM.push(gtmEventPrefix, GTM.EVENTS.SUBMITTED);
    })
    .catch((error) => {
      formContainer.innerHTML = '<p>Something went wrong</p>';
      console.error(`Form type "${formType}" submit failed: ${error}`);
      formContainer.classList.remove('loading');
      formContainer.scrollIntoView({
        behavior: 'smooth',
      });

      if (this.onFailure !== undefined) {
        this.onFailure();
      }

      GTM.push(gtmEventPrefix, GTM.EVENTS.FAILED);
    });
}
