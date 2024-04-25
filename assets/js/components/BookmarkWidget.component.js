export default class BookmarkWidget {
  constructor(element, options) {
    this.element = element;
    this.bookmarkIcon = this.element.querySelector(options.bookmarkIcon);
    this.bookmarkTooltip = this.element.querySelector(options.bookmarkTooltip);
    this.createBookmarkEndpoint = element.dataset.createBookmarkEndpoint;
    this.deleteBookmarkEndpoint = element.dataset.deleteBookmarkEndpoint;
    this.isBookmarked = element.dataset.isBookmarked === 'true';

    this.onInit();
  }

  onInit() {
    console.log('bookmark component');

    this.element.addEventListener('click', this.updateBookmarkData.bind(this));
  }

  updateBookmarkData() {
    this.bookmarkTooltip.classList.remove('tooltip-animated');

    if (this.isBookmarked) {
      this.bookmarkTooltip.innerHTML = 'Bookmark removed';
    } else {
      this.bookmarkTooltip.innerHTML = 'Bookmarked';
    }

    return fetch(this.isBookmarked ? this.deleteBookmarkEndpoint : this.createBookmarkEndpoint, {
      method: 'POST',
    }).then((response) => {
      ['far', 'fas'].forEach((faTypeClass) => this.bookmarkIcon.classList.toggle(faTypeClass));

      this.bookmarkTooltip.classList.add('tooltip-animated');

      this.isBookmarked = !this.isBookmarked;

      return response;
    });
  }
}
