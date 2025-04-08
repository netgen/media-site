export default function validateForm(form) {
  const fields = form.querySelectorAll('input, select, textarea');

  Array.from(fields).forEach((field) => {
    field.removeAttribute('aria-invalid');
  });

  const invalidField = Array.from(fields).find((field) => !field.checkValidity());

  if (invalidField) {
    invalidField.setAttribute('aria-invalid', 'true');
    invalidField.focus();

    return false;
  }

  return true;
}
