// webpack.config.default.js
const Encore = require('@symfony/webpack-encore');
const Webpack = require('webpack'); // eslint-disable-line import/no-extraneous-dependencies
const UglifyJsPlugin = require('uglifyjs-webpack-plugin');

const path = require('path');

Encore.reset();

const siteConfig = {
  name: 'default',
  buildLocation: Encore.isProduction() ? 'build' : 'build_dev',
  resourcesLocation: 'src/AppBundle/Resources',
};

Encore
  // the project directory where all compiled assets will be stored
  .setOutputPath(`./web/${siteConfig.buildLocation}/`)

  // the public path used by the web server to access the previous directory
  .setPublicPath(`/${siteConfig.buildLocation}`)

  // will create web/build/app.js and web/build/app.css
  .addEntry('app', `./${siteConfig.resourcesLocation}/es6/app.js`)

  // allow sass/scss files to be processed
  .enableSassLoader((options) => {
    options.includePaths = [path.resolve(__dirname, 'node_modules')]; // eslint-disable-line no-param-reassign
  })

  // allow legacy applications to use $/jQuery as a global variable
  // .autoProvidejQuery()

  .enableSourceMaps(!Encore.isProduction())

  // empty the outputPath dir before each build
  .cleanupOutputBeforeBuild()

  // create hashed filenames (e.g. app.css?v=abc123)
  .enableVersioning(Encore.isProduction())

  .enablePostCssLoader((options) => {
    options.config = { // eslint-disable-line no-param-reassign
      path: 'postcss.config.js',
    };
  })
;

if (Encore.isProduction()) {
  Encore.configureFilenames({
    js: '[name].js?v=[chunkhash]',
    css: '[name].css?v=[contenthash]',
    images: 'images/[name].[ext]?v=[hash:8]',
    fonts: 'fonts/[name].[ext]?v=[hash:8]',
  });
}

const config = Encore.getWebpackConfig();

// Remove the old version of uglifyjs plugin which doesn't handle ES6
// We can remove this when Encore upgrades Webpack to 4.0
config.plugins = config.plugins.filter(
  plugin => !(plugin instanceof Webpack.optimize.UglifyJsPlugin)
);

// Add the new version of uglifyjs which is compatible with ES6
// We can remove this when Encore upgrades Webpack to 4.0
if (Encore.isProduction()) {
  config.plugins.push(new UglifyJsPlugin());
}

config.watchOptions = { poll: true, ignored: /node_modules/ };
config.name = siteConfig.name;

// export the final configuration
module.exports = config;
