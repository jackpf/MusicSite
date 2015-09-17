set :application, "Regulation17"
set :domain,      "185.116.212.11"
set :user,        "jack"
set :deploy_to,   "/home/#{user}/websites/#{domain}"
set :app_path,    "app"

set :repository,  "https://github.com/jackpf/Regulation17.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :model_manager, "doctrine"
# Or: `propel`

set :permission_method,   :acl
set :use_set_permissions, true

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set  :use_sudo,      false
set  :keep_releases,  3

# Be more verbose by uncommenting the following line
#logger.level = Logger::MAX_LEVEL

set :shared_files,      ["app/config/parameters.yml"]
set :dump_assetic_assets, true

after "deploy:update", "deploy:cleanup"