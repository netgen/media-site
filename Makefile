SYMFONY_ENV = dev
PHP_RUN = php7.4
COMPOSER_PATH = /usr/local/bin/composer2
ifeq ("$(wildcard $(COMPOSER_PATH))","")
	COMPOSER_PATH = /usr/local/bin/composer
endif
COMPOSER_RUN = $(PHP_RUN) $(COMPOSER_PATH)/composer.phar

.PHONY: help
help: ## List of all available commands
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-15s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

.DEFAULT_GOAL := help

.PHONY: php-version
php-version: ## See PHP version needed for this project
	@echo $(PHP_RUN)

.PHONY: vendor
vendor: ## Run composer install
	$(COMPOSER_RUN) install

.PHONY: assets-node
assets-node:
	sudo n auto

.PHONY: assets-build
assets-build: ## Build frontend assets for specified environment (default: SYMFONY_ENV=dev)
	@$(MAKE) -s assets-node
	yarn install
	yarn build:$(SYMFONY_ENV)

.PHONY: assets-watch
assets-watch: ## Watch frontend assets (during development)
	@$(MAKE) -s assets-node
	yarn install
	yarn watch

.PHONY: clear-cache
clear-cache: ## Clear caches for specified environment (default: SYMFONY_ENV=dev)
	$(PHP_RUN) bin/console cache:clear --env=$(SYMFONY_ENV)

.PHONY: images
images: ## Generate most used image variations for all images for specified environment (default: SYMFONY_ENV=dev)
	$(PHP_RUN) bin/console ngsite:content:generate-image-variations --variations=i30,i160,i320,i480,nglayouts_app_preview,ngcb_thumbnail --env=$(SYMFONY_ENV)
