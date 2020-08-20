<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (ENVIRONMENT !== 'testing') {
    $autoload['config'] = array('MY_auto_list_config');
} else {
    // CI-PHPUnit checks from application/folder instead of module/folder
    $autoload['config'] = ['../modules/list_auto/config/MY_auto_list_config'];
}
