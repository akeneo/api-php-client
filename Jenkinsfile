#!groovy

def supportedPhpVersions = ["5.6", "7.0", "7.1"]

def launchUnitTests = "yes"
def launchIntegrationTests = "no"
def clients = ["php-http/guzzle5-adapter", "php-http/guzzle6-adapter"]
// def pimVersion = "1.7"

stage("Checkout") {
    milestone 1
    if (env.BRANCH_NAME =~ /^PR-/) {
        userInput = input(message: 'Launch tests?', parameters: [
            choice(choices: 'yes\nno', description: 'Run unit tests and code style checks', name: 'launchUnitTests'),
            // choice(choices: 'yes\nno', description: 'Run integration tests', name: 'launchIntegrationTests'),
            string(defaultValue: 'php-http/guzzle5-adapter,php-http/guzzle6-adapter', description: 'Clients used to run integration tests (comma separated values)', name: 'clients'),
            // choice(choices: '1.7', description: 'PIM version to run integration tests with', name: 'pimVersion'),
        ])

        launchUnitTests = userInput['launchUnitTests']
        // launchIntegrationTests = userInput['launchIntegrationTests']
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

       stash "pim_community_dev"
    }

    checkouts = [:];
    for (client in clients) {
        for (phpVersion in supportedPhpVersions) {
            def currentVersion = phpVersion
            def currentClient = client

            checkouts["${client.replaceAll('/', '-')}-${phpVersion}"] = {runCheckout(currentVersion, currentClient)}
        }
    }

    parallel checkouts
}

if (launchUnitTests.equals("yes")) {
    stage("Unit tests and Code style") {
        def tasks = [:]

        tasks["php-cs-fixer"] = {runPhpCsFixerTest()}

        for (phpVersion in supportedPhpVersions) {
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
            for (phpVersion in supportedPhpVersions) {
                def currentVersion = phpVersion
                def currentClient = client

                tasks["phpunit-${phpVersion}"-${client}] = {runIntegrationTest(currentVersion, currentClient)}
            }
        }

        // parallel tasks
    }
}

def runCheckout(phpVersion, client) {
    node('docker') {
        deleteDir()
        try {
            docker.image("carcel/php:${phpVersion}").inside("-v /home/akeneo/.composer:/home/docker/.composer") {
                unstash "php-api-client"

                sh "composer require ${client}"
                sh "composer update --optimize-autoloader --no-interaction --no-progress --prefer-dist"

                stash "php-api-client_${client.replaceAll('/', '-')}_php-${phpVersion}"
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
                unstash "php-api-client_php-http-guzzle6-adapter_php-${phpVersion}"

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

def runIntegrationTest(phpVersion, client) {
    node('docker') {
        deleteDir()
        try {
            docker.image("carcel/php:${phpVersion}").inside() {
                unstash "php-api-client_${client.replaceAll('/', '-')}_php-${phpVersion}"

                sh "mkdir -p build/logs/"

                sh "./bin/phpunit -c app/phpunit.xml.dist --log-junit build/logs/phpunit_integration.xml"
            }
        } finally {
            sh "docker stop \$(docker ps -a -q) || true"
            sh "docker rm \$(docker ps -a -q) || true"
            sh "docker volume rm \$(docker volume ls -q) || true"

            sh "find build/logs/ -name \"*.xml\" | xargs sed -i \"s/testcase name=\\\"/testcase name=\\\"[php-${phpVersion}-${client}] /\""
            junit "build/logs/*.xml"

            deleteDir()
        }
    }
}
