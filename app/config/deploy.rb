set :application, "Regulation17"
set :domain,      "regulation17.com"
set :user,        "jack"
set :deploy_to,   "/home/#{user}/websites/#{domain}"
set :app_path,    "app"

set :repository,  "https://github.com/jackpf/Regulation17.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set  :use_sudo,      false
set  :keep_releases,  3

# Be more verbose by uncommenting the following line
#logger.level = Logger::MAX_LEVEL

set :shared_files,      ["app/config/parameters.yml"]
set :shared_children,   [app_path + "/logs", web_path + "/uploads", web_path + "/media"]

set :writable_dirs,       ["app/cache", "app/logs", "web/uploads", "web/media"]
set :webserver_user,      "www-data"
set :permission_method,   :acl
set :use_set_permissions, true

set :dump_assetic_assets, true

before "symfony:cache:warmup", "symfony:doctrine:schema:update"
after "deploy:update", "deploy:cleanup"