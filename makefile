.PHONY: install migration migration/dump restart start stop

DOCKER_COMPOSE_RUN=docker-compose run --rm --no-deps

install:
	$(DOCKER_COMPOSE_RUN) composer install

start:
	docker-compose up --detach postgres php nginx adminer

stop:
	docker-compose down --remove-orphans --volumes

restart:
	docker-compose restart

migration/dump:
	$(DOCKER_COMPOSE_RUN) php bin/console doctrine:schema:update --dump-sql

migration:
	$(DOCKER_COMPOSE_RUN) php bin/console doctrine:schema:update --force

fixtures:
	echo -ne "y\n" | $(DOCKER_COMPOSE_RUN) php bin/console doctrine:fixtures:load
