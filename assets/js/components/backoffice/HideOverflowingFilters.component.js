const SEARCH_ELEMENT_MARGIN_RIGHT = 16;

export default class HideOverflowingFilters {
  constructor(element, options) {
    this.filterWrapper = element;
    this.options = options;
    this.onInit();
  }

  onInit() {
    this.filtersModal = this.filterWrapper.parentElement.querySelector('.dropdown-menu');
    this.filters = this.filterWrapper.querySelector(this.options.filterWrapSelector);
    this.movedElements = [];
    this.mostRightElement = [...this.filterWrapper.querySelector(':scope > div').children]
      .reverse()
      .find((element) => window.getComputedStyle(element).display !== 'none');
    this.handleWindowResize();

    window.addEventListener('resize', this.handleWindowResize.bind(this));
  }

  handleWindowResize() {
    const wrapperRect = this.filterWrapper.getBoundingClientRect();
    const mostRightElementRect = this.mostRightElement.getBoundingClientRect();

    if (mostRightElementRect.right > wrapperRect.right || window.innerWidth < 576) {
      // Move the last child of filter-wrapper to filters-modal and store window width at which it should be restored
      const lastChild = this.filters.lastElementChild;
      if (lastChild && this.filtersModal) {
        this.movedElements.unshift({
          element: lastChild,
          width: wrapperRect.right + SEARCH_ELEMENT_MARGIN_RIGHT,
        });
        this.filtersModal.prepend(lastChild);
        this.filterWrapper.classList.add('more-filters');
      }

      requestAnimationFrame(this.handleWindowResize.bind(this));
    } else if (this.movedElements.length && window.innerWidth > this.movedElements[0].width) {
      this.filters.append(this.movedElements[0].element);
      this.movedElements.shift();
      if (!this.movedElements.length) {
        this.filterWrapper.classList.remove('more-filters');
      }
    }
  }
}
