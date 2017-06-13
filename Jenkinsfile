#!groovy

def launchUnitTests = "yes"
def launchIntegrationTests = "yes"
def clients = ["php-http/guzzle5-adapter", "php-http/guzzle6-adapter"]
def pimVersion = "1.7"


def clientConfig = [
    "php-http/guzzle6-adapter": ["phpVersion": ["5.6", "7.0", "7.1"], "psrImplem": ["guzzlehttp/psr7"]],
    "php-http/guzzle5-adapter": ["phpVersion": ["5.6"], "psrImplem": ["guzzlehttp/psr7", "zendframework/zend-diactoros", "slim/slim"]],
]

stage("Checkout") {
    milestone 1
    if (env.BRANCH_NAME =~ /^PR-/) {
        userInput = input(message: 'Launch tests?', parameters: [
            choice(choices: 'yes\nno', description: 'Run unit tests and code style checks', name: 'launchUnitTests'),
            choice(choices: 'yes\nno', description: 'Run integration tests', name: 'launchIntegrationTests'),
            string(defaultValue: 'php-http/guzzle5-adapter,php-http/guzzle6-adapter', description: 'Clients used to run integration tests (comma separated values)', name: 'clients'),
            // choice(choices: '1.7', description: 'PIM version to run integration tests with', name: 'pimVersion'),
        ])

        launchUnitTests = userInput['launchUnitTests']
        launchIntegrationTests = userInput['launchIntegrationTests']
        clients = userInput['clients'].tokenize(',')
        // pimVersion = userInput['pimVersion']
    }
    milestone 2

    node {
        deleteDir()
        checkout scm
        stash "php-api-client"

       checkout([$class: 'GitSCM',
         branches: [[name: '1.7']],
         userRemoteConfigs: [[credentialsId: 'github-credentials', url: 'https://github.com/akeneo/pim-community-dev.git']]
       ])

       stash "pim_community_dev_${pimVersion}"
    }



    checkouts = [:];
    checkouts["pim_community_dev_${pimVersion}"] = {runCheckoutPim("5.6", pimVersion)};

    for (client in clients) {
        for (phpVersion in clientConfig.get(client).get("phpVersion")) {
            for (psrImplem in clientConfig.get(client).get("psrImplem")) {
                def currentVersion = phpVersion
                def currentClient = client

                checkouts["${client.replaceAll('/', '-')}-${phpVersion}"] = {runCheckoutClient(currentVersion, currentClient, "guzzlehttp/psr7")}
            }
        }
    }

    parallel checkouts
}

if (launchUnitTests.equals("yes")) {
    stage("Unit tests and Code style") {
        def tasks = [:]

        tasks["php-cs-fixer"] = {runPhpCsFixerTest()}

        for (phpVersion in clientConfig.get("php-http/guzzle6-adapter").get("phpVersion") {
            def currentVersion = phpVersion

            tasks["phpspec-${phpVersion}"] = {runPhpSpecTest(currentVersion)}
        }

        parallel tasks
    }
}

if (launchIntegrationTests.equals("yes")) {
    stage("Integration tests") {
        def tasks = [:]

        for (client in clients) {
            for (phpVersion in clientConfig.get(client).get("phpVersion")) {
                for (psrImplem in clientConfig.get(client).get("psrImplem")) {
                    def currentClient = client
                    def currentPsrImplem =psrImplem
                    def currentPhpVersion = phpVersion

                    tasks["phpunit-${phpVersion}-${client}"] = {runIntegrationTest(currentPhpVersion, currentClient, currentPsrImplem, pimVersion)}
                }
            }
        }

        parallel tasks
    }
}

def runCheckoutPim(phpVersion, pimVersion) {
    node('docker') {
        deleteDir()
        try {
            docker.image("carcel/php:${phpVersion}").inside("-v /home/akeneo/.composer:/home/docker/.composer") {
                unstash "pim_community_dev_${pimVersion}"

                sh "composer require \"akeneo/catalogs\":\"dev-API-175\" --ignore-platform-reqs --optimize-autoloader --no-interaction --no-progress --prefer-dist"
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

def runCheckoutClient(phpVersion, client, psrImplem) {
    node('docker') {
        deleteDir()
        try {
            docker.image("carcel/php:${phpVersion}").inside("-v /home/akeneo/.composer:/home/docker/.composer") {
                unstash "php-api-client"

                sh "composer require ${client} ${psrImplem}"
                sh "composer update --optimize-autoloader --no-interaction --no-progress --prefer-dist"

                sh "cp etc/parameters.yml.dist etc/parameters.yml"

                stash "php-api-client_${client.replaceAll('/', '-')}_${psrImplem.replaceAll('/', '-')}_php-${phpVersion}"
            }
        } finally {
            sh "docker stop \$(docker ps -a -q) || true"
            sh "docker rm \$(docker ps -a -q) || true"
            sh "docker volume rm \$(docker volume ls -q) || true"

            deleteDir()
        }
    }
}

def runPhpCsFixerTest() {
    node('docker') {
        deleteDir()
        try {
            docker.image("carcel/php:7.1").inside() {
                unstash "php-api-client_php-http-guzzle6-adapter_php-7.1"

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

def runPhpSpecTest(phpVersion) {
    node('docker') {
        deleteDir()
        try {
            docker.image("carcel/php:${phpVersion}").inside() {
                unstash "php-api-client_php-http-guzzle6-adapter_guzzlehttp_psr7_php-${phpVersion}"

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

def runIntegrationTest(phpVersion, client, psrImplem, pimVersion) {
    node('docker') {
        deleteDir()
        try {
            sh "docker run --name mysql -e MYSQL_ROOT_PASSWORD=root -e MYSQL_USER=akeneo_pim -e MYSQL_PASSWORD=akeneo_pim -e MYSQL_DATABASE=akeneo_pim -d mysql:5.5 --sql-mode=ERROR_FOR_DIVISION_BY_ZERO,NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"

            dir('pim') {
                unstash "pim_community_dev_${pimVersion}"
                sh "docker run --name akeneo-pim --link mysql:mysql -v \$(pwd):/home/docker/pim -d carcel/akeneo-apache:php-5.6"
                sh "docker exec akeneo-pim pim/app/console pim:install -e prod"
            }

            docker.image("carcel/php:${phpVersion}").inside("--link akeneo-pim:akeneo-pim -v /var/run/docker.sock:/var/run/docker.sock -v /usr/bin/docker:/usr/bin/docker") {
                unstash "php-api-client_${client.replaceAll('/', '-')}_${psrImplem.replaceAll('/', '-')}_php-${phpVersion}"
                sh "mkdir -p build/logs/"
                sh "sudo ./bin/phpunit -c phpunit.xml.dist --log-junit build/logs/phpunit_integration.xml"
            }
        } finally {
            sh "docker stop \$(docker ps -a -q) || true"
            sh "docker rm \$(docker ps -a -q) || true"
            sh "docker volume rm \$(docker volume ls -q) || true"

            sh "find build/logs/ -name \"*.xml\" | xargs sed -i \"s/testcase name=\\\"/testcase name=\\\"[php-${phpVersion}-${client.replaceAll('/', '-')}] /\""
            junit "build/logs/*.xml"

            deleteDir()
        }
    }
}
