PHP_IMAGE = gustavofreze/php:8.2
DOCKER_RUN = docker run -u root --rm -it --net=host -v ${PWD}:/app -w /app ${PHP_IMAGE}
DOCKER_EXEC = docker exec -it cheap-delivery

INTEGRATION_TEST = docker run -u root --rm -it --name cheap-delivery-integration-test --link cheap-delivery-adm --network=cheap-delivery_default -v ${PWD}:/app -w /app ${PHP_IMAGE}

FLYWAY_IMAGE = flyway/flyway:10.9.1
FLYWAY_RUN = docker run --rm -v ${PWD}/db/mysql/migrations:/flyway/sql --env-file=config/local.env --link cheap-delivery-adm --network=cheap-delivery_default ${FLYWAY_IMAGE}
MIGRATE_DB = ${FLYWAY_RUN} -locations=filesystem:/flyway/sql -schemas=cheap_delivery_adm
MIGRATE_TEST_DB = ${FLYWAY_RUN} -locations=filesystem:/flyway/sql -schemas=cheap_delivery_adm_test

start:
	@docker-compose up -d --build

configure:
	@${DOCKER_RUN} composer update --optimize-autoloader

test: migrate-test-database
	@${INTEGRATION_TEST} composer run tests

unit-test:
	@${DOCKER_RUN} composer run unit-test

integration-test: migrate-test-database
	@${INTEGRATION_TEST} composer run integration-test

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
