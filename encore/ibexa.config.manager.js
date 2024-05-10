const path = require('path');

module.exports = (ibexaConfig, ibexaConfigManager) => {
  ibexaConfigManager.add({
    ibexaConfig,
    entryName: 'ibexa-admin-ui-layout-css',
    newItems: [path.resolve(__dirname, '../assets/sass/ibexa/log_viewer.scss')],
  });
};
