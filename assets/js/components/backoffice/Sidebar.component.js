export default class Sidebar {
  constructor(element, options) {
    this.element = element;
    this.options = options;

    this.init();
  }

  init() {
    const sidebarToggle = document.getElementById('toggleSidebar');
    if (sidebarToggle) {
      sidebarToggle.onclick = () => {
        this.element.classList.toggle('closed-sidebar');
      };
    }

    // Sidebar menu dropdowns
    const menuItems = document.querySelectorAll(this.options.hasSubmenu);
    menuItems.forEach((menuItem) => {
      const submenu = menuItem.querySelector(this.options.submenu);
      const child = menuItem.querySelector(this.options.child);

      child.addEventListener('click', (event) => {
        event.preventDefault();
        submenu.classList.toggle('active');
        menuItem.classList.toggle('active');
      });
    });

    const mobileNavToggle = document.getElementById('mobileNavToggle');

    // Mobile menu toggle
    mobileNavToggle.onclick = () => {
      this.element.classList.toggle('closed-sidebar');
    };

    // Sidebar account mini menu toggle and closing on outside click
    const myAccountToggle = document.getElementById('myAccountToggle');
    const myAccountMenu = document.getElementById('myAccountMenu');

    if (myAccountToggle && myAccountMenu) {
      myAccountToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        myAccountMenu.classList.toggle('user-settings-menu--visible');
      });

      document.addEventListener('click', (event) => {
        const isClickInsideMenu = myAccountMenu.contains(event.target);
        const isClickOnToggle = event.target === myAccountToggle;

        if (!isClickInsideMenu && !isClickOnToggle) {
          myAccountMenu.classList.remove('user-settings-menu--visible');
        }
      });
    }

    // Scroll sidebar to the selected item after the reload
    const currentItems = document.querySelectorAll(this.options.currentItems);

    if (currentItems.length > 0) {
      const currentItem = currentItems[0];
      currentItem.scrollIntoView();
    }

    // Detect Safari for specific CSS
    if (/^((?!chrome|android).)*safari/i.test(navigator.userAgent)) {
      document.querySelector('body').classList.add('sf-helper');
    }

  }
}
