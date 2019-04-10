install:
	composer install
serve:
	sass public/scss/main.scss public/css/main.css --style compressed
	php bin/console server:run
wscss:
	sass --watch public/scss/main.scss public/css/main.css --style compressed