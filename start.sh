ln -sf `pwd`/config/#{fetch(:stage)}.php `pwd`/config/config.ph
curl -sS https://getcomposer.org/installer | php
mv composer.phar composer
./composer selfupdate -q
./console migrations:migrate --no-interaction
./console orm:generate-proxies
./console bolt:demo-runner