compose-bash:
	docker-compose exec app bash

compose-build:
	docker-compose build

compose-up:
	docker-compose up -d

init:
	docker-compose exec app composer install
	docker-compose exec app cp .env.example .env
	docker-compose exec app php artisan key:generate

test:
	docker-compose exec app bash -c "./vendor/bin/phpunit"
