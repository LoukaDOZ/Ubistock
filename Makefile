build:
	docker compose build

start:
	docker compose up -d

stop:
	docker compose down

clean: stop db-clean
	docker rmi ubistock-app
	docker rmi ubistock-api
	docker rmi ubistock-proxy

db-clean:
	sudo rm -rf ./db/data/