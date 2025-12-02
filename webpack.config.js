const fs = require('fs');
const path = require('path');
const Encore = require('@symfony/webpack-encore');
const getWebpackConfigs = require('@ibexa/frontend-config/webpack-config/get-configs');
const customConfigsPaths = require('./var/encore/ibexa.webpack.custom.config.js');

const customConfigs = getWebpackConfigs(Encore, customConfigsPaths);
const isReactBlockPathCreated = fs.existsSync('./assets/page-builder/react/blocks');

module.exports = [...customConfigs];
