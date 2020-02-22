include .env

pull: 
	docker-compose pull

dbuild: 
	docker-compose build

#make up 
#make up s=service
#make up a="-f docker-compose.yml -f docker-compose.override.yml"
up:
	docker-compose $(a) up -d $(s)

down: 
	docker-compose down

start:
	docker-compose $(a) start
	
stop:
	docker-compose $(a) stop

restart:
	docker-compose restart $(s)

ls:
	docker-compose ps 

vol:
	docker volume ls

log:
	docker logs $(PROJECT_NAME)_drupal
	
#See docker-compose rm
#make rm a="--help"
rm: 
	docker system prune $(a) --all

#Container commands
denter:
	docker-compose exec drupal sh $(c)

#make jrun c="echo hello world"
drun:
	docker-compose run drupal $(c)

# #Usage
# #make composer a="require drupal/redis"
composer:
	docker-compose exec drupal composer $(a)

drush:
	docker-compose run drush $(a)

#make dbdump > drupal.sql
dbdump:
	docker-compose exec postgres pg_dump -C -U user -w drupal