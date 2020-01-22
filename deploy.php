<?php
namespace Deployer;

require 'recipe/common.php';

// Project name
set('application', 'mqtt-daemon');

// Project repository
set('repository', 'git@github.com:mainstreamer/mqtt-daemon.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

// Shared files/dirs between deploys 
set('shared_files', []);
set('shared_dirs', []);

// Writable dirs by web server 
set('writable_dirs', []);
set('allow_anonymous_stats', false);

// Hosts
//    ->set('deploy_path', '~/{{application}}');
host('izeebot.top')
    ->user('root')
    ->set('branch', 'master')
    ->stage('production')
    ->set('deploy_path', '/var/www/mqtt-daemon');

set('user','root');

set('http_user', 'www-data');

set('env', [
    'APP_ENV' => 'prod',
    'DSN' => getenv('DSN'),
    'USER' => getenv('USER'),
    'PASS' => getenv('PASS'),
]);
// Tasks

desc('Deploy your project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:vendors',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');




