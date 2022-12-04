// webpack.config.default.js
const Encore = require('@symfony/webpack-encore');
const Webpack = require('webpack'); // eslint-disable-line import/no-extraneous-dependencies

const path = require('path');

Encore.reset();

const siteConfig = {
  name: 'default',
  buildLocation: Encore.isProduction() ? 'build' : 'build_dev',
  assetsLocation: 'assets',
};

Encore
  // the project directory where all compiled assets will be stored
  .setOutputPath(`./public/assets/app/${siteConfig.buildLocation}/`)

  // the public path used by the web server to access the previous directory
  .setPublicPath(`/assets/app/${siteConfig.buildLocation}`)

  // will create public/assets/app/build/index.js and public/assets/app/build/index.css
  .addEntry('index', `./${siteConfig.assetsLocation}/js/index.js`)
  .addEntry('index-noncritical', `./${siteConfig.assetsLocation}/js/index-noncritical.js`)

  // allow sass/scss files to be processed
  .enableSassLoader((options) => {
    options.sassOptions.includePaths = [path.resolve(__dirname, 'node_modules')]; // eslint-disable-line no-param-reassign
    options.sassOptions.outputStyle = Encore.isProduction() ? 'compressed' : 'nested';
  })

  // allow legacy applications to use $/jQuery as a global variable
  // .autoProvidejQuery()

  .enableSourceMaps(!Encore.isProduction())

  // empty the outputPath dir before each build
  .cleanupOutputBeforeBuild()

  // create hashed filenames (e.g. app.css?v=abc123)
  .enableVersioning(Encore.isProduction())

  .enablePostCssLoader((options) => {
    options.postcssOptions = {
      config: path.resolve(__dirname, 'postcss.config.js')
    };
  })

  .configureTerserPlugin((options) => {
    options.parallel = true;
    options.terserOptions = {
      output: {
        comments: false,
      },
      compress: {
        drop_console: true,
      },
    };
  })

  .enableSingleRuntimeChunk()

    // enables @babel/preset-env polyfills
  .configureBabelPresetEnv((config) => {
    config.useBuiltIns = 'entry';
    config.corejs = 3;
  })
;

if (Encore.isProduction()) {
  Encore.configureFilenames({
    js: '[name].js?v=[contenthash]',
    css: '[name].css?v=[contenthash]',
  });

  Encore.configureImageRule({
    filename: 'images/[name][ext]?v=[hash:8]'
  });

  Encore.configureFontRule({
    filename: 'fonts/[name][ext]?v=[hash:8]'
  });
}

const config = Encore.getWebpackConfig();

config.watchOptions = { poll: true, ignored: /node_modules/ };
config.name = siteConfig.name;

if (config.devServer) {
  config.devServer.disableHostCheck = true;
}

// export the final configuration
module.exports = config;
