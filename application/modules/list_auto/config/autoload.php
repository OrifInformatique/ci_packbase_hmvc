<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (ENVIRONMENT !== 'testing') {
    $autoload['language'] = array('MY_list_auto');
    $autoload['config'] = array('MY_list_auto_config');
} else {
    // CI-PHPUnit checks from application/folder instead of module/folder
    $autoload['language'] = ['../../modules/list_auto/language/french/MY_list_auto'];
    $autoload['config'] = ['../modules/list_auto/config/MY_list_auto_config'];
}
