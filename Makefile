DOCKER_RUN ?= DOCKER_BUILDKIT=1 docker-compose run php_client

.PHONY: help
help:
	@grep -E '(^[a-zA-Z_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[32m%-25s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

.PHONY: build-image
build-image: ## Build docker image
	DOCKER_BUILDKIT=1 docker-compose build

.PHONY:
.PHONY: dependencies
dependencies: ## Install composer dependencies
	cp docker-compose.yml.dist docker-compose.yml
	rm -rf composer.lock vendor/
	$(DOCKER_RUN) composer install

.PHONY: tests
tests: unit spec cs ## Run PHPUnit & PHPSpec tests, and code style check

.PHONY: unit
unit: ## Run PHPUnit tests
	@echo "-----------"
	@echo "- PHPUnit -"
	@echo "-----------"
	$(DOCKER_RUN) bin/phpunit -c phpunit.xml.dist

.PHONY: spec
spec: ## Run PHPSpec tests
	@echo "-----------"
	@echo "- PHPSpec -"
	@echo "-----------"
	$(DOCKER_RUN) bin/phpspec run

.PHONY: cs
cs: ## Run code style check
	@echo "------------------"
	@echo "- PHP code style -"
	@echo "------------------"
	$(DOCKER_RUN) bin/ecs

.PHONY: fix-cs
fix-cs: ## Fix PHP code style
	$(DOCKER_RUN) bin/ecs --fix

.PHONY: rector
rector: ## Run rector
	$(DOCKER_RUN) bin/rector
