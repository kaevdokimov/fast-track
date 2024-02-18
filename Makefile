#!make

init: docker-clear docker-build docker-up composer-install migrate fixtures messenger-init messenger-run test-init
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

tests-workflow:
	./bin/console doctrine:database:create --if-not-exists --env=test
	./bin/console doctrine:migrations:migrate -n --env=test
	./bin/console doctrine:fixtures:load --purge-with-truncate -n --env=test
	./bin/phpunit --uses workflow

tests:
	docker-compose exec php symfony console doctrine:database:create --if-not-exists --env=test
	docker-compose exec php symfony console doctrine:migrations:migrate -n --env=test
	docker-compose exec php symfony console doctrine:fixtures:load --purge-with-truncate -n --env=test
	docker-compose exec php symfony server:stop
	docker-compose exec php symfony console messenger:setup-transports -n --env=test
	docker-compose exec php symfony run -d symfony console messenger:consume async -q -n --env=test
	docker-compose exec php symfony php bin/phpunit $(MAKECMDGOALS)
	docker-compose exec php symfony server:stop
.PHONY: tests

test-init:
	docker-compose exec php symfony console doctrine:database:create --if-not-exists --env=test
	docker-compose exec php symfony console doctrine:migrations:migrate -n --env=test
	docker-compose exec php symfony console doctrine:fixtures:load --purge-with-truncate -n --env=test

clear:
	docker-compose exec php symfony console cache:clear
	#rm -rf var/cache/dev/http_cache/

app:
	docker-compose exec php bash

messenger-init:
	docker-compose exec php symfony console messenger:setup-transports

messenger-run:
	docker-compose exec php symfony run -d --watch=config,src,templates,vendor symfony console messenger:consume async -vv

migration:
	docker-compose exec php symfony console make:migration

migrate:
	docker-compose exec php symfony console doctrine:migrations:migrate --all-or-nothing --query-time --no-interaction --env=dev

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

log:
	docker-compose exec php symfony server:log

stop:
	docker-compose exec php symfony server:stop
status:
	docker-compose exec php symfony server:status

