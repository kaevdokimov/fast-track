#!make

init: docker-clear docker-build docker-up composer-install migrate fixtures
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

test:
	docker-compose exec php vendor/bin/phpunit

clear:
	docker-compose exec php bin/console cache:clear

app:
	docker-compose exec php bash

migration:
	docker-compose exec php symfony console make:migration

migrate:
	docker-compose exec php symfony console doctrine:migrations:migrate --all-or-nothing --query-time --no-interaction

composer-install:
	docker-compose exec php composer install
	docker-compose exec php composer dump-autoload

composer-update:
	docker-compose exec php composer update --apcu-autoloader -o
	docker-compose exec php composer dump-autoload

fixtures:
	docker-compose exec php bin/console doctrine:fixtures:load --purge-with-truncate --no-interaction

fixtures-tests:
	docker-compose exec php bin/console doctrine:fixtures:load --purge-with-truncate --no-interaction --env=test
