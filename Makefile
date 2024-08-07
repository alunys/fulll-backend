MAKEFLAGS += --no-print-directory --silent
DC := docker compose
DCE := $(DC) exec
DCR := $(DC) run --rm


vendor: composer.lock
	$(DCR) --no-deps php composer install

behat: vendor
	$(DCR) --no-deps php vendor/behat/behat/bin/behat $(ARGS)

docker-stop:
	$(DC) stop

docker-down:
	$(DC) down --remove-orphans || true

docker-fix-rights:
	if ! $(DC) exec php chown -R $(shell id -u):$(shell id -g) .; then $(DCR) --no-deps php chown -R $(shell id -u):$(shell id -g) .; fi

docker-logs:
	$(DC) logs -f
