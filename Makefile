default: build

build:
	bin/build.sh

clean:
	@if [ -d ".git" ]; then git clean -xdf; fi

create-composer-auth:
	bin/composer-auth.sh

remove-composer-auth:
	rm -f auth.json

run:
	docker-compose up

bash:
	docker-compose exec wordpress bash

test:
	composer test
