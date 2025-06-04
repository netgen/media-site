/* global page */

export default class PageHeader {
  constructor(_, options) {
    this.options = options;

    this.pageWrapper = document.querySelector(options.pageWrapper);
    this.navToggle = document.querySelector(options.navToggle);
    this.searchToggle = document.querySelector(options.searchToggle);
    this.headerSearch = document.querySelector(options.headerSearch);
    this.searchInput = this.headerSearch?.querySelector(options.searchInput) ?? null;
    this.mainNav = document.querySelector(options.mainNav);
    this.level1Menus = [];
    this.submenuTriggerElements = [];
    this.languageSelector = document.querySelector(options.languageSelector);
    this.stickyHeader = document.querySelector(options.stickyHeader);

    this.init();
  }

  init() {
    this.setActiveStateOnMenuItems();
    this.navToggleSetup();
    this.searchToggleSetup();
    this.headerSearchSetup();
    this.addSubmenuTriggers();
    this.languageSelectorSetup();
    this.stickyHeaderSetup();
  }

  navToggleSetup() {
    if (this.navToggle === null) {
      return;
    }

    let scrollTop = 0;

    this.navToggle.addEventListener('click', (event) => {
      event.preventDefault();

      const ariaExpanded = this.navToggle.getAttribute('aria-expanded') === 'true';

      this.navToggle.setAttribute('aria-expanded', ariaExpanded);

      if (!this.pageWrapper.classList.contains(this.options.navActiveClass)) {
        scrollTop = window.scrollY; // set scroll position intro variable
        this.changePageClasses({
          add: this.options.navActiveClass,
          remove: this.options.searchboxActiveClass,
        });
      } else {
        this.changePageClasses({ remove: this.options.navActiveClass });
        window.scrollTo({ top: scrollTop, left: 0, behavior: 'instant' }); // scroll to saved position
      }
    });
  }

  searchToggleSetup() {
    if (this.searchToggle === null) {
      return;
    }

    this.searchToggle.addEventListener('click', (event) => {
      event.preventDefault();

      this.changePageClasses({
        toggle: this.options.searchboxActiveClass,
        remove: this.options.navActiveClass,
      });

      const ariaExpanded = this.searchToggle.getAttribute('aria-expanded') === 'true';
      this.searchToggle.setAttribute('aria-expanded', !ariaExpanded);
      this.searchInput?.focus();
    });
  }

  headerSearchSetup() {
    if (this.headerSearch === null) {
      return;
    }

    this.headerSearch.addEventListener('blur', () => {
      this.changePageClasses({ remove: this.options.searchboxActiveClass });
    });

    this.headerSearch.addEventListener('click', (event) => {
      if (this.headerSearch.contains(event.target)) {
        return;
      }

      this.changePageClasses({ remove: this.options.searchboxActiveClass });
    });

    this.headerSearch.addEventListener('input', () => {
      if (this.searchInput.value !== '') {
        this.headerSearch.classList.add(this.options.filledClass);

        return;
      }

      this.headerSearch.classList.remove(this.options.filledClass);
    });
  }

  addSubmenuTriggers() {
    if (this.mainNav === null) {
      return;
    }

    this.level1Menus = this.mainNav.querySelectorAll(this.options.menuLevel1);
    if (this.level1Menus.length === 0) {
      return;
    }

    this.level1Menus.forEach((menu) => {
      const submenuTriggerContent = document.createElement(this.options.submenuTriggerElement);
      submenuTriggerContent.classList.add(this.options.submenuTriggerClass);

      menu.parentElement.insertBefore(submenuTriggerContent, menu);
      menu.parentElement.dataset[this.options.submenuDataParam] = true;

      this.submenuTriggerElements.push(submenuTriggerContent);
    });

    this.submenuTriggerElements.forEach((submenuTrigger) => {
      submenuTrigger.addEventListener('click', () => {
        this.toggleMobileSubmenu(submenuTrigger);
      });
    });
  }

  toggleMobileSubmenu(submenuTrigger) {
    submenuTrigger.parentElement.classList.toggle(this.options.submenuActiveClass);
  }

  setActiveStateOnMenuItems() {
    if (page.dataset.path === undefined) {
      return;
    }

    const activeItemsList = JSON.parse(page.dataset.path);
    const navigationList = document.querySelectorAll(this.options.navigationList);

    navigationList.forEach((navigation) => {
      activeItemsList.forEach((activeItemId) => {
        const item = navigation.querySelector(`[data-location-id="${activeItemId}"]`);

        if (item !== null) {
          item.classList.add('active', this.options.submenuActiveClass);
        }
      });
    });
  }

  languageSelectorSetup() {
    if (this.languageSelector === null) {
      return;
    }
    this.languageSelector.addEventListener('show.bs.dropdown', () => {
      this.removePageClass(this.options.navActiveClass);
      this.removePageClass(this.options.searchboxActiveClass);
    });
  }

  changePageClasses({ remove = null, add = null, toggle = null }) {
    if (remove !== null) {
      this.removePageClass(remove);
    }

    if (add !== null) {
      this.addPageClass(add);
    }

    if (toggle !== null) {
      this.togglePageClass(toggle);
    }
  }

  removePageClass(classToRemove) {
    this.pageWrapper.classList.remove(classToRemove);
  }

  addPageClass(classToAdd) {
    this.pageWrapper.classList.add(classToAdd);
  }

  togglePageClass(classToToggle) {
    this.pageWrapper.classList.toggle(classToToggle);
  }

  stickyHeaderSetup() {
    if (this.stickyHeader === null) {
      return;
    }
    ['load', 'scroll', 'resize', 'orientationchange'].forEach((eventType) => {
      window.addEventListener(eventType, () => {
        window.scrollY >= 1
          ? this.stickyHeader.classList.add('site-header-sticky--active')
          : this.stickyHeader.classList.remove('site-header-sticky--active');
      });
    });
  }
}
