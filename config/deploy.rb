# general settings
default_run_options[:pty] = true
set :application, "Haltes"

# source control settings
set :scm, :git
set :deploy_via, :remote_cache
set :repository, "dev1.regie:/bigdisk/git/haltes.git"
set :branch, "master"
set :keep_releases, 3
after "deploy:update", "deploy:cleanup"

# stages
set :stages, %w(acceptance@acc1 production@app2)
set :stage_dir, "config/deploy"
set :default_stage, "acceptance@acc1"
require 'capistrano/ext/multistage'

before "deploy:finalize_update", "composer:install", "deploy:config"
before "composer:install", "composer:copy_vendors"

namespace :deploy do
    task :migrate do
        # overrides the standard Rails database migrations task
    end
    task :start, :roles => :app do
    end
    task :stop, :roles => :app do
    end
    task :restart, :roles => :app do
        # no restart required for Apache/mod_php
    end
    desc "Symlink the local config into the new release, Create the proxy directory, run ant"
    task :config do
        run "cd #{release_path} && ln -s #{shared_path}/config/local.php #{release_path}/config/autoload/local.php"
        run "cd #{release_path}/data && mkdir DoctrineORMModule && mkdir DoctrineORMModule/Proxy && chmod 777 #{release_path}/data/DoctrineORMModule/Proxy"
        # run "cd #{release_path} && mkdir build && mkdir build/sassOutput"
        run "cd #{release_path} && ant"
    end
end

namespace :composer do
    desc "Copy vendors from previous release"
        task :copy_vendors, :except => { :no_release => true } do
        run "if [ -d #{previous_release}/vendor ]; then cp -a #{previous_release}/vendor #{latest_release}/vendor; fi"
    end
    desc "run composer install and ensure all dependencies are installed"
    task :install do
        run "cd #{release_path} && curl -s https://getcomposer.org/installer | php"
        #run "cd #{release_path} && ant"
    end
end
