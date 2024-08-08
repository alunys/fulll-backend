MAKEFLAGS += --no-print-directory --silent
include Makefile.docker.mk
include Makefile.qa.mk

vendor: composer.lock
	$(DCR) --no-deps php composer install

doctrine_migrations: vendor
	$(DCR) php bin/console doctrine:migrations:migrate --no-interaction

shell-php: vendor
	$(DCR) php bash

fixtures:
	$(SF_CONSOLE) doctrine:database:drop --force --if-exists
	$(SF_CONSOLE) doctrine:database:create
	$(SF_CONSOLE) doctrine:migrations:migrate --no-interaction
	$(SF_CONSOLE) doctrine:fixtures:load --append --no-interaction
