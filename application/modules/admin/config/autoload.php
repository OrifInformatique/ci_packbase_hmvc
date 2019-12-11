<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (ENVIRONMENT !== 'testing') {
    $autoload['language'] = ['MY_admin'];
    $autoload['config'] = ['auth/MY_auth_config'];
} else {
    // CI-PHPUnit checks from application/folder instead of module/folder
    $autoload['language'] = ['../../modules/admin/language/french/MY_admin'];
    $autoload['config'] = ['../modules/auth/config/MY_auth_config'];
}