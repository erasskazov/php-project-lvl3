start:
	php artisan serve --host 0.0.0.0

setup:
	composer install
	cp -n .env.example .env || true
	php artisan key:gen --ansi
	touch database/database.sqlite
	php artisan migrate
	npm install
	npm run build

migrate:
	php artisan migrate --seed

lint:
	composer exec phpcs

test:
	php artisan test