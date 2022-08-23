web: heroku-php-apache2 public/
release: php bin/console cache:clear && php bin/console cache:warmup && php bin/console do:mi:mi --allow-no-migration -n
