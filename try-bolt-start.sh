ln -sf `pwd`/config/#{fetch(:stage)}.php `pwd`/config/config.ph
./console migrations:migrate --no-interaction
./console orm:generate-proxies
./console bolt:demo-runner