start:
	php artisan serve

api:
	xdg-open "http://127.0.0.1:8000/"

test:
	./vendor/bin/phpunit tests/Feature/ApiTest.php --testdox --exclude-group=incomplete

