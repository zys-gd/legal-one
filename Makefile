install: ## install project
	docker compose build
	docker compose run --rm php composer i
run: ## run application
	docker compose up -d
