// Sidebar drawer
const appContainer = document.querySelector('.app-container');

const sidebarToggle = document.getElementById('toggleSidebar');
if (sidebarToggle) {
  sidebarToggle.onclick = () => {
    appContainer.classList.toggle('closed-sidebar');
  };
}

// Sidebar menu dropdowns
const menuItems = document.querySelectorAll('.has-submenu');
menuItems.forEach((menuItem) => {
  let submenu = menuItem.querySelector('.menu_level_1');
  let child = menuItem.querySelector('div:first-child');

  child.addEventListener('click', (event) => {
    event.preventDefault();
    submenu.classList.toggle('active');
    menuItem.classList.toggle('active');
  });
});

const mobileNavToggle = document.getElementById('mobileNavToggle');

// Mobile menu toggle
mobileNavToggle.onclick = () => {
  appContainer.classList.toggle('closed-sidebar');
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
document.addEventListener('DOMContentLoaded', function () {
  const currentItems = document.querySelectorAll(
    '.app-sidebar .sidebar-wrapper .current, .app-sidebar .sidebar-wrapper .current_ancestor'
  );

  if (currentItems.length > 0) {
    let currentItem = currentItems[0];
    currentItem.scrollIntoView();
  }
});

// Detect Safari for specific CSS
if (/^((?!chrome|android).)*safari/i.test(navigator.userAgent)) {
  document.querySelector('body').classList.add('sf-helper');
}
