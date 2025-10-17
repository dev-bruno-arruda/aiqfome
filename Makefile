.DEFAULT_GOAL := help

help:
	@echo "Please choose what you want to do:"
	@echo "  make dup: start docker container"
	@echo "  make ddw: stop docker container"
	@echo "  make drs: restart docker container"
	@echo "  make dci: composer install inside container"
	@echo "  make dcu: composer update inside container"
	@echo "  make mysql: go into the mysql container"
	@echo "  make php: go into the php container"
	@echo "  make test: run tests with code coverage"
	@echo "  make lvtest: run Laravel tests"
	@echo "  make mig: run migrations with seed"
	@echo "  make rmig: run rollback migrations"
	@echo "  make clear: clear Laravel caches"
	@echo "  make job: run queue worker"
	@echo "  make link: create storage link"
	@echo "  make swagger: generate Swagger documentation"

up:
	cp .env.example .env; ./vendor/bin/sail up -d --build

dup:
	./vendor/bin/sail up -d

ddw:
	./vendor/bin/sail down --volumes

drs:
	./vendor/bin/sail down --volumes && ./vendor/bin/sail up -d

dci: check_containers
	./vendor/bin/sail composer install

dcu: check_containers
	./vendor/bin/sail composer update

mysql: check_containers
	./vendor/bin/sail mysql

php: check_containers
	./vendor/bin/sail shell

test: check_containers
	./vendor/bin/sail artisan migrate:fresh --env=testing
	./vendor/bin/sail artisan test --coverage-html coverage
	@echo "Tests and code coverage generated in the coverage directory."

lvtest: check_containers
	./vendor/bin/sail artisan test

mig: check_containers
	./vendor/bin/sail artisan migrate:fresh --seed

rmig: check_containers
	./vendor/bin/sail artisan migrate:rollback

clear: check_containers
	./vendor/bin/sail artisan cache:clear && ./vendor/bin/sail artisan config:clear && ./vendor/bin/sail artisan route:clear && ./vendor/bin/sail artisan view:clear

job: check_containers
	./vendor/bin/sail artisan queue:work

link: check_containers
	./vendor/bin/sail artisan storage:link

swagger: check_containers
	./vendor/bin/sail artisan l5-swagger:generate

check_containers:
	@docker ps | grep aiqfome-laravel.test-1 > /dev/null || (echo "Starting containers..." && make dup)
