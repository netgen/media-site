export default class HeaderNav {
    constructor(element, options) {
        this.el = element;
        this.options = options;

        this.pageWrapper = document.querySelector(options.pageWrapper);
        this.navToggle = document.querySelector(options.navToggle);
        this.searchToggle = document.querySelector(options.searchToggle);
        this.headerSearch = document.querySelector(options.headerSearch);
        this.searchInput = this.headerSearch.querySelector(options.searchInput);
        this.mainNav = document.querySelector(options.mainNav);
        this.level_1_menus = false;
        this.submenuTriggerElements = [];

        this.onInit();
    }

    onInit() {
        const self = this;

        this.setActiveStateOnMenuItems();

        this.navToggle && this.navToggle.addEventListener('click', (e) => {
            this.pageToggleClass(e, this.options.navActiveClass);
        })

        this.searchToggle && this.searchToggle.addEventListener('click', (e) => {
            this.pageToggleClass(e, this.options.searchboxActiveClass, this.options.navActiveClass);
            this.searchInput.focus();
        });

        this.headerSearch && this.headerSearch.addEventListener('blur', (e) => {
            this.pageWrapper.classList.remove(this.options.searchboxActiveClass);
        })

        this.headerSearch && this.headerSearch.addEventListener('click', (e) => {
            if(this.headerSearch.contains(e.target)) return;

            this.pageWrapper.classList.remove(this.options.searchboxActiveClass);
        })

        this.headerSearch && this.headerSearch.addEventListener('input', (e) => {
            if(this.searchInput.value !== '') {
                this.headerSearch.classList.add(this.options.filledClass);
                return;
            }

            this.headerSearch.classList.remove(this.options.filledClass);
        })

        this.addSubmenuTriggers();
    }

    pageToggleClass(e, classToToggle, classToRemove) {
        e.preventDefault();

        this.pageWrapper.classList.toggle(classToToggle);

        if (classToRemove) {
          this.pageWrapper.classList.remove(classToRemove);
        }
    };

    addSubmenuTriggers() {
        if(!this.mainNav) return;
        this.level_1_menus = this.mainNav.querySelectorAll(this.options.menuLevel1);

        if(this.level_1_menus) {
            this.level_1_menus.forEach(menu => {
                const submenuTriggerContent = document.createElement(this.options.submenuTriggerElement)
                submenuTriggerContent.classList.add(this.options.submenuTriggerClass)

                menu.parentElement.insertBefore(submenuTriggerContent, menu)
                menu.parentElement.setAttribute(this.options.submenuDataParam, 'true')

                this.submenuTriggerElements.push(submenuTriggerContent)
            })


            this.submenuTriggerElements.forEach(item => {
                item.addEventListener('click', (e) => {
                    this.toggleMobileSubmenu(item);
                })
            })
        }
    }

    toggleMobileSubmenu(el) {
        el.parentElement.classList.toggle(this.options.submenuActiveClass)
    }

    setActiveStateOnMenuItems() {
        if (page.dataset.path) {
            const activeItemsList = JSON.parse(page.dataset.path);
            const navigationList = document.querySelectorAll(this.options.navigationList);

            navigationList.forEach((navigation) => {
                activeItemsList.forEach((activeItemId) => {
                    const item = navigation.querySelector(`[data-location-id="${activeItemId}"]`);

                    if (item) {
                        item.classList.add('active', this.options.submenuActiveClass);
                    }
                });
            });
        }
    }
}
