Edit translations from admin
=============================

Thanks to the [Prime Translations Bundle](https://github.com/primedigital/prime-translations-bundle) (based on the [LexikTranslationBundle](https://github.com/lexik/LexikTranslationBundle)) it is possible to manipulate Symfony translations through the admin interface.

Configuration
-------------

Configuration can be found in the main `app/config/config.yml` file:

```yaml
lexik_translation:
    fallback_locale: [en]
    managed_locales: [en]

    base_layout: "@PrimeTranslations/pagelayout.html.twig"
    grid_input_type: text
    grid_toggle_similar: false
    storage:
        type: orm
```

The most important is to configure locales that you want to manage with this bundle. For all other configuration options, check bundle's [GitHub repository](https://github.com/primedigital/prime-translations-bundle).

Import existing translations
----------------------------

Each time some new translations are being added, those have to be manually imported into the database. This can be done using the following command:

```console
php bin/console lexik:translations:import AppBundle
```

This command will run through all translation domain files inside the provided `AppBundle` and import all translations in the database. Existing translations will be skipped, unless their translation has changed. 

Usage
-----

You will find a link called `Translations` in the main menu inside administration which leads to this module. There you can see all available translations, delete or modify existing ones as well as add new ones.
