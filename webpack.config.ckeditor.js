// webpack.config.ckeditor4.js
const Encore = require('@symfony/webpack-encore');
const Webpack = require('webpack'); // eslint-disable-line import/no-extraneous-dependencies

const path = require('path');

Encore.reset();

const ckeditorConfig = {
  name: 'ckeditor',
  buildLocation: 'ckeditor4',
  resourcesLocation: 'assets',
};
Encore
  // the project directory where all compiled assets will be stored
  .setOutputPath(`./public/assets/ckeditor/`)

  // the public path used by the web server to access the previous directory
  .setPublicPath(`/assets/ckeditor`)

  .addEntry('app', `./${ckeditorConfig.resourcesLocation}/js/ckeditor.js`)

  // empty the outputPath dir before each build
  .cleanupOutputBeforeBuild()

  .copyFiles([
    { from: './assets/ckeditor4/', to: '[path][name].[ext]', pattern: /\.(js|css)$/, includeSubdirectories: false },
    { from: './assets/ckeditor4/adapters', to: 'adapters/[path][name].[ext]' },
    { from: './assets/ckeditor4/lang', to: 'lang/[path][name].[ext]' },
    { from: './assets/ckeditor4/plugins', to: 'plugins/[path][name].[ext]' },
    { from: './assets/ckeditor4/skins', to: 'skins/[path][name].[ext]' },
  ])
  .enableSingleRuntimeChunk()
;

const ckeditor = Encore.getWebpackConfig();

ckeditor.watchOptions = { poll: true, ignored: /node_modules/ };
ckeditor.name = ckeditorConfig.name;

if (ckeditor.devServer) {
  ckeditor.devServer.disableHostCheck = true;
}

module.exports = [ckeditor];
