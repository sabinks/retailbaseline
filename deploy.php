<?php
namespace Deployer;

require 'recipe/laravel.php';
require 'vendor/deployer/recipes/recipe/npm.php';

// Project name
set('application', 'lemon_om');
set('ssh_multiplexing', false);
set('writable_use_sudo', false);
set('cleanup_use_sudo', true);
// Project repository
set('repository', 'git@gitlab.com:arundhungel/operational-management.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true); 

//set defautl branch
// set('branch', 'develop');
set('keep_releases', 2);
set('release_name', function(){
	return date('YmdHis');
});

// Shared files/dirs between deploys 
add('shared_files', ['.env']);
add('shared_dirs', ['public/uploads', 'node_modules', 'storage']);

// Writable dirs by web server 
add('writable_dirs', ['public/uploads', 'storage', 'storage/framework', 'bootstrap/cache']);
after('deploy:update_code', 'npm:install');
// Hosts

host('retailbaseline_prod')
    ->set('deploy_path', '/var/www/lemon_om');
// Tasks
task('artisan:optimize', function () {});
task('build', function () {
    run('cd {{release_path}} && build');
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

task('storage:link' , function(){
	run ('cd {{release_path}} && php artisan storage:link && php artisan route:clear');
});
task('npm:production' , function(){
	run ('cd {{release_path}} && npm run production');
});

task('cleanup', function(){
        run('cd {{release_path}} && php artisan route:clear');
});


// Migrate database before symlink new release.
before('deploy:symlink', 'artisan:migrate');
// after('deploy', 'artisan:db:seed');
after('deploy', 'storage:link');
after('deploy', 'npm:production');
task('reload:php-fpm', function(){
	run('systemctl reload php7.3-fpm');
});

task('reload:nginx', function(){
	run('systemctl reload nginx');
});
after('deploy', 'reload:php-fpm');
after('deploy', 'reload:nginx');

