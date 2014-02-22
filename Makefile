SHELL = /bin/sh

composer:
	./composer.phar update

generate-doc:
	./vendor/bin/phpdoc.php -d ./src -t ./doc/api/ --title='php-doc'

perform-tests:
	./vendor/bin/phpunit ./tests
