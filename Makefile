dev:
	docker compose -f docker-compose.dev.yml up -d

dev-build:
	docker compose -f docker-compose.dev.yml up --build

prod:
	docker compose -f docker-compose.prod.yml up -d

prod-build:
	docker compose -f docker-compose.prod.yml up --build -d

stop:
	docker compose -f docker-compose.dev.yml down
	docker compose -f docker-compose.prod.yml down

stop-dev:
	docker compose -f docker-compose.dev.yml down

stop-prod:
	docker compose -f docker-compose.prod.yml down

clean:
	docker compose -f docker-compose.dev.yml down -v
	docker compose -f docker-compose.prod.yml down -v
	docker system prune -f

logs:
	docker compose -f docker-compose.dev.yml logs -f

logs-prod:
	docker compose -f docker-compose.prod.yml logs -f

shell:
	docker exec -it ominimo-blog-app-dev bash

shell-prod:
	docker exec -it ominimo-blog-app-prod bash

test:
	docker exec -it ominimo-blog-app-dev ./vendor/bin/pest

install:
	docker exec -it ominimo-blog-app-dev composer install
	docker exec -it ominimo-blog-app-dev npm install

migrate:
	docker exec -it ominimo-blog-app-dev php artisan migrate

migrate-prod:
	docker exec -it ominimo-blog-app-prod php artisan migrate

seed:
	docker exec -it ominimo-blog-app-dev php artisan db:seed

fresh:
	docker exec -it ominimo-blog-app-dev php artisan migrate:fresh --seed
