IMAGE = gustavofreze/php:8.2
DOCKER_RUN = docker run -u root --rm -it --net=host -v ${PWD}:/app -w /app ${IMAGE}
DOCKER_EXEC = docker exec -it cheap-delivery

INTEGRATION_TEST = docker run -u root --rm -it --name cheap-delivery-integration-test --link cheap-delivery-adm --network=cheap-delivery_default -v ${PWD}:/app -w /app ${IMAGE}

FLYWAY = docker run --rm -v ${PWD}/db/mysql/migrations:/flyway/sql --env-file=config/local.env --link cheap-delivery-adm --network=cheap-delivery_default flyway/flyway:10.8.1
MIGRATE_DB = ${FLYWAY} -locations=filesystem:/flyway/sql -schemas=cheap_delivery_adm
MIGRATE_TEST_DB = ${FLYWAY} -locations=filesystem:/flyway/sql -schemas=cheap_delivery_adm_test

start:
	@docker-compose up -d --build

configure:
	@${DOCKER_RUN} composer update --optimize-autoloader

test: migrate-test-database
	@${INTEGRATION_TEST} composer run tests

test-no-coverage: migrate-test-database
	@${INTEGRATION_TEST} composer run test-no-coverage

test-unit:
	@${DOCKER_RUN} composer run test-unit

test-integration: migrate-test-database
	@${INTEGRATION_TEST} composer run test-integration

review:
	@${DOCKER_RUN} composer review

fix-style:
	@${DOCKER_RUN} composer fix-style

show-coverage:
	@sensible-browser report/coverage/coverage-html/index.html report/coverage/mutation-report.html

clean:
	@sudo chown -R ${USER}:${USER} ${PWD}
	@rm -rf report vendor *.lock .phpunit.cache

migrate-database:
	@${MIGRATE_DB} migrate

clean-database:
	@${MIGRATE_DB} clean

migrate-test-database:
	@${MIGRATE_TEST_DB} clean
	@${MIGRATE_TEST_DB} migrate
