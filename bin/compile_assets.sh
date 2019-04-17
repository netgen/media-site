#!/bin/bash

# Script to generate assets using `yarn encore`.
# It checks SYMFONY_ENV to ensure assets are generated for correct production/development environment.

# NOTE: Check for Symfony environment is commented out since we do not need to build eZ Platform Admin UI
# assets in Symfony dev environment. Uncomment the check if you plan to add building assets to composer.json
# as provided by eZ Platform default install

# if [ "${SYMFONY_ENV}" == "dev" ] ; then
#     yarn encore dev --config=webpack.config.ezplatform.js
# else
    yarn encore prod --config=webpack.config.ezplatform.js
# fi
