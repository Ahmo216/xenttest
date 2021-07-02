install: copy-files substitude-user-and-group up composer-deps npm-install npm-dev
up: docker-up
down: docker-down
restart: docker-down docker-up

init: docker-down-clear docker-pull docker-build
init-rebuild: docker-down-clear docker-pull docker-rebuild

copy-files:
	cp docker-compose.override.dist.yml docker-compose.override.yml
	cp captainhook.config.dist.json captainhook.config.json
	cp .env.example .env

substitude-user-and-group:
	sed -i -e "s/\$${UID}/$$(id -u)/" docker-compose.override.yml
	sed -i -e "s/\$${GID}/$$(id -g)/" docker-compose.override.yml

composer-deps:
	$(info *****************************************)
	$(info ***** Relax it can take a while ... *****)
	$(info *****************************************)
	docker-compose run --rm devbox sh -c "composer --no-scripts install"

post-install:
	docker-compose run --rm devbox composer run-script post-autoload-dump


npm-install:
	$(info *****************************************)
	$(info ***** Relax it can take a while ... *****)
	$(info *****************************************)
	docker-compose run --rm devbox sh -c "npm install"

npm-dev:
	docker-compose run --rm devbox sh -c "npm run dev"


docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-build:
	docker-compose build

docker-rebuild:
	docker-compose build --no-cache

docker-pull:
	docker-compose pull
