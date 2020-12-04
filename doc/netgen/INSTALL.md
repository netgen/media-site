Netgen Site install instructions
================================

Software requirements
---------------------

* PHP built in server / Apache 2.4+ / Nginx 1.12+
* MySQL 5.7+
* PHP 7.3+ (with `gd`, `imagick`, `curl`, `mysql`, `xsl`, `xml`, `intl` and `mbstring` extensions)
* ImageMagick

Optional dependencies
---------------------

* Varnish 6.0
* Solr 6.5+

Installation instructions
-------------------------

### MySQL database

Use the following MySQL DDL to create a database which will be used for your project:

```mysql
CREATE DATABASE <db_name> CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci;
```

### Create the new project based on this repo

```
composer create-project netgen/media-site
```

### Create project for contribution

If you are a developer wishing to contribute to the `media-site`, do not use the above `composer create-project` command.

Instead, do the following:

```
git clone git@github.com:netgen/media-site.git
cd media-site
```

If you are contributing to the latest version, skip the next step. Otherwise, take care to checkout the branch you wish to contribute to, for example:

```
git checkout 1.9
```

As either way you will not be developing on a tagged (stable) version, you need to modify `composer.json`, please add the following:

```
"minimum-stability": "dev",
"prefer-stable": true,
```

After this, you can run `composer install`, and follow the rest of the instructions.

Near the end of vendor installation procedure, when asked, be sure to specify
the correct database connection for the site.

### Generate frontend assets

Run the following to generate development versions of the assets:

```
yarn install
yarn build:dev
```

or to build production versions of the assets:

```
yarn install
yarn build:prod
```

### Note for eZ Platform official WebPack support

This repo completely replaces the default `webpack.config.js` file coming from eZ Platform with
Netgen Site specific version which is used **only** for frontend of the project. The eZ Systems provided
file is renamed to `webpack.config.ezplatform.js` without changes.

Also, automatic building of eZ Platform Admin UI assets on every `composer install` or `composer update`
has been disabled so there's no need to install `nodejs` or `yarn` on your production servers to build
those assets. Either deploy them via your deployment procedures, or commit the entire `web/assets` folder
to the git repository. You can build the eZ Platform Admin UI assets on demand simply by executing
`composer ezplatform-assets`.

If, however, you wish to bring back building eZ Platform Admin UI assets when running Composer, add the
`web/assets/` folder to `.gitignore` and add the following to `symfony-scripts` in your `composer.json`:

```json
"@php bin/console bazinga:js-translation:dump web/assets --merge-domains",
"yarn install",
"yarn ezplatform"
```

Note that you do NOT need to rename `webpack.config.ezplatform.js` back to its old name since
`yarn ezplatform` takes the new name into account.

More info: https://github.com/ezsystems/ezplatform/pull/392

### Import database schema and demo data

Run the following command to import database schema and demo data (add `--env=prod`
after `bin/console` if running in prod mode):

```
php bin/console ezplatform:install <SITE_NAME>
```

where `<SITE_NAME>` is the name of wanted site, e.g. `netgen-media`,
or `netgen-media-clean` for the clean version, without demo data.

Import the translations to the database with:

```
php bin/console lexik:translations:import AppBundle
```

This is required so Prime Translations Bundle can be used to edit translations through admin interface.
Note that after every change to the translation files, you need to run the above command again.

Finally, generate the GraphQL schema for admin interface:

```
php bin/console ezplatform:graphql:generate-schema
```

### Generate image variations

If using demo content, it can be quite resource intensive to generate all needed image variations
at request time, especially when demo content uses high quality and high resolution images.

To overcome this, you can use the following command to generate most used image variations for all images:

```
php bin/console ngsite:content:generate-image-variations --variations=i30,i160,i320,i480,nglayouts_app_preview,ngcb_thumbnail
```

This command will take a couple of minutes to complete, so grab a cup of coffee while it's running.

In addition to limiting the command on specific image variations, you can also limit it to a subset of
subtrees, content types and content fields. Use the following command to list all available options:

```
php bin/console ngsite:content:generate-image-variations --help
```

### Run PHP built in server / Setup Apache virtual host

For development purposes, you can use PHP built in server to run the site.

Just start with:

```
php bin/console server:run -d web
```

Alternatively, you can create a new Apache virtual host and set it up to point
to `web/` directory inside the repo root.

An example virtual host is available at `doc/apache2/netgen-site-vhost.conf`

If you wish to use rewrite rules located `.htaccess` file instead of putting
them in virtual host configuration, you can use a virtual host variant located
at `doc/apache2/netgen-site.conf`

### Setup folder permissions

You need to setup file and directory permissions so eZ Platform can write to cache,
log and var folders:

```bash
$ setfacl -R -m u:<web-user>:rwX -m g:<web-user>:rwX var web/var
$ setfacl -dR -m u:<web-user>:rwX -m g:<web-user>:rwX var web/var
```

In case `setfacl` is not available on your system, refer to [Symfony installation instructions]
to set up the permissions correctly.

[Symfony installation instructions]: https://symfony.com/doc/3.4/setup/file_permissions.html
