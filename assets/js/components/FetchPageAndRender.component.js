/* REQUIRED HTML STRUCTURE FOR IT TO WORK

FETCH PAGE AND RENDER WRAPPER
<div class="js-fetch-page" data-fetch-page-content-id="CONTENT_ID">

  FETCHED PAGE PLACEHOLDER
  <div class="js-fetch-page-placeholder"></div>

  WRAPPED FETCH TRIGGER LINK FOR EZRICHTEXT TEXTS
  <div class="js-fetch-page-trigger-wrapper"><a href=""></a></div>
  OR JUST FETCH TRIGGER LINK
  <a class="js-fetch-page-trigger-wrapper"></a>

  FETCHED PAGE REMOVAL LINK FROM PLACEHOLDER
  <a class="js-fetch-page-remove"></a>

</div>

*/
export default class FetchPageAndRender {
  constructor(element, options) {
    this.element = element;
    this.options = options;

    this.triggerWrapper = document.querySelector(options.triggerWrapper);
    this.pagePlaceholder = document.querySelector(options.pagePlaceholder);
    this.pageRemove = document.querySelector(options.pageRemove);

    this.init();
  }

  init() {
    if (this.triggerWrapper instanceof HTMLAnchorElement) {
      this.triggerWrapper.addEventListener('click', this.handleFetchPage.bind(this));
    } else {
      this.triggerWrapper
        .querySelector('a')
        .addEventListener('click', this.handleFetchPage.bind(this));
    }
  }

  handleFetchPage(event) {
    event.preventDefault();

    const url = `/view-payload/${this.element.dataset.fetchPageContentId}`;

    fetch(url)
      .then((response) => {
        console.log(url, response);
        if (!response.ok) {
          throw new Error();
        }
        return response.text();
      })
      .then(this.handleRenderPage.bind(this))
      .catch((error) => {
        console.error(`Failed to fetch page content: ${error}`);
      });
  }

  handleRenderPage(page) {
    const template = document.createElement('template');
    template.innerHTML = page.trim();

    this.pagePlaceholder.appendChild(template.content);

    this.pageRemove.addEventListener('click', (e) => {
      e.preventDefault();
      this.pagePlaceholder.innerHTML = '';
    });
  }
}
