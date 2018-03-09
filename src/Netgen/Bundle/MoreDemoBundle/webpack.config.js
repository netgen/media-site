// webpack.config.js
const Encore = require('@symfony/webpack-encore');

const buildFolder = Encore.isProduction() ? 'build' : 'build_dev';

Encore
  // the project directory where all compiled assets will be stored
  .setOutputPath(`../../../../web/${buildFolder}/`)

  // the public path used by the web server to access the previous directory
  .setPublicPath(`/${buildFolder}`)

  // will create public/build/app.js and public/build/app.css
  .addEntry('app', './Resources/es6/app.js')

  // allow sass/scss files to be processed
  .enableSassLoader()

  // allow legacy applications to use $/jQuery as a global variable
  // .autoProvidejQuery()

  .enableSourceMaps(!Encore.isProduction())

  // empty the outputPath dir before each build
  .cleanupOutputBeforeBuild()

  // show OS notifications when builds finish/fail
  .enableBuildNotifications()

  // create hashed filenames (e.g. app.abc123.css)
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

config.watchOptions = { poll: true, ignored: /node_modules/ };

// export the final configuration
module.exports = config;
