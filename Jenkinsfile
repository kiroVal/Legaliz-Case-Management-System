pipeline {
    agent any

    environment {
        APP_NAME = "syntaxxedlcms"
        DOCKER_IMAGE = "michael877/${APP_NAME}"
        DOCKER_TAG = "latest"
        
        // *** NEW: Define the absolute path to the Composer executable (Windows path) ***
        // !! CRITICAL: YOU MUST REPLACE THIS PLACEHOLDER PATH !!
        COMPOSER_BIN = "C:/path/to/composer/composer.phar" 
        // Example Windows Path format: "C:/ProgramData/ComposerSetup/bin/composer.phar"
    }

    stages {
        stage('Checkout') {
            steps {
                git branch: 'main', url: 'https://github.com/kiroVal/Legaliz-Case-Management-System.git'
            }
        }

        stage('Install Dependencies') {
            steps {
                echo 'Installing PHP dependencies...'
                sh '''
                    if [ -f composer.json ]; then
                        # Use the absolute path defined in the environment
                        "${COMPOSER_BIN}" install --no-interaction --prefer-dist
                    else
                        echo "No composer.json found, skipping composer install"
                    fi
                '''
            }
        }

        stage('Unit Test') {
            steps {
                echo 'Running PHP Unit tests...'
                sh '''
                    if [ -f phpunit.xml ] || [ -f phpunit.xml.dist ]; then
                        ./vendor/bin/phpunit --testdox
                    else
                        echo "No PHPUnit config found, skipping tests"
                    fi
                '''
            }
        }

        stage('Start PHP Server') {
            steps {
                echo 'Starting PHP built-in server...'
                sh 'nohup php -S 127.0.0.1:8081 > server.log 2>&1 &'
                sleep 5
            }
        }

        stage('Integration Test') {
            steps {
                echo 'Running integration tests...'
                sh 'curl -I http://127.0.0.1:8081 || exit 1'
            }
        }

        stage('Build Docker Image') {
            steps {
                echo 'Building Docker image...'
                sh 'docker build -t ${DOCKER_IMAGE}:${DOCKER_TAG} .'
            }
        }

        stage('Push Docker Image') {
            steps {
                withCredentials([usernamePassword(credentialsId: 'dockerhub-credentials', usernameVariable: 'DOCKER_USER', passwordVariable: 'DOCKER_PASS')]) {
                    sh '''
                        echo "$DOCKER_PASS" | docker login -u "$DOCKER_USER" --password-stdin
                        docker push ${DOCKER_IMAGE}:${DOCKER_TAG}
                    '''
                }
            }
        }
    }

    post {
        always {
            echo "Cleaning up..."
            // Using taskkill for Windows agent (Taskkill uses the Image Name, php.exe)
            sh "taskkill /F /IM php.exe || true" 
            echo "Pipeline finished."
        }
    }
}
