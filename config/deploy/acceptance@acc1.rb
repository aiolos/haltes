set :use_sudo, false
set :location, "acc1.regie"
set :branch, "master"

role :app, location
role :web, location
role :db, location, :primary => true

set :deploy_to, "/bigdisk/apache_sites/haltes"
