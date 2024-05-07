// This file contains only import, configuration and initialization of components
import addDocumentEventListeners from '../utilities/components/add-document-event-listeners';

import ModalConfirmControl from '../components/backoffice/ModalConfirmControl.component';
import NgConversationsMarkThreadAsRead from '../components/backoffice/NgConversationsMarkThreadAsRead.component';
import Sidebar from '../components/backoffice/Sidebar.component';

const componentConfiguration = [
  {
    Component: NgConversationsMarkThreadAsRead,
    selector: '#ngconversations-app-root',
    options: {
      menuItemSelector: '#menu_item_conversations',
      menuItemCountClass: 'menu-ntf-count',
      menuItemIconSelector: '.icon-conversations',
      markAsReadEvent: 'ngconversations-change-thread-read-state',
    },
  },
  {
    Component: Sidebar,
    selector: '.app-container',
    options: {
      hasSubmenu: '.has-submenu',
      submenu: '.menu_level_1',
      child: 'div:first-child',
      currentItems:
        '.app-sidebar .sidebar-wrapper .current, .app-sidebar .sidebar-wrapper .current_ancestor',
    },
  },
  {
    Component: ModalConfirmControl,
    selector: '.js-modal-confirm',
  },
];

addDocumentEventListeners(componentConfiguration);