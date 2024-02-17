#!make

init: docker-clear docker-build docker-up composer-install migrate fixtures test-init
up: docker-up
down: docker-down
restart: docker-down docker-up
#check: validate-schema lint

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down

docker-clear:
	docker-compose down -v --remove-orphans

docker-build:
	docker-compose build --pull

tests:
	docker-compose exec php symfony console doctrine:database:create --if-not-exists --env=test
	docker-compose exec php symfony console doctrine:migrations:migrate -n --env=test
	docker-compose exec php symfony console doctrine:fixtures:load --purge-with-truncate -n --env=test
	docker-compose exec php symfony php bin/phpunit $(MAKECMDGOALS)
.PHONY: tests

test-init:
	docker-compose exec php symfony console doctrine:database:create --if-not-exists --env=test
	docker-compose exec php symfony console doctrine:migrations:migrate -n --env=test
	docker-compose exec php symfony console doctrine:fixtures:load --purge-with-truncate -n --env=test

clear:
	docker-compose exec php symfony console cache:clear

app:
	docker-compose exec php bash

migration:
	docker-compose exec php symfony console make:migration

migrate:
	docker-compose exec php symfony console doctrine:migrations:migrate --all-or-nothing --query-time --no-interaction --write-sql --env=dev

migrate-prod:
	docker-compose exec php symfony console doctrine:migrations:migrate --all-or-nothing --query-time --no-interaction

composer-install:
	docker-compose exec php composer install

composer-update:
	docker-compose exec php composer update
	docker-compose exec php composer dump-autoload -o

fixtures:
	docker-compose exec php symfony console doctrine:fixtures:load --purge-with-truncate --no-interaction --env=dev

show-tables:
	docker-compose exec php symfony console dbal:run-sql "SELECT * FROM pg_catalog.pg_tables WHERE schemaname NOT IN ('pg_catalog','information_schema');"

