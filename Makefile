init: docker-down-clear \
	app-clear \
	docker-pull docker-build docker-up \
	app-init
up: docker-up
down: docker-down
restart: docker-down docker-up
check: app-check app-fixtures

update-deps: app-composer-update restart

docker-up:
	docker compose up -d

docker-down:
	docker compose down --remove-orphans

docker-down-clear:
	docker compose down -v --remove-orphans

docker-pull:
	docker compose pull

docker-build:
	docker compose build --pull

app-clear:
	docker run --rm -v ${PWD}/app:/app -w /app alpine sh -c 'rm -rf var/cache/* var/log/* var/test/*'

app-init: app-permissions app-composer-install app-wait-db app-migrations app-fixtures

app-permissions:
	docker run --rm -v ${PWD}/app:/app -w /app alpine chmod 777 var/cache var/log var/test

app-composer-install:
	docker compose run --rm app-php-cli composer install

app-composer-update:
	docker compose run --rm app-php-cli composer update

app-wait-db:
	docker compose run --rm app-php-cli wait-for-it app-postgres:5432 -t 30

app-migrations:
	docker compose run --rm app-php-cli composer app migrations:migrate -- --no-interaction

app-fixtures:
	docker compose run --rm app-php-cli composer app fixtures:load

app-check: app-validate-schema app-lint app-analyze app-test

app-validate-schema:
	docker compose run --rm app-php-cli composer app orm:validate-schema

app-lint:
	docker compose run --rm app-php-cli composer lint
	docker compose run --rm app-php-cli composer php-cs-fixer fix -- --dry-run --diff

app-lint-fix:
	docker compose run --rm app-php-cli composer php-cs-fixer fix

app-analyze:
	docker compose run --rm app-php-cli composer psalm -- --no-diff

app-test:
	docker compose run --rm app-php-cli composer test

build: build-app

build-app:
	docker --log-level=debug build --pull --file=app/docker/production/nginx/Dockerfile --tag=${REGISTRY}/app:${IMAGE_TAG} app
	docker --log-level=debug build --pull --file=app/docker/production/php-fpm/Dockerfile --tag=${REGISTRY}/app-php-fpm:${IMAGE_TAG} app
	docker --log-level=debug build --pull --file=app/docker/production/php-cli/Dockerfile --tag=${REGISTRY}/app-php-cli:${IMAGE_TAG} app

try-build:
	REGISTRY=localhost IMAGE_TAG=0 make build

testing-build: testing-build-testing-app-php-cli

testing-build-testing-app-php-cli:
	docker --log-level=debug build --pull --file=app/docker/testing/php-cli/Dockerfile --tag=${REGISTRY}/testing-app-php-cli:${IMAGE_TAG} app

testing-init:
	COMPOSE_PROJECT_NAME=testing docker compose -f docker-compose-testing.yml up -d
	COMPOSE_PROJECT_NAME=testing docker compose -f docker-compose-testing.yml run --rm app-php-cli wait-for-it app-postgres:5432 -t 60
	COMPOSE_PROJECT_NAME=testing docker compose -f docker-compose-testing.yml run --rm app-php-cli php bin/app.php migrations:migrate --no-interaction
	COMPOSE_PROJECT_NAME=testing docker compose -f docker-compose-testing.yml run --rm testing-app-php-cli php bin/app.php fixtures:load --no-interaction
	sleep 5

testing-down-clear:
	COMPOSE_PROJECT_NAME=testing docker compose -f docker-compose-testing.yml down -v --remove-orphans

try-testing: try-build try-testing-build try-testing-init try-testing-down-clear

try-testing-build:
	REGISTRY=localhost IMAGE_TAG=0 make testing-build

try-testing-init:
	REGISTRY=localhost IMAGE_TAG=0 make testing-init

try-testing-down-clear:
	REGISTRY=localhost IMAGE_TAG=0 make testing-down-clear
