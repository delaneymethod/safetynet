#!/usr/bin/env groovy

node('master') {
	try {
		stage('checkout') {
			git url: 'git@gitlab.com:mkelso/safetynet.git'
		}
		
		stage('build') {
			sh './development up -d'
			sh './development composer install --prefer-dist'
			sh 'cp .env.example .env'
			sh './development artisan key:generate'
			sh 'sed -i "s/DB_HOST=.*/DB_HOST=mariadb/" .env'
			sh 'sed -i "s/REDIS_HOST=.*/REDIS_HOST=redis/" .env'
			sh 'sed -i "s/CACHE_DRIVER=.*/CACHE_DRIVER=redis/" .env'
			sh 'sed -i "s/SESSION_DRIVER=.*/SESSION_DRIVER=redis/" .env'
		}
		
		stage('test') {
			sh 'APP_ENV=testing ./development test'
		}
		
		sh 'git rev-parse --abbrev-ref HEAD > GIT_BRANCH'
		
		git_branch = readFile('GIT_BRANCH').trim()
		
		if (git_branch == 'master') {
			stage('release') {
				sh './docker/build'
			}
			
			stage('deployment') {
				// IP address of Production server
				sh 'ssh -i ~/.ssh/id_sd root@138.68.183.131 /opt/deploy'
			}
		}
	} catch(error) {
		throw error
	} finally {
		sh './development down'
	}
}
