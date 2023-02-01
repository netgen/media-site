Netgen Site frontend setup
==========================

Authors
-------

* Goran Martinjak
* Marko Žabčić

Prerequisites
-------------

* Node JS
* Yarn

Usage
-----

In root directory:

1. `yarn install` to install Node JS packages used by Webpack and the project defined in `package.json`
2. `yarn build:dev` to generate frontend assets used in dev environment (CSS, JavaScript, images...)


Using Yarn
----------

For packages used on frontend:

```shell
$ yarn add jquery --save
```

For packages used by Webpack:

```shell
$ yarn add autoprefixer --save-dev
```

After adding files, use them as Node JS modules by requiring them in your
`.js` file in `js` folder, e.g.:

```javascript
require('swiper');
```

or:

```javascript
import $ from 'jquery';
```

Read more about [Yarn](https://yarnpkg.com)

Using Webpack Encore
--------------------

Start Webpack Encore with `yarn run encore`.

To build dev assets use `yarn build:dev` or `yarn encore dev`.
To build minified production assets use `yarn build:prod` or `yarn encore production`.
To build and watch `.sass` and `.js` files for changes use `yarn watch` or `yarn encore dev --watch`.
To start webpack dev server use `yarn server` or `yarn encore dev-server`.

What Webpack Encore does
------------------------

1. Watches `assets/sass` and `assets/js` directories if started with `--watch`
2. Compiles Sass and JavaScript files
3. Copies all assets (images, fonts...) referenced in CSS to `public/assets/app/build` folder (or `public/assets/app/build_dev` in development environment)
4. Adds vendor prefixes to css (`-moz`, `-webkit` ...)
5. Compiles `.js` files to supported syntax and transpiles required node modules to `index.js`
6. Adds hashes to `manifest.json` for cache busting if building production assets

Conventions
-----------

Main scss file is `sass/style.scss`

Resources
---------

* [Yarn](https://yarnpkg.com)
* [Webpack Encore](https://symfony.com/doc/current/frontend.html)
