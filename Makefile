.PHONY: help build up down restart shell composer artisan pint phpstan phpstan-tests rector test test-parallel test-coverage ci fresh

# Docker Compose command
DC = docker compose
# Container name
APP = app

help: ## Show this help
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

# =============================================================================
# Docker Commands
# =============================================================================

build: ## Build Docker containers
	$(DC) build

up: ## Start Docker containers
	$(DC) up -d

down: ## Stop Docker containers
	$(DC) down

restart: down up ## Restart Docker containers

logs: ## Show Docker logs
	$(DC) logs -f

shell: ## Access app container shell
	$(DC) exec $(APP) bash

# =============================================================================
# Composer Commands
# =============================================================================

composer: ## Run composer command (usage: make composer c="install")
	$(DC) run --rm $(APP) composer $(c)

# =============================================================================
# Artisan Commands
# =============================================================================

artisan: ## Run artisan command (usage: make artisan c="migrate")
	$(DC) exec $(APP) php artisan $(c)

migrate: ## Run database migrations
	$(DC) exec $(APP) php artisan migrate

fresh: ## Fresh migrate with seed
	$(DC) exec $(APP) php artisan migrate:fresh --seed

seed: ## Run database seeders
	$(DC) exec $(APP) php artisan db:seed

# =============================================================================
# Code Quality Tools
# =============================================================================

pint: ## Run Laravel Pint (code formatter)
	$(DC) exec $(APP) ./vendor/bin/pint

pint-check: ## Run Laravel Pint in check mode (no changes)
	$(DC) exec $(APP) ./vendor/bin/pint --test

phpstan: ## Run PHPStan for application code
	$(DC) exec $(APP) ./vendor/bin/phpstan analyse --configuration=phpstan.neon

phpstan-tests: ## Run PHPStan for tests
	$(DC) exec $(APP) ./vendor/bin/phpstan analyse --configuration=phpstan-tests.neon

rector: ## Run Rector (code refactoring)
	$(DC) exec $(APP) ./vendor/bin/rector process

rector-dry: ## Run Rector in dry-run mode (no changes)
	$(DC) exec $(APP) ./vendor/bin/rector process --dry-run

# =============================================================================
# Testing
# =============================================================================

test: ## Run PHPUnit tests
	$(DC) exec $(APP) php artisan test

test-parallel: ## Run PHPUnit tests in parallel
	$(DC) exec $(APP) ./vendor/bin/paratest --processes=auto

test-coverage: ## Run PHPUnit tests with coverage
	$(DC) exec $(APP) php artisan test --coverage

test-unit: ## Run only unit tests
	$(DC) exec $(APP) php artisan test --testsuite=Unit

test-feature: ## Run only feature tests
	$(DC) exec $(APP) php artisan test --testsuite=Feature

# =============================================================================
# CI Pipeline (Run all checks)
# =============================================================================

ci: pint-check phpstan phpstan-tests test-parallel ## Run full CI pipeline

ci-fix: pint rector ## Fix code style and apply refactoring

# =============================================================================
# Setup
# =============================================================================

setup: build up install ## Initial project setup
	$(DC) exec $(APP) cp -n .env.example .env || true
	$(DC) exec $(APP) php artisan key:generate
	$(DC) exec $(APP) php artisan migrate
	@echo "Setup complete! Access the application at http://localhost:8080"
