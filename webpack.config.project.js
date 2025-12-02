// webpack.config.js
const config = require('./webpack.config.default');

// for multiple webpack configurations, create another webpack config file and require it here
// const anotherConfig = require('./webpack.config.anotherConfig');

// export the final configuration
// for multiple webpack configurations, add imported configuration to export array eg. module.exports = [config, anotherConfig];
module.exports = [config];
