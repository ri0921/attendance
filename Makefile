init:
	docker-compose up -d --build
	docker-compose exec php composer install
	docker-compose exec php cp .env.example .env
	docker-compose exec php php artisan key:generate
	docker-compose exec php chmod -R 777 storage bootstrap/cache
	@make fresh

fresh:
	docker compose exec php php artisan migrate:fresh --seed

restart:
	@make down
	@make up

up:
	docker-compose up -d

down:
	docker compose down --remove-orphans

cache:
	docker-compose exec php php artisan config:clear
	docker-compose exec php php artisan config:cache

stop:
	docker-compose stop

start:
	docker-compose start
	docker-compose exec php bash

test:
	docker-compose exec php php artisan key:generate --env=testing
	docker-compose exec php php artisan config:clear
	docker-compose exec php php artisan config:cache
	docker-compose exec php php artisan migrate --env=testing