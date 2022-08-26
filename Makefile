.DEFAULT_GOAL := help
.PHONY: help

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-50s\033[0m %s\n", $$1, $$2}'

code_check: ## Run php-cs-fixer and psalm
	symfony console doctrine:schema:val \
	&& symfony php ./vendor/bin/php-cs-fixer fix src \
    && symfony php ./vendor/bin/php-cs-fixer fix tests \
    && symfony php ./vendor/bin/psalm

run_tests: ## Run tests
	APP_ENV=test symfony console ca:cl --env=test && \
	APP_ENV=test symfony console doctrine:database:drop --force --env=test --if-exists && \
    APP_ENV=test symfony console doctrine:database:create --env=test && \
    APP_ENV=test symfony console doctrine:schema:update --env=test --force && \
    APP_ENV=test symfony php ./vendor/bin/phpunit

run_tests_stop_on_error_failure: ## Run tests and stop on error/failure
	APP_ENV=test symfony console ca:cl --env=test && \
	APP_ENV=test symfony console doctrine:database:drop --force --env=test --if-exists && \
    APP_ENV=test symfony console doctrine:database:create --env=test && \
    APP_ENV=test symfony console doctrine:schema:update --env=test --force && \
    APP_ENV=test symfony php ./vendor/bin/phpunit --stop-on-error --stop-on-failure

open_tableplus_mysql: ## Open db in tableplus
	open "mysql://root:wise-pokemon@127.0.0.1:3388/wise-pokemon?statusColor=007F3D&enviroment=local&name=wise-pokemon.test&tLSMode=1&usePrivateKey=false&safeModeLevel=0&advancedSafeModeLevel=0"

start_project: ## Start project
	make docker_up && symfony proxy:start && make server_start

docker_up: ## Start docker
	docker-compose up --force-recreate -d

docker_down: ## Stop docker
	docker-compose down

server_start: ## Start symfony server
	symfony server:start

migrations_generate: ## Create blank migration
	symfony console do:mig:gen

migrations_diff: ## Diff db vs schema
	symfony console do:mig:diff

migrations_migrate: ## Migrate db
	symfony console do:mig:mig -n
