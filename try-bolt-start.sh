ln -sf `pwd`/config/production.php `pwd`/config/config.php
./console migrations:migrate --no-interaction
./console orm:generate-proxies
./console bolt:demo-runner