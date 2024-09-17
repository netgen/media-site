Netgen Site install instructions
================================

Software requirements
---------------------

* PHP built in server / Symfony CLI / Apache 2.4+ / Nginx 1.18+
* MySQL 8.0+
* PHP 8.1+ (with `gd`, `imagick`, `redis`, `curl`, `json`, `mysql`, `xsl`, `xml`, `intl` and `mbstring` extensions)

Optional dependencies
---------------------

* Varnish 6.0 or 7.1+ with [`varnish-modules`](https://github.com/varnish/varnish-modules/blob/master/README.md)
* Solr 7.7 or 8.11.1+

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

If you are a developer wishing to contribute to `netgen/media-site`, do not use the above `composer create-project` command.

Instead, do the following:

```
git clone git@github.com:netgen/media-site.git
cd media-site
```

If you are contributing to the latest version, skip the next step. Otherwise, take care to checkout the branch you wish to contribute to, for example:

```
git checkout 1.12
```

As either way you will not be developing on a tagged (stable) version, you need to modify `composer.json` and add the following:

```
"minimum-stability": "dev",
"prefer-stable": true,
```

After this, you can run `composer install`, and follow the rest of the instructions.

### Generate frontend assets

Run the following to generate development versions of the assets:

<details>
<summary>Makefile</summary>

```
make assets
```
</details>

<details>
<summary>Manually</summary>

```
yarn install
yarn build:dev
```
</details>

or to build production versions of the assets:

<details>
<summary>Makefile</summary>

```
make assets-prod
```
</details>

<details>
<summary>Manually</summary>

```
yarn install
yarn build:prod
```
</details>

### Generate backend assets

Run the following to generate Ibexa Admin UI assets:

<details>
<summary>Makefile</summary>

```
make ibexa-assets
```
</details>

<details>
<summary>Manually</summary>

```
composer ibexa-assets
```
</details>

### Note for Ibexa DXP official WebPack support

This repo completely replaces the default `webpack.config.js` file coming from Ibexa DXP with
Netgen Site specific version which is used **only** for frontend of the project. The Ibexa provided
file is renamed to `webpack.config.ibexa.js` without changes.

Also, automatic building of Ibexa Admin UI assets on every `composer install` or `composer update`
has been disabled so there's no need to install `nodejs` or `yarn` on your production servers to build
those assets. Either deploy them via your deployment procedures, or commit the entire `public/assets` folder
to the git repository. You can build the Ibexa Admin UI assets on demand simply by executing
`composer ibexa-assets`.

If, however, you wish to bring back building Ibexa Admin UI assets when running Composer, add the
`public/assets/` folder to `.gitignore` and add the following to `symfony-scripts` in your `composer.json`:

```json
"@php bin/console bazinga:js-translation:dump public/assets --merge-domains",
"yarn install",
"yarn ibexa"
```

Note that you do NOT need to rename `webpack.config.ibexa.js` back to its old name since
`yarn ibexa` takes the new name into account.

More info: https://github.com/ezsystems/ezplatform/pull/392

### Import database schema and demo data

Configure your database in `.env.local` file in your project root folder (change the username,
password, database host and name as required, based on your setup):

```
DATABASE_URL=mysql://root:mypass@127.0.0.1/media_site
```

Run the following command to import database schema and demo data (add `--env=prod`
after `bin/console` if running in prod mode):

```
php bin/console ibexa:install <SITE_NAME>
```

where `<SITE_NAME>` is the name of wanted site, e.g. `netgen-media`.

The demo data adds an administrator user to the database.  This user's username
is `admin` and its password is `publish`.

Finally, generate the GraphQL schema for admin interface:

<details>
<summary>Makefile</summary>

```
make graphql-schema
```
</details>

<details>
<summary>Manually</summary>

```
php bin/console ibexa:graphql:generate-schema
```
</details>

### Generate image variations

It can be quite resource intensive to generate all needed image variations  at
request time, especially since demo content uses high quality and high resolution images.

To overcome this, you can use the following command to generate most used image variations for all images:

<details>
<summary>Makefile</summary>

```
make images
```
</details>

<details>
<summary>Manually</summary>

```
php bin/console ngsite:content:generate-image-variations --variations=i30,i160,i320,i480,nglayouts_app_preview,ngcb_thumbnail
```
</details>


This command will take a couple of minutes to complete, so grab a cup of coffee while it's running.

In addition to limiting the command on specific image variations, you can also limit it to a subset of
subtrees, content types and content fields. Use the following command to list all available options:

```
php bin/console ngsite:content:generate-image-variations --help
```

### Run the Symfony CLI server / Setup Apache virtual host

For development purposes, you can use [Symfony CLI](https://symfony.com/download) server to run the site.

Just start it from the project root with:

```
symfony server:start
```

Alternatively, you can create a new Apache virtual host and set it up to point
to `public/` directory inside the repo root.

An example virtual host is available at `doc/apache2/media-site-vhost.conf`

If you wish to use rewrite rules located `.htaccess` file instead of putting
them in virtual host configuration, you can use a virtual host variant located
at `doc/apache2/media-site.conf`

### Setup folder permissions

You need to setup file and directory permissions so Ibexa DXP can write to cache,
log and var folders:

```bash
$ setfacl -R -m u:<web-user>:rwX -m g:<web-user>:rwX var public/var
$ setfacl -dR -m u:<web-user>:rwX -m g:<web-user>:rwX var public/var
```

In case `setfacl` is not available on your system, refer to [Symfony installation instructions]
to set up the permissions correctly.

[Symfony installation instructions]: https://symfony.com/doc/3.4/setup/file_permissions.html

### Activate Captainhook

Symlink `captainhook.template.json` file to `captainhook.json`

```bash
$ ln -s captainhook.template.json captainhook.json
```
After that run ```composer install``` to activate git hooks used by Captainhook
