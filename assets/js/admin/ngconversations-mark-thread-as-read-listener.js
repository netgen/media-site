const CONSTANTS = {
  conversationsRootSelector: '#ngconversations-app-root',
  menuItemSelector: '#menu_item_conversations',
  menuItemCountClass: 'menu-ntf-count',
  menuItemIconSelector: '.icon-conversations',
  markAsReadEvent: 'ngconversations-change-thread-read-state',
};

const conversationsRoot = document.querySelector(CONSTANTS.conversationsRootSelector);
const menuItem = document.querySelector(CONSTANTS.menuItemSelector);

if (conversationsRoot !== null && menuItem !== null) {
  conversationsRoot.addEventListener(CONSTANTS.markAsReadEvent, ({ detail }) => {
    let countElement = menuItem.querySelector(`.${CONSTANTS.menuItemCountClass}`);

    let count = Number(countElement?.innerText) || 0;
    count += detail.isRead ? -1 : 1;

    if (count > 0) {
      if (countElement === null) {
        countElement = document.createElement('span');
        countElement.classList.add(CONSTANTS.menuItemCountClass);
        menuItem.insertBefore(countElement, menuItem.querySelector(CONSTANTS.menuItemIconSelector));
      }

      countElement.innerText = count;
    } else {
      countElement?.remove();
    }
  });
}
