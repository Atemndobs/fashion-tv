start:
	php artisan serve

home:
	xdg-open "http://127.0.0.1:8000/"

api:
	xdg-open "http://127.0.0.1:8000/api?q="

test:
	./vendor/bin/phpunit tests/Feature/ApiTest.php --testdox --exclude-group=incomplete

