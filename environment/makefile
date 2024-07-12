.PHONY: help
# Make stuff

-include .env

export DOCKER_BUILDKIT=1
export COMPOSE_DOCKER_CLI_BUILD=1

.DEFAULT_GOAL := help

ARTIFACTS_DIRECTORY := "./artifacts"

CURRENT_PATH :=${abspath .}

SHELL_CONTAINER_NAME := $(if $(c),$(c),php)
BUILD_TARGET := $(if $(t),$(t),development)

help: ## Help.
	@grep -E '^[a-z_A-Z-]+:.*?## .*$$' Makefile | awk 'BEGIN {FS = ":.*?## "}; {printf "[32m%-27s[0m %s\n", $$1, $$2}'

build: ## Build images.
	@make create_project_artifacts
	@docker-compose -f docker-compose.$(BUILD_TARGET).yml build

shell: ## Internal image bash command line.
	@if [[ -z `docker ps | grep ${SHELL_CONTAINER_NAME}` ]]; then \
		echo "${SHELL_CONTAINER_NAME} is NOT running (make start)."; \
	else \
		docker-compose -f docker-compose.$(BUILD_TARGET).yml exec $(SHELL_CONTAINER_NAME) /bin/ash; \
	fi
	
start: ## Start previously built application images.
	@make create_project_artifacts
	@make start_postgres
	@make start_php
	@make start_nginx

start_php: ## Start php image.
	@if [[ -z `docker ps | grep $(PROJECT_NAME)/php` ]]; then \
		docker-compose -f docker-compose.$(BUILD_TARGET).yml up -d php; \
	else \
		echo "Php is running."; \
	fi

start_nginx: ## Start nginx image.
	@if [[ -z `docker ps | grep $(PROJECT_NAME)/nginx` ]]; then \
		docker-compose -f docker-compose.$(BUILD_TARGET).yml up -d nginx; \
	else \
		echo "Nginx is running."; \
	fi

start_postgres: ## Start postgres image.
	@if [[ -z `docker ps | grep $(PROJECT_NAME)/postgres` ]]; then \
		PROJECT_NAME=$(PROJECT_NAME) docker-compose -f docker-compose.$(BUILD_TARGET).yml up -d postgres; \
	else \
		echo "Postgres is running."; \
	fi

stop: ## Stop all images.
	@docker-compose -f docker-compose.$(BUILD_TARGET).yml stop

create_project_artifacts:
	mkdir -p ./artifacts/php
