setup-local:
	./bin/setup-local.sh

test:
	docker-compose exec app php artisan test

lint:
	docker-compose exec app composer lint
	docker-compose exec app npm run lint

down:
	docker-compose down
