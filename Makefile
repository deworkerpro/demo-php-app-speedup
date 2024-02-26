init: docker-down-clear \
	app-clear \
	docker-pull docker-build docker-up \
	app-init \
	app-ready
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
	docker run --rm -v ${PWD}/app:/app -w /app alpine sh -c 'rm -rf .ready var/cache/* var/log/* var/test/*'

app-init: app-permissions app-composer-install

app-permissions:
	docker run --rm -v ${PWD}/app:/app -w /app alpine chmod 777 var var/log

app-composer-install:
	docker compose run --rm app-php-cli composer install

app-composer-update:
	docker compose run --rm app-php-cli composer update

app-ready:
	docker run --rm --volume ${PWD}/app:/app --workdir /app alpine touch .ready

build: build-app

build-app:
	docker --log-level=debug build --pull --file=app/docker/production/php/Dockerfile --tag=${REGISTRY}/app:${IMAGE_TAG} app
	docker --log-level=debug build --pull --file=app/docker/production/php-cli/Dockerfile --tag=${REGISTRY}/app-php-cli:${IMAGE_TAG} app

try-build:
	REGISTRY=localhost IMAGE_TAG=0 make build

testing-build: testing-build-benchmark

testing-build-benchmark:
	docker --log-level=debug build --pull --file=benchmark/Dockerfile --tag=${REGISTRY}/benchmark:${IMAGE_TAG} benchmark

testing-init:
	COMPOSE_PROJECT_NAME=testing docker compose -f docker-compose-testing.yml up -d
	sleep 10

testing-benchmark:
	COMPOSE_PROJECT_NAME=testing docker compose -f docker-compose-testing.yml run --rm benchmark ab -n 1 -d -r http://localhost/
	COMPOSE_PROJECT_NAME=testing docker compose -f docker-compose-testing.yml run --rm benchmark ab -n 100 -c 100 -d -r http://localhost/
	COMPOSE_PROJECT_NAME=testing docker compose -f docker-compose-testing.yml run --rm benchmark ab -n 100 -c 100 -d -r http://localhost/en/blog/

testing-down-clear:
	COMPOSE_PROJECT_NAME=testing docker compose -f docker-compose-testing.yml down -v --remove-orphans

try-testing: try-testing-down-clear try-build try-testing-build try-testing-init

try-testing-build:
	REGISTRY=localhost IMAGE_TAG=0 make testing-build

try-testing-init:
	REGISTRY=localhost IMAGE_TAG=0 make testing-init

try-testing-benchmark:
	REGISTRY=localhost IMAGE_TAG=0 make testing-benchmark

try-testing-down-clear:
	REGISTRY=localhost IMAGE_TAG=0 make testing-down-clear
