.DEFAULT_GOAL := start

start:
	docker-compose up -d

bash: start
	docker-compose exec php bash

migrate: start
	docker-compose exec php php bin/console doctrine:migrations:migrate

test: start
	docker-compose exec php php bin/phpunit -c phpunit.xml.dist