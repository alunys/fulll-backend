DC := docker compose
DCE := $(DC) exec
DCR := $(DC) run --rm
SF_CONSOLE := $(DCE) php bin/console

docker-stop:
	$(DC) stop

docker-down:
	$(DC) down --remove-orphans || true

docker-fix-rights:
	if ! $(DC) exec php chown -R $(shell id -u):$(shell id -g) .; then $(DCR) --no-deps php chown -R $(shell id -u):$(shell id -g) .; fi

docker-logs:
	$(DC) logs -f
