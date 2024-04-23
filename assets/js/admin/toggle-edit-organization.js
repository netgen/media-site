const editOrganizationBtn = document.querySelector('.js-edit-organization__btn');
const organizationContent = document.querySelector('.my-country-organization__content');

if (editOrganizationBtn) {
  editOrganizationBtn.addEventListener('click', () => {
    organizationContent.classList.toggle('my-country-organization__content--edit');
  });
}
