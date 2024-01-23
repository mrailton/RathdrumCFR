<?php

namespace Deployer;

require 'recipe/laravel.php';
require 'contrib/php-fpm.php';
require 'contrib/npm.php';

set('application', 'rathdrumcfr');
set('repository', 'git@github.com:mrailton/rathdrumcfr.git');
set('php_fpm_version', '8.2');

host(getenv('HOST'))
    ->set('remote_user', getenv('USER'))
    ->set('deploy_path', '~/www');

task('deploy', [
    'deploy:prepare',
    'deploy:vendors',
    'artisan:storage:link',
    'artisan:view:cache',
    'artisan:config:cache',
    'artisan:migrate',
    'npm:install',
    'npm:run:build',
    'deploy:publish',
    'artisan:queue:restart',
    'honeybadger:deploy',
]);

task('npm:run:build', function () {
    cd('{{release_path}}');
    run('npm run build');
});

task('honeybadger:deploy', function () {
    cd('{{release_path}}');
    run('php artisan honeybadger:deploy');
});

after('deploy:failed', 'deploy:unlock');
