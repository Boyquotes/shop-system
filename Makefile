initialize:
	docker-compose up -d
	docker-compose exec php bin/console doctrine:migrations:diff
	docker-compose exec php bin/console doctrine:migrations:migrate
	docker-compose exec php bin/console lexik:jwt:generate-keypair

run:
	docker-compose up -d

drop_migrations:
	docker-compose exec php bin/console doctrine:schema:drop --full-database --force

migrate:
	docker-compose exec php bin/console doctrine:migrations:diff
	docker-compose exec php bin/console doctrine:migrations:migrate

load_fixtures:
	docker-compose exec php bin/console doctrine:fixtures:load

test:
	docker-compose exec php bin/tests.sh

fix_codesniffer:
	docker-compose exec php vendor/bin/phpcbf

cache_clear:
	docker-compose exec php bin/console cache:clear
