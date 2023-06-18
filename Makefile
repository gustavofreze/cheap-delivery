DOCKER_RUN = docker run --rm -it --net=host -v ${PWD}:/app -w /app gustavofreze/cheap-delivery
DOCKER_EXEC = docker exec -it cheap-delivery

.PHONY: configure run test test-no-coverage review show-reports clean

configure:
	@docker-compose up -d --build
	@${DOCKER_EXEC} composer update --optimize-autoloader

run:
	@${DOCKER_RUN} composer update --optimize-autoloader

test: run review
	@${DOCKER_RUN} composer tests

test-no-coverage: run review
	@${DOCKER_RUN} composer tests-no-coverage

review:
	@${DOCKER_RUN} composer review

show-reports:
	@sensible-browser report/coverage/coverage-html/index.html

clean:
	@sudo chown -R ${USER}:${USER} ${PWD}
	@rm -rf report vendor
