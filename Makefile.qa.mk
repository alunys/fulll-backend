QA := $(DCR) qa
DCR_TEST := $(DCR) -e APP_ENV=test
DCR_TEST_PHP := $(DCR_TEST) php
SF_CONSOLE_TEST := $(DCR_TEST_PHP) bin/console

tests: cs-fixer_dry-run phpstan psalm rector-dry-run behat

cc-test:
	$(DCR_TEST_PHP) bin/console cache:clear

_database_test:
	$(DC) up -d
	$(SF_CONSOLE_TEST) doctrine:database:drop --force --if-exists
	$(SF_CONSOLE_TEST) doctrine:database:create
	$(SF_CONSOLE_TEST) doctrine:migrations:migrate --no-interaction
	$(SF_CONSOLE_TEST) doctrine:fixtures:load --append --no-interaction

behat: vendor _database_test cc-test
	$(DCR_TEST_PHP) vendor/bin/behat $(ARGS)

cs-fixer:
	$(QA) php-cs-fixer fix --verbose

cs-fixer_dry-run:
	$(QA) php-cs-fixer fix --diff --verbose --dry-run

phpstan: vendor
	$(QA) phpstan analyze

psalm: vendor
	$(QA) psalm

rector: vendor
	$(QA) bin/console cache:clear
	$(QA) rector
	$(MAKE) docker-fix-rights

rector-dry-run: vendor
	$(QA) bin/console cache:clear
	$(QA) rector --dry-run
	$(MAKE) docker-fix-rights
