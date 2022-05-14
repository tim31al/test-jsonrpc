#!/bin/bash

#docker-compose up -d
#
#sleep 5

docker-compose exec -u app activity composer install
docker-compose exec -u app activity php bin/console doctrine:database:create -q || true
docker-compose exec -u app activity php bin/console doctrine:migrations:migrate -q
docker-compose exec -u app activity php bin/console doctrine:fixtures:load -q --group=AppFixtures
docker-compose exec -u app php bin/console lexik:jwt:generate-keypair

docker-compose exec -u app landing composer install
docker-compose exec -u app landing php bin/console doctrine:database:create -q || true
docker-compose exec -u app landing php bin/console doctrine:migrations:migrate -q
docker-compose exec -u app landing php bin/console doctrine:fixtures:load -q
docker-compose exec -u app landing php bin/console  messenger:consume async -vv



