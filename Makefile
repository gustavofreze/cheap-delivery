DOCKER_RUN = docker run --rm -it --net=host -v ${PWD}:/app -w /app gustavofreze/cheap-delivery
DOCKER_EXEC = docker exec -it cheap-delivery

.PHONY: run clean configure

configure:
	@docker-compose up -d --build
	@${DOCKER_EXEC} composer update --optimize-autoloader

run:
	@${DOCKER_RUN} composer update --optimize-autoloader

test: run
	@${DOCKER_RUN} composer test

test-coverage: run
	@${DOCKER_RUN} composer test-coverage

show-coverage:
	@sensible-browser report/coverage-html/index.html

review: run
	@${DOCKER_RUN} composer phpmd

clean:
	@sudo chown -R ${USER}:${USER} ${PWD}
	@rm -rf report vendor