PWD := $(shell pwd -L)
PHP_IMAGE := gustavofreze/php:8.2
APP_RUN := docker run -u root --rm -it --network=host -v ${PWD}:/app -w /app ${PHP_IMAGE}
APP_TEST_RUN := docker run -u root --rm -it --name cheap-delivery-integration-test --link cheap-delivery-adm --network=cheap-delivery_default -v ${PWD}:/app -w /app ${PHP_IMAGE}

FLYWAY_IMAGE := flyway/flyway:10.9.1
FLYWAY_RUN := docker run --rm -v ${PWD}/db/mysql/migrations:/flyway/sql --env-file=config/local.env --link cheap-delivery-adm --network=cheap-delivery_default ${FLYWAY_IMAGE}
MIGRATE_DB := ${FLYWAY_RUN} -locations=filesystem:/flyway/sql -schemas=cheap_delivery_adm
MIGRATE_TEST_DB := ${FLYWAY_RUN} -locations=filesystem:/flyway/sql -schemas=cheap_delivery_adm_test

.DEFAULT_GOAL := help

.PHONY: start configure test unit-test integration-test review fix-style show-coverage clean migrate-database clean-database migrate-test-database help

start: ## Start Docker Compose services
	@docker-compose up -d --build

configure: ## Configure development environment
	@${APP_RUN} composer update --optimize-autoloader

test: migrate-test-database ## Run all tests
	@${APP_TEST_RUN} composer run tests

unit-test: ## Run unit tests
	@${APP_RUN} composer run unit-test

integration-test: migrate-test-database ## Run integration tests
	@${APP_TEST_RUN} composer run integration-test

review: ## Run code review
	@${APP_RUN} composer review

fix-style: ## Fix code style
	@${APP_RUN} composer fix-style

show-coverage: ## Open code coverage reports in browser
	@sensible-browser report/coverage/coverage-html/index.html report/coverage/mutation-report.html

migrate-database: ## Run database migrations
	@${MIGRATE_DB} migrate

clean-database: ## Clean database
	@${MIGRATE_DB} clean

migrate-test-database: ## Run test database migrations
	@${MIGRATE_TEST_DB} clean
	@${MIGRATE_TEST_DB} migrate

help: ## Display this help message
	@echo "Usage: make [target]"
	@echo ""
	@echo "Setup and run"
	@grep -E '^(configure|start|migrate-database|clean-database):.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
	@echo ""
	@echo "Testing"
	@grep -E '^(test|unit-test|integration-test|show-coverage|migrate-test-database):.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
	@echo ""
	@echo "Code review "
	@grep -E '^(review|fix-style):.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
	@echo ""
