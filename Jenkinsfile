#!groovy

String launchUnitTests = "yes"
String launchIntegrationTests = "yes"
def pimVersions = ["1.7", "master"]
def supportedPhpVersions = ["5.6", "7.0", "7.1"]

def clientConfig = [
    "php-http/guzzle6-adapter": ["phpVersion": supportedPhpVersions, "psrImplem": ["guzzlehttp/psr7"]],
    "php-http/guzzle5-adapter": ["phpVersion": supportedPhpVersions, "psrImplem": ["guzzlehttp/psr7", "zendframework/zend-diactoros", "slim/slim"]],
    "php-http/curl-client": ["phpVersion": supportedPhpVersions, "psrImplem": ["guzzlehttp/psr7", "zendframework/zend-diactoros", "slim/slim"]]
]

def clients = clientConfig.keySet() as String[]

stage("Checkout") {
    milestone 1
    if (env.BRANCH_NAME =~ /^PR-/) {
        userInput = input(message: 'Launch tests?', parameters: [
            string(defaultValue: pimVersions.join(','), description: 'PIM edition the tests should run on', name: 'requiredPimVersions'),
            choice(choices: 'yes\nno', description: 'Run unit tests and code style checks', name: 'launchUnitTests'),
            choice(choices: 'yes\nno', description: 'Run integration tests', name: 'launchIntegrationTests'),
            string(defaultValue: clients.join(','), description: 'Clients used to run integration tests (comma separated values)', name: 'clients'),
        ])

        pimVersions = userInput['requiredPimVersions'].tokenize(',')
        launchUnitTests = userInput['launchUnitTests']
        launchIntegrationTests = userInput['launchIntegrationTests']
        clients = userInput['clients'].tokenize(',')
    }
    milestone 2

    node {
        deleteDir()
        checkout scm
        stash "php-api-client"

        for (pimVersion in pimVersions) {
            deleteDir()
            checkout([$class: 'GitSCM',
                branches: [[name: pimVersion]],
                userRemoteConfigs: [[credentialsId: 'github-credentials', url: 'https://github.com/akeneo/pim-community-dev.git']]
            ])
            stash "pim_community_dev_${pimVersion}"
        }
    }

    checkouts = [:]

    if (launchUnitTests.equals("yes")) {
        String currentClient = "php-http/guzzle6-adapter"
        String currentPsrImplem = "guzzlehttp/psr7"

        for (phpVersion in clientConfig.get(currentClient).get("phpVersion")) {
            String currentPhpVersion = phpVersion

            checkouts["${currentClient}-${currentPsrImplem}-${currentPhpVersion}"] = {runCheckoutClient(currentPhpVersion, currentClient, currentPsrImplem)}
        }
    }

    if (launchIntegrationTests.equals("yes")) {
        for (pimVersion in pimVersions) {
            String currentPimVersion = pimVersion

            if ("master" == currentPimVersion) {
                checkouts["pim_community_dev_${currentPimVersion}"] = {runCheckoutPim18(currentPimVersion)}
            }

            if ("1.7" == currentPimVersion) {
                checkouts["pim_community_dev_${currentPimVersion}"] = {runCheckoutPim17(currentPimVersion)}
            }
        }

        for (client in clients) {
            for (phpVersion in clientConfig.get(client).get("phpVersion")) {
                for (psrImplem in clientConfig.get(client).get("psrImplem")) {
                    String currentClient = client
                    String currentPhpVersion = phpVersion
                    String currentPsrImplem = psrImplem

                    checkouts["${currentClient}-${currentPsrImplem}-${currentPhpVersion}"] = {runCheckoutClient(currentPhpVersion, currentClient, currentPsrImplem)}
                }
            }
        }
    }

    parallel checkouts
}

if (launchUnitTests.equals("yes")) {
    stage("Unit tests and Code style") {
        def tasks = [:]

        String currentClient = "php-http/guzzle6-adapter"
        String currentPsrImplem = "guzzlehttp/psr7"

        tasks["php-cs-fixer"] = {runPhpCsFixerTest("7.1", currentClient, currentPsrImplem)}

        for (phpVersion in clientConfig.get(currentClient).get("phpVersion")) {
            String currentPhpVersion = phpVersion

            tasks["phpspec-${phpVersion}"] = {runPhpSpecTest(currentPhpVersion, currentClient, currentPsrImplem)}
        }

        parallel tasks
    }
}

if (launchIntegrationTests.equals("yes")) {
    for (pimVersion in pimVersions) {
        String currentPimVersion = pimVersion
        stage("Integration tests ${currentPimVersion}") {
            def tasks = [:]

            for (client in clients) {
                for (phpVersion in clientConfig.get(client).get("phpVersion")) {
                    for (psrImplem in clientConfig.get(client).get("psrImplem")) {
                        String currentClient = client
                        String currentPsrImplem = psrImplem
                        String currentPhpVersion = phpVersion

                        tasks["phpunit-${currentClient}-${currentPsrImplem}-${currentPhpVersion}"] = {runIntegrationTest(currentPhpVersion, currentClient, currentPsrImplem, currentPimVersion)}
                    }
                }
            }

            parallel tasks
        }
    }
}

/**
 * Run checkout of the PIM for a given PHP version and a PIM version.
 *
 * @param pimVersion PIM version to checkout
 */
void runCheckoutPim17(String pimVersion) {
    node('docker') {
        deleteDir()
        try {
            docker.image("akeneo/php:5.6").inside("-v /home/akeneo/.composer:/home/docker/.composer") {
                unstash "pim_community_dev_${pimVersion}"

                sh "composer require \"akeneo/catalogs\":\"dev-master\" --optimize-autoloader --no-interaction --no-progress --prefer-dist"
                sh "cp app/config/parameters.yml.dist app/config/parameters.yml"
                sh "sed -i 's/database_host:     localhost/database_host:     mysql/' app/config/parameters.yml"
                sh "sed -i \"s@installer_data: .*@installer_data: '%kernel.root_dir%/../vendor/akeneo/catalogs/${pimVersion}/community/api/fixtures'@\" app/config/pim_parameters.yml"

                stash "pim_community_dev_${pimVersion}"
            }
        } finally {
            sh "docker stop \$(docker ps -a -q) || true"
            sh "docker rm \$(docker ps -a -q) || true"
            sh "docker volume rm \$(docker volume ls -q) || true"

            deleteDir()
        }
    }
}

/**
 * Run checkout of the PIM for a given PHP version and a PIM version.
 *
 * @param pimVersion PIM version to checkout
 */
void runCheckoutPim18(String pimVersion) {
    node('docker') {
        deleteDir()
        sh "docker stop \$(docker ps -a -q) || true"
        sh "docker rm \$(docker ps -a -q) || true"

        try {
            docker.image("akeneo/php:7.1").inside("-v /home/akeneo/.composer:/home/docker/.composer -e COMPOSER_HOME=/home/docker/.composer") {
                unstash "pim_community_dev_${pimVersion}"

                sh "composer require \"akeneo/catalogs\":\"dev-master\" --optimize-autoloader --no-interaction --no-progress --prefer-dist"
                sh "cp app/config/parameters_test.yml.dist app/config/parameters_test.yml"
                sh "sed -i \"s#database_host: .*#database_host: mysql#g\" app/config/parameters.yml"
                sh "sed -i \"s#index_hosts: .*#index_hosts: 'elasticsearch: 9200'#g\" app/config/parameters.yml"
                sh "sed -i \"s@installer_data: .*@installer_data: '%kernel.root_dir%/../vendor/akeneo/catalogs/${pimVersion}/community/api/fixtures'@\" app/config/pim_parameters.yml"

                stash "pim_community_dev_${pimVersion}"
            }
        } finally {
            sh "docker stop \$(docker ps -a -q) || true"
            sh "docker rm \$(docker ps -a -q) || true"
            sh "docker volume rm \$(docker volume ls -q) || true"

            deleteDir()
        }
    }
}

/**
 * Run checkout of the PHP client, for a given PHP version, HTTP client and PSR7 implementation.
 *
 * @param phpVersion PHP version to use to run the composer
 * @param client     name of the HTTP client package to use to checkout
 * @param psrImplem  name of the PSR 7 implementation package to checkout
 */
void runCheckoutClient(String phpVersion, String client, String psrImplem) {
    node('docker') {
        deleteDir()
        try {
            docker.image("akeneo/php:${phpVersion}").inside("-v /home/akeneo/.composer:/.composer -e COMPOSER_HOME=/.composer") {
                unstash "php-api-client"

                sh "composer require ${client} ${psrImplem}"
                sh "composer update --optimize-autoloader --no-interaction --no-progress --prefer-dist"

                sh "cp etc/parameters.yml.dist etc/parameters.yml"

                stash "php-api-client_${client}_${psrImplem}_php-${phpVersion}".replaceAll("/", "_")
            }
        } finally {
            sh "docker stop \$(docker ps -a -q) || true"
            sh "docker rm \$(docker ps -a -q) || true"
            sh "docker volume rm \$(docker volume ls -q) || true"

            deleteDir()
        }
    }
}

/**
 * Run php cs fixer, for a given PHP version, HTTP client and PSR7 implementation.
 *
 * @param phpVersion PHP version to run the test with
 * @param client     name of the HTTP client package to run the test with
 * @param psrImplem  name of the PSR 7 implementation package to run the test with
 */
void runPhpCsFixerTest(String phpVersion, String client, String psrImplem) {
    node('docker') {
        deleteDir()
        try {
            docker.image("akeneo/php:${phpVersion}").inside() {
                unstash "php-api-client_${client}_${psrImplem}_php-${phpVersion}".replaceAll("/", "_")

                sh "mkdir -p build/logs/"

                sh "./bin/php-cs-fixer fix --diff --dry-run --format=junit --config=.php_cs.php > build/logs/phpcs.xml"
            }
        } finally {
            sh "docker stop \$(docker ps -a -q) || true"
            sh "docker rm \$(docker ps -a -q) || true"
            sh "docker volume rm \$(docker volume ls -q) || true"

            sh "find build/logs/ -name \"*.xml\" | xargs sed -i \"s/testcase name=\\\"/testcase name=\\\"[php-cs-fixer] /\""
            junit "build/logs/*.xml"

            deleteDir()
        }
    }
}

/**
 * Run PHPspec tests, for a given PHP version, HTTP client and PSR7 implementation.
 *
 * @param phpVersion PHP version to run the test with
 * @param client     name of the HTTP client package to use to run the test with
 * @param psrImplem  name of the PSR 7 implementation package to run the test with
 */
void runPhpSpecTest(String phpVersion, String client, String psrImplem) {
    node('docker') {
        deleteDir()
        try {
            docker.image("akeneo/php:${phpVersion}").inside() {
                unstash "php-api-client_${client}_${psrImplem}_php-${phpVersion}".replaceAll("/", "_")

                sh "mkdir -p build/logs/"

                sh "./bin/phpspec run --no-interaction --format=junit > build/logs/phpspec.xml"
            }
        } finally {
            sh "docker stop \$(docker ps -a -q) || true"
            sh "docker rm \$(docker ps -a -q) || true"
            sh "docker volume rm \$(docker volume ls -q) || true"

            sh "find build/logs/ -name \"*.xml\" | xargs sed -i \"s/testcase name=\\\"/testcase name=\\\"[php-${phpVersion}] /\""
            junit "build/logs/*.xml"

            deleteDir()
        }
    }
}

/**
 * Run integration tests of the PHP client, for a given PHP version, HTTP client, PSR7 implementation and a PIM version.
 * First, it starts the PIM. The configuration of the PIM (composer, parameters) is already done in the checkout step.
 * Then, it launches the PHPUnit tests.
 *
 * Do note that PHPUnit resets the PIM database between each test and generates the API client id/secret,
 * thanks to "docker exec" commands inside the PHPUnit process.
 * In order to do that, the docker socket and docker bin are exposed as volumes to the PHPUnit container.
 *
 * @param phpVersion PHP version to run the test with
 * @param client     name of the HTTP client package to use to run the test with
 * @param psrImplem  name of the PSR 7 implementation package to run the test with
 * @param pimVersion PIM version to run the test with
 */
void runIntegrationTest(String phpVersion, String client, String psrImplem, String pimVersion) {
    node('docker') {
        deleteDir()
        try {
            dir('pim') {
                unstash "pim_community_dev_${pimVersion}"

                if ("master" == pimVersion) {
                    sh "docker run --name mysql -e MYSQL_ROOT_PASSWORD=root -e MYSQL_USER=akeneo_pim -e MYSQL_PASSWORD=akeneo_pim -e MYSQL_DATABASE=akeneo_pim --tmpfs=/var/lib/mysql/:rw,noexec,nosuid,size=400m --tmpfs=/tmp/:rw,noexec,nosuid,size=200m -d mysql:5.7"
                    sh "docker run --name elasticsearch -e ES_JAVA_OPTS=\"-Xms256m -Xmx256m\" -d elasticsearch:5"
                    sh "docker run --name akeneo-pim --link mysql:mysql --link elasticsearch:elasticsearch -v \$(pwd):/srv/pim -w /srv/pim -d akeneo/fpm:php-7.1"
                    sh "docker run --name httpd --link akeneo-pim:fpm -v \$(pwd):/srv/pim -v \$(pwd)/docker/httpd.conf:/usr/local/apache2/conf/httpd.conf -v \$(pwd)/docker/akeneo.conf:/usr/local/apache2/conf/vhost.conf -w /srv/pim -d httpd:2.4"
                }

                if ("1.7" == pimVersion) {
                    sh "docker run --name mysql -e MYSQL_ROOT_PASSWORD=root -e MYSQL_USER=akeneo_pim -e MYSQL_PASSWORD=akeneo_pim -e MYSQL_DATABASE=akeneo_pim --tmpfs=/var/lib/mysql/:rw,noexec,nosuid,size=400m --tmpfs=/tmp/:rw,noexec,nosuid,size=200m -d mysql:5.5"
                    sh "docker run --name akeneo-pim --link mysql:mysql -v \$(pwd):/srv/pim -v \$(pwd)/docker/akeneo.conf:/etc/apache2/sites-available/000-default.conf:ro -w /srv/pim -d akeneo/apache-php:php-5.6"
                }

                // Wait for elasticsearch container ready
                sh "sleep 20"
                sh "docker exec akeneo-pim app/console pim:install -e prod"
            }

            unstash "php-api-client_${client}_${psrImplem}_php-${phpVersion}".replaceAll("/", "_")
            sh "mkdir -p build/logs/"

            if ("master" == pimVersion) {
                docker.image("akeneo/php:${phpVersion}").inside("--link akeneo-pim:akeneo-pim --link httpd:httpd -v /var/run/docker.sock:/var/run/docker.sock -v /usr/bin/docker:/usr/bin/docker -w /home/docker/client --privileged") {
                    sh "sed -i \"s#baseUri: .*#baseUri: 'http://httpd'#g\" etc/parameters.yml"
                    sh "sudo ./bin/phpunit -c phpunit.xml.dist --testsuite PHP_Client_Unit_Test_1_8 --log-junit build/logs/phpunit_integration.xml"
                }
            }

            if ("1.7" == pimVersion) {
                docker.image("akeneo/php:${phpVersion}").inside("--link akeneo-pim:akeneo-pim -v /var/run/docker.sock:/var/run/docker.sock -v /usr/bin/docker:/usr/bin/docker -w /home/docker/client") {
                    sh "sudo ./bin/phpunit -c phpunit.xml.dist --testsuite PHP_Client_Unit_Test_1_7 --log-junit build/logs/phpunit_integration.xml"
                }
            }
        } finally {
            sh "docker stop \$(docker ps -a -q) || true"
            sh "docker rm \$(docker ps -a -q) || true"
            sh "docker volume rm \$(docker volume ls -q) || true"

            sh "find build/logs/ -name \"*.xml\" | xargs sed -i \"s/testcase name=\\\"/testcase name=\\\"[php-${phpVersion}-${client.replaceAll('/', '-')}-${psrImplem.replaceAll('/', '-')}] /\""
            junit "build/logs/*.xml"

            deleteDir()
        }
    }
}
