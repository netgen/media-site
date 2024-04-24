export default class NgConversationsMarkThreadAsRead {
  constructor(element, options) {
    this.element = element;
    this.options = options;

    this.init();
  }

  init() {
    const menuItem = document.querySelector(this.options.menuItemSelector);

    this.element.addEventListener(this.options.markAsReadEvent, ({ detail }) => {
      let countElement = menuItem.querySelector(`.${this.options.menuItemCountClass}`);

      let count = Number(countElement?.innerText) || 0;
      count += detail.isRead ? -1 : 1;

      if (count > 0) {
        if (countElement === null) {
          countElement = document.createElement('span');
          countElement.classList.add(this.options.menuItemCountClass);
          const menuItemIcon = menuItem.querySelector(this.options.menuItemIconSelector);
          menuItemIcon.parentNode.insertBefore(countElement, menuItemIcon);
        }

        countElement.innerText = count;
      } else {
        countElement?.remove();
      }
    });
  }
}
