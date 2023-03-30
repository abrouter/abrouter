.PHONY: up
up:
	docker-compose up -d

.PHONY: stop
stop:
	docker-compose stop

.PHONY: consul
consul: check ./docker-compose.env
	docker-compose exec abr-app-api rm -f .env
	docker-compose exec abr-app-api /app/docker/build/consul.sh
	docker-compose exec abr-app-front rm -f .env
	docker-compose exec abr-app-front /app/docker/build/consul.sh

.PHONY: fill-consul
fill-consul:
	docker-compose exec abr-app-api rm -f /app/.env
	docker-compose exec abr-app-api php /app/docker/build/consul.php i

.PHONY: migrate
migrate:
	docker-compose exec abr-app-api php artisan migrate

.PHONY: install
install:
	docker-compose exec abr-app-api composer install
	docker-compose exec abr-app-front composer install

.PHONY: passport
passport:
	docker-compose exec abr-app-api chmod -R 777 /app/storage
	docker-compose exec abr-app-api php artisan passport:install

.PHONY: build-prod
build-prod:
	php build/compose.php --mode=prod

.PHONY: build-dev
build-dev:
	php build/compose.php --mode=local
