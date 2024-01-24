setup:
	docker compose -f docker-compose.yaml up -d --build
	docker exec -t mastergas_php cp .env.example .env
	docker exec -t mastergas_php composer install
	docker exec -t mastergas_php chmod -R 755 /var/www/html/storage
	docker exec -t mastergas_php chmod -R 755 /var/www/html/bootstrap/cache
	docker exec -t mastergas_php chown -R www-data:www-data /var/www/html/storage
	docker exec -t mastergas_php chown -R www-data:www-data /var/www/html/bootstrap/cache

up:
	docker compose -f docker-compose.yaml up -d

down:
	docker compose -f docker-compose.yaml down