# --------------------------
# Laravel Docker Makefile
# --------------------------

# Default values
PHP_SERVICE = php
COMPOSE = docker-compose

# --------------------------
# Build and start containers
# --------------------------
# Normal up (reuse images)

# ################### The Main Command to use every time ###################
up: down docker-up composer-install migrate npm-install npm-dev
# ###########################################################################

docker-up:
	$(COMPOSE) up -d

# Full rebuild (rarely)
rebuild: down
	$(COMPOSE) up -d --build

# --------------------------
# Stop and remove containers
# --------------------------
down:
	$(COMPOSE) down

# --------------------------
# Install composer dependencies
# --------------------------
composer-install:
	$(COMPOSE) exec $(PHP_SERVICE) composer install

# --------------------------
# Generate Laravel app key
# --------------------------
key-generate:
	$(COMPOSE) exec $(PHP_SERVICE) php artisan key:generate

# --------------------------
# Run migrations
# --------------------------
migrate:
	$(COMPOSE) exec $(PHP_SERVICE) php artisan migrate

# --------------------------
# Full setup: up + install + key + migrate
# --------------------------
setup: up composer-install key-generate migrate npm-install npm-dev
	@echo "✅ Laravel setup complete!"


# -------------------
# Node / assets
# -------------------
npm-install:
	docker-compose exec node npm install

npm-dev:
	docker-compose exec node npm run dev

npm-build:
	docker-compose exec node npm run build
