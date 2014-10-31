require 'rubygems'
require 'rake'
require 'bundler/setup'
require 'net/http'
require 'json'

# Load DSL and Setup Up Stages
require 'capistrano/setup'
require 'capistrano/docker'

set :namespace,         "bolt"
set :application,       "try"
set :password,          "bolt30080"
set :stage,             "production" ### Default stage
set :deploy_path,        "domains/try.bolt.cm/private_html_real"
set :build_commands,    [
    'composer install --no-dev --prefer-dist --optimize-autoloader',
    'cp ../../config/env.php ./config/'
]
set :start_commands,    [
    "ln -sf `pwd`/config/#{fetch(:stage)}.php `pwd`/config/config.php",
    "curl -sS https://getcomposer.org/installer | php",
    "mv composer.phar composer",
    "./composer selfupdate -q",
    "./console migrations:migrate --no-interaction",
    "./console orm:generate-proxies",
    "./console bolt:demo-runner"
]



task :production do
    set :branch,        "master"
    server 'bolt.cm', user: 'bolt', roles: %w{host}
end

namespace :deploy do
    
    desc "Builds project locally ready for deploy."
    task :build do
        puts "Preparing local build: note, only code commited to your local Git repository will be included."
       
        run_locally do
            begin
                capture "ls tmp/build/.git"
            rescue
                execute "mkdir -p tmp/build"
                execute "cd tmp/build && git clone ../../ ."
            end
            
            commit = capture "git rev-parse HEAD"
            execute "cd tmp/build/ && git fetch && git checkout -f #{commit}"

            fetch(:build_commands).each do |command|
                execute "cd tmp/build && "+command       
            end
            
            execute "echo '"+fetch(:start_commands, []).join(';\n') +"' > tmp/build/try-bolt-start.sh"
            execute "chmod +x tmp/build/try-bolt-start.sh"        
        
        end   
        
        
    end
    
    desc "Updates the code on the remote container"
    task :push do
        on roles :host do |host|
            info " Running Rsync to: #{host.user}@#{host.hostname}"
            run_locally do
                execute "rsync -rupl --exclude '.git' tmp/build/* #{host.user}@#{host.hostname}:#{fetch(:deploy_path)}/"
            end
        end
    end
    
    desc "Updates the code on the remote container"
    task :start do
        on roles :host do |host|
            begin execute "pkill -f 'try-bolt-start.sh'" rescue nil end 
            begin execute "pkill -f 'demo-runner'" rescue nil end 
            execute "cd #{fetch(:deploy_path)}; ((nohup ./try-bolt-start.sh &>/dev/null) &)" 
        end
    end
end

Rake::Task[:deploy].clear_actions
desc 'Deploy a new release.'
task :deploy do
  set(:deploying, true)
  %w{ build push start finished }.each do |task|
    invoke "deploy:#{task}"
  end
end
task default: :deploy
invoke 'load:defaults'

