IMAGE = gustavofreze/php:8.2
DOCKER_RUN = docker run -u root --rm -it --net=host -v ${PWD}:/app -w /app ${IMAGE}
DOCKER_EXEC = docker exec -it cheap-delivery

FLYWAY = docker run --rm -v ${PWD}/db/mysql/migrations:/flyway/sql --env-file=config/local.env --link cheap-delivery-adm --network=cheap-delivery_default -e FLYWAY_EDITION="community" flyway/flyway:10.8.1
MIGRATE_DB = ${FLYWAY} -locations=filesystem:/flyway/sql -schemas=cheap_delivery_adm

.PHONY: configure run test test-no-coverage review fix-style show-coverage clean migrate-database clean-database

configure:
	@docker-compose up -d --build

configure-local:
	@${DOCKER_RUN} composer update --optimize-autoloader

test:
	@${DOCKER_RUN} composer run tests

test-no-coverage:
	@${DOCKER_RUN} composer run test-no-coverage

test-unit:
	@${DOCKER_RUN} composer run test-unit

test-integration:
	@${DOCKER_RUN} composer run test-integration

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
