set :application, "Regulation XVII"
set :domain,      "jackpf.noip.me"
set :user,        "osmc"
set :deploy_to,   "/home/#{user}/workspace/#{domain}"
set :app_path,    "app"

set :repository,  "https://github.com/jackpf/MusicSite.git"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain, :primary => true       # This may be the same as your `Web` server

set   :use_sudo,      false
set  :keep_releases,  3

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL