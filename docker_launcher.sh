# This script intends to launch Akeneo PIM thanks to docker, with the API fixtures

if [[ $# -ne 4 ]] ; then
    printf "%s\n" "Usage : " \
        "1st arg : name of the PIM docker instance, should be consistent with etc/parameters.yml configuration" \
        "2nd arg : path to the pim installation       " \
        "3th arg : path to the php client installation" \
        "4th arg : path to the composer directory     " \
        "Example: ./(basename $0) akeneo-pim /home/docker/pim /home/docker/client /home/docker/.composer"
    exit 1
 fi

docker run --name mysql -e MYSQL_ROOT_PASSWORD=root -e MYSQL_USER=akeneo_pim -e MYSQL_PASSWORD=akeneo_pim -e MYSQL_DATABASE=akeneo_pim -d mysql:5.5 --sql-mode=ERROR_FOR_DIVISION_BY_ZERO,NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION

docker run --name $1 -e COMPOSER_HOME=/home/docker/.composer --link mysql:mysql -p 8080:80 -p 8081:81 -v $2:/home/docker/pim -v $4:/home/docker/.composer -d carcel/akeneo-apache:php-5.6
docker exec $1 sh -c "cd pim; composer require \"akeneo/catalogs\":\"dev-master\" --ignore-platform-reqs --optimize-autoloader --no-interaction --no-progress --prefer-dist"
docker exec $1 sh -c "cp pim/app/config/parameters.yml.dist pim/app/config/parameters.yml"
docker exec $1 sh -c "sed -i 's/database_host:     localhost/database_host:     mysql/' pim/app/config/parameters.yml"
docker exec $1 sh -c "sed -i \"s@installer_data: .*@installer_data: '%kernel.root_dir%/../vendor/akeneo/catalogs/master/community/small/fixtures'@\" pim/app/config/pim_parameters.yml"
docker exec $1 sh -c "pim/app/console pim:install -e prod --force"

docker run --name php-api-client -e COMPOSER_HOME=/home/docker/.composer --link $1:$1 --rm -it  -v /var/run/docker.sock:/var/run/docker.sock -v $3:/home/docker -v $4:/home/docker/.composer carcel/php:5.6 "curl -sSL https://get.docker.com/ | sh; composer update --ignore-platform-reqs --optimize-autoloader --no-interaction --no-progress --prefer-dist; mkdir -p app/build/logs; sudo ./bin/phpunit -c phpunit.xml --log-junit app/build/logs/phpunit_integration.xml"
