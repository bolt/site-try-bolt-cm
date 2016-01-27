## Try Bolt Application Repository

This is an application that allows users to launch a test version of Bolt using a theme of their choice.

The system currently powers http://try.bolt.cm

### How to get a development version running.

After cloning this repo you'll need to setup a development DB connection. This app uses MySQL and the connection
settings are in `config/development.php` you will also need to create a symlink from `config/config.php` to 
`config/development.php`

The bootstrap file is inside the `public` folder so you will need to set this as the root of your Apache 
VirtualHost or alternatively start a PHP server with `public/index.php` as the bootstrap file.

### The external Docker service.

Note that this repo does not contain the code to actually launch the Docker service, instead a launch is triggered
by connecting to an additional server. If you want to test this part of the functionality you will need an SSH
account on the Docker server. Please contact the project maintainers for more information.

For reference this is triggered via the `startJob` method in `src/Command/DemoRunner`.

### How to deploy:

Note that you'll need to add your SSH key to the deployment server for this to work, there is no password auth
supported. You will also need to get a copy of `config/env.php` which for security reasons is not kept in the 
public repo.

Deployment is managed via Capistrano, the steps it takes automatically is to

1. Push the code
2. Run `composer install` on the remote machine 
3. Symlink the configuration to run off `production.php`
4. Copy up the environment config file from `config.php`
5. Kills and existing instances of `try-bolt-start.sh` and launches a new version as a background process.


Before you can deploy you'll need to make sure you have Capistrano and it's dependencies.
1. run `bundle install`
2. to deploy run `cap production deploy`

Deploy for production occurs from master branch unless modified in `Capfile`.
