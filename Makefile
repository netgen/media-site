SYMFONY_ENV = dev
PHP_VERSION = php7.4
PHP_RUN = /usr/bin/env $(PHP_VERSION)
COMPOSER_PATH = /usr/local/bin/composer2
ifeq ("$(wildcard $(COMPOSER_PATH))","")
	COMPOSER_PATH = /usr/local/bin/composer
endif
COMPOSER_RUN = $(PHP_RUN) $(COMPOSER_PATH)
CACHE_POOL = cache.redis

.PHONY: help
help: ## List of all available commands
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.DEFAULT_GOAL := help

.PHONY: php-version
php-version: ## See PHP version needed for this project
	@echo $(PHP_VERSION)

COMPOSER_INSTALL_PARAMETERS =
ifeq ($(SYMFONY_ENV), prod)
	COMPOSER_INSTALL_PARAMETERS = --no-dev -o
endif

.PHONY: vendor
vendor: ## Run composer install
	$(COMPOSER_RUN) install $(COMPOSER_INSTALL_PARAMETERS)

.PHONY: assets
.ONESHELL:
assets: ## Build frontend assets for DEV environment
	. ${NVM_DIR}/nvm.sh && nvm use || nvm install $(cat .nvmrc)
	yarn install
	yarn build:dev

.PHONY: assets-prod
.ONESHELL:
assets-prod: assets-node ## Build frontend assets for PROD environment
	. ${NVM_DIR}/nvm.sh && nvm use || nvm install $(cat .nvmrc)
	yarn install
	yarn build:prod

.PHONY: assets-watch
.ONESHELL:
assets-watch: assets-node ## Watch frontend assets (during development)
	. ${NVM_DIR}/nvm.sh && nvm use || nvm install $(cat .nvmrc)
	yarn install
	yarn watch

.PHONY: graphql-schema
graphql-schema: ## Generate graphql schema
	$(PHP_RUN) bin/console ezplatform:graphql:generate-schema --env=$(SYMFONY_ENV)

.PHONY: clear-cache
clear-cache: ## Clear caches for specified environment (default: SYMFONY_ENV=dev)
	$(PHP_RUN) bin/console cache:clear --env=$(SYMFONY_ENV)

.PHONY: clear-all-cache
clear-all-cache: ## Clear all caches for specified environment (including eg. Redis) (default: SYMFONY_ENV=dev)
	@$(MAKE) -s clear-cache
	$(PHP_RUN) bin/console cache:pool:clear $(CACHE_POOL) --env=$(SYMFONY_ENV)

.PHONY: images
images: ## Generate most used image variations for all images for specified environment (default: SYMFONY_ENV=dev)
	$(PHP_RUN) bin/console ngsite:content:generate-image-variations --variations=i30,i160,i320,i480,nglayouts_app_preview,ngcb_thumbnail --env=$(SYMFONY_ENV)

.PHONY: migrations
migrations: ## Run Doctrine migrations for specified environment (default: SYMFONY_ENV=dev)
	$(PHP_RUN) bin/console doctrine:migration:migrate --allow-no-migration --env=$(SYMFONY_ENV)

.PHONY: reindex
reindex: ## Recreate or refresh search engine index for specified environment (default: SYMFONY_ENV=dev)
	$(PHP_RUN) bin/console ezplatform:reindex --env=$(SYMFONY_ENV)

.PHONY: build
build: ## Build the project (install vendor, migrations, reindex, build assets, clear cache) for specified environment (default: SYMFONY_ENV=dev)
	@$(MAKE) -s vendor
	@$(MAKE) -s migrations
	@$(MAKE) -s reindex
ifeq ($(SYMFONY_ENV), prod)
	$(MAKE) -s assets-prod
else
	$(MAKE) -s assets
endif
	@$(MAKE) -s graphql-schema
	@$(MAKE) -s clear-cache

.PHONY: refresh
refresh: ## Fetch latest changes and build the project for specified environment (default: SYMFONY_ENV=dev)
	/usr/bin/env git stash
	/usr/bin/env git pull --rebase
	/usr/bin/env git stash pop
	@$(MAKE) -s build
