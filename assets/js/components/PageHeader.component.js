/* global page */

export default class PageHeader {
  constructor(_, options) {
    this.options = options;

    // Page structure
    this.pageWrapper = document.querySelector(options.pageWrapper);
    this.siteHeader = document.querySelector(options.siteHeader);

    // Navigation
    this.mainNav = document.querySelector(options.mainNav);
    this.navigationList = document.querySelectorAll(options.navigationList);
    this.navToggle = document.querySelector(options.navToggle);
    this.languageSelector = this.siteHeader?.querySelector(options.languageSelector) ?? null;

    // Search
    this.searchToggle = document.querySelector(options.searchToggle);
    this.headerSearch = document.querySelector(options.headerSearch);
    this.searchInput = this.headerSearch?.querySelector(options.searchInput) ?? null;

    // Internal state
    this.submenuRefs = [];
    this.submenuTriggerSelector = '.' + options.submenuTriggerClass;

    this.init();
  }

  init() {
    this.navToggleSetup();
    this.searchToggleSetup();
    this.headerSearchSetup();
    this.submenuTriggersSetup();
    this.setActiveStateOnMenuItems(); // Must run AFTER submenuTriggersSetup so [data-submenu] exists
    this.languageSelectorSetup();
    this.headerScrollSetup();
  }

  isMobile() {
    return window.innerWidth < 992; // Has to be in sync with the SCSS variables $collapse-nav and $grid-breakpoints
  }

  navToggleSetup() {
    if (!this.navToggle) return;

    if (this.isMobile() && this.mainNav) {
      this.mainNav.setAttribute('aria-hidden', 'true');
    }

    let savedScrollTop = 0;

    this.navToggle.addEventListener('click', (event) => {
      event.preventDefault();

      const isOpening = !this.pageWrapper.classList.contains(this.options.navActiveClass);

      if (isOpening) {
        savedScrollTop = window.scrollY;
        document.body.style.top = `-${savedScrollTop}px`;
        this.changePageClasses({ add: this.options.navActiveClass, remove: this.options.searchboxActiveClass });
      } else {
        document.body.style.top = '';
        this.changePageClasses({ remove: this.options.navActiveClass });
        window.scrollTo({ top: savedScrollTop, left: 0, behavior: 'instant' });
      }

      this.navToggle.setAttribute('aria-expanded', isOpening);
      this.mainNav?.setAttribute('aria-hidden', !isOpening);
    });
  }

  searchToggleSetup() {
    if (!this.searchToggle) return;

    this.searchToggle.addEventListener('click', (event) => {
      event.preventDefault();
      this.changePageClasses({ toggle: this.options.searchboxActiveClass, remove: this.options.navActiveClass });

      const isExpanded = this.searchToggle.getAttribute('aria-expanded') === 'true';
      this.searchToggle.setAttribute('aria-expanded', !isExpanded);
      this.searchInput?.focus();
    });
  }

  headerSearchSetup() {
    if (!this.headerSearch) return;

    this.headerSearch.addEventListener('blur', () => {
      this.changePageClasses({ remove: this.options.searchboxActiveClass });
    });

    this.headerSearch.addEventListener('input', () => {
      this.headerSearch.classList.toggle(this.options.filledClass, this.searchInput.value !== '');
    });

    document.addEventListener('click', (e) => {
      if (!this.headerSearch.contains(e.target) && !this.searchToggle?.contains(e.target)) {
        this.changePageClasses({ remove: this.options.searchboxActiveClass });
      }
    });
  }

  submenuTriggersSetup() {
    if (this.navigationList.length === 0) return;

    this.navigationList.forEach((navigation) => {
      const submenus = navigation.querySelectorAll(this.options.menuLevel1);
      submenus.forEach((submenu, index) => this.initSubmenu(submenu, index));
    });

    if (this.submenuRefs.length === 0) return;

    // Single delegated click handler for all submenu interactions
    document.addEventListener('click', (e) => this.handleDocumentClick(e));
    window.addEventListener('keyup', (e) => e.key === 'Escape' && this.closeAllSubmenus());
  }

  initSubmenu(submenu, index) {
    const submenuParent = submenu.parentElement;
    if (!submenuParent) return;

    submenuParent.dataset[this.options.submenuDataParam] = true;

    // Check if submenu triggers should be disabled (e.g. in footer where items are listed directly)
    const disableSelector = this.options.disableSubmenuTriggers;
    if (disableSelector && submenuParent.closest(disableSelector)) {
      const disabledTrigger = submenuParent.querySelector(this.submenuTriggerSelector);
      if (disabledTrigger) {
        disabledTrigger.setAttribute('tabindex', '-1');
        disabledTrigger.style.setProperty('pointer-events', 'none');
        disabledTrigger.removeAttribute('aria-haspopup');
        disabledTrigger.removeAttribute('aria-expanded');
        disabledTrigger.removeAttribute('aria-controls');
      }
      submenu.removeAttribute('aria-hidden');
      return;
    }

    // Get or create trigger
    let submenuTrigger = submenuParent.querySelector(this.submenuTriggerSelector);
    if (!submenuTrigger) {
      submenuTrigger = this.createSubmenuTrigger(submenuParent, submenu);
    }

    // Generate submenu ID for ARIA
    if (!submenu.id) {
      const locationId = submenuParent.dataset.locationId;
      submenu.id = locationId ? `submenu-${locationId}` : `submenu-${index}`;
    }
    submenu.setAttribute('aria-hidden', 'true');

    // Ensure ARIA attributes
    submenuTrigger.setAttribute('aria-haspopup', 'menu');
    submenuTrigger.setAttribute('aria-expanded', 'false');
    submenuTrigger.setAttribute('aria-controls', submenu.id);

    this.submenuRefs.push({ submenuParent, submenuTrigger, submenu });
  }

  createSubmenuTrigger(submenuParent, submenu) {
    const trigger = document.createElement(this.options.submenuTriggerElement);
    trigger.classList.add(this.options.submenuTriggerClass);

    const parentLink = submenuParent.querySelector('a');
    if (parentLink) {
      trigger.setAttribute('aria-label', `${parentLink.textContent.trim()} menu`);
    }

    submenuParent.insertBefore(trigger, submenu);
    return trigger;
  }

  handleDocumentClick(e) {
    const clickedTrigger = e.target.closest(this.submenuTriggerSelector);

    if (clickedTrigger) {
      const ref = this.submenuRefs.find(({ submenuTrigger }) => submenuTrigger === clickedTrigger);
      if (ref) {
        this.toggleSubmenu(ref);
      }
      return;
    }

    // Click outside - close submenus on desktop only
    if (this.isMobile()) return;

    this.submenuRefs.forEach((ref) => {
      if (!ref.submenuParent.contains(e.target) && ref.submenuParent.classList.contains(this.options.submenuActiveClass)) {
        this.closeSubmenu(ref);
      }
    });
  }

  toggleSubmenu(ref) {
    const { submenuParent, submenuTrigger, submenu } = ref;

    this.setSubmenuMaxHeight(submenu);
    const isActive = submenuParent.classList.toggle(this.options.submenuActiveClass);

    // Close other submenus when opening one
    if (isActive) {
      this.submenuRefs.forEach((other) => {
        if (other !== ref && other.submenuParent.classList.contains(this.options.submenuActiveClass)) {
          this.closeSubmenu(other);
        }
      });
    }

    submenuTrigger.setAttribute('aria-expanded', isActive);
    submenu.setAttribute('aria-hidden', !isActive);
  }

  closeSubmenu({ submenuParent, submenuTrigger, submenu }) {
    submenuParent.classList.remove(this.options.submenuActiveClass);
    submenuTrigger.setAttribute('aria-expanded', 'false');
    submenu.setAttribute('aria-hidden', 'true');
  }

  closeAllSubmenus() {
    this.submenuRefs.forEach((ref) => {
      if (ref.submenuParent.classList.contains(this.options.submenuActiveClass)) {
        this.closeSubmenu(ref);
      }
    });
  }

  setSubmenuMaxHeight(submenu) {
    submenu.style.setProperty('--max-height', `${submenu.scrollHeight}px`);
  }

  setActiveStateOnMenuItems() {
    if (!page.dataset.path) return;

    const activeItemIds = JSON.parse(page.dataset.path);

    this.navigationList.forEach((navigation) => {
      activeItemIds.forEach((id) => {
        const item = navigation.querySelector(`[data-location-id="${id}"]`);
        if (!item) return;

        item.classList.add('active');

        // Pre-expand parent submenu on mobile only
        if (!this.isMobile()) return;

        const parentWithSubmenu = item.parentElement?.closest('li[data-submenu]');
        const ref = this.submenuRefs.find((r) => r.submenuParent === parentWithSubmenu);
        if (ref) {
          ref.submenuParent.classList.add(this.options.submenuActiveClass);
          ref.submenuTrigger.setAttribute('aria-expanded', 'true');
          ref.submenu.setAttribute('aria-hidden', 'false');
          this.setSubmenuMaxHeight(ref.submenu);
        }
      });
    });
  }

  languageSelectorSetup() {
    if (!this.languageSelector) return;

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

  headerScrollSetup() {
    if (!this.siteHeader) return;

    const updateScrollState = () => {
      this.siteHeader.classList.toggle('scrolled', window.scrollY >= 1);
    };

    ['load', 'scroll', 'resize', 'orientationchange'].forEach((event) => {
      window.addEventListener(event, updateScrollState);
    });
  }
}
