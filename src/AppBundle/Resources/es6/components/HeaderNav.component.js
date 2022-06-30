import $ from 'jquery';

export default class HeaderNav {
    constructor(element, options) {
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

        this.navToggle.addEventListener('click', (e) => {
            this.pageToggleClass(e, this.options.navActiveClass);
        })

        this.searchToggle.addEventListener('click', (e) => {
            this.pageToggleClass(e, this.options.searchboxActiveClass, this.options.navActiveClass);
            this.searchInput.focus();
        });

        this.headerSearch.addEventListener('blur', (e) => {
            this.pageWrapper.classList.remove(this.options.searchboxActiveClass);
        })

        this.headerSearch.addEventListener('click', (e) => {
            if(this.headerSearch.contains(e.target)) return;

            this.pageWrapper.classList.remove(this.options.searchboxActiveClass);
        })

        this.headerSearch.addEventListener('input', (e) => {
            if(this.searchInput.value !== '') {
                this.headerSearch.addClass('filled');
                return;
            } 

            this.headerSearch.classList.remove('filled');    
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
}


// OLD CODE FROM ngsite.js

// const page = $('#page');
// const navToggle = $('.mainnav-toggle');
// const searchToggle = $('.searchbox-toggle');
// const searchForm = $('.header-search');
// const searchInput = searchForm.find('input.search-query');
// const pageToggleClass = (e, classToToggle, classToRemove) => {
//   e.preventDefault();
//   page.toggleClass(classToToggle);
//   if (classToRemove) {
//     page.removeClass(classToRemove);
//   }
// };
// /* toggle mobile menu */
// navToggle.on('click', (e) => {
//   pageToggleClass(e, 'mainnav-active');
// });
// /* toggle searchbox */
// searchToggle.on('click', (e) => {
//   pageToggleClass(e, 'searchbox-active', 'mainnav-active');
//   searchInput.focus();
// });
// searchForm.on('clickoutside', () => {
//   page.removeClass('searchbox-active');
// });
// searchInput.on('input', function () {
//   if ($(this).val() !== '') {
//     searchForm.addClass('filled');
//   } else {
//     searchForm.removeClass('filled');
//   }
// });

// /* toggle mobile submenu */
// const mainNav = $('.main-navigation').find('ul.navbar-nav');
// const submenuTrigContent = $('<i class="submenu-trigger"></i>');
// mainNav
//   .find('.menu_level_1')
//   .before(submenuTrigContent)
//   .parent('li')
//   .attr('data-submenu', 'true');
// mainNav.on('click', 'i.submenu-trigger', function () {
//   $(this).parent('li').toggleClass('submenu-active');
// });