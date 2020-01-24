<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (ENVIRONMENT !== 'testing') {
    $autoload['language'] = array('MY_scanqrcode');
    //$autoload['config'] = array('MY_scanqrcode_config');
} else {
    // CI-PHPUnit checks from application/folder instead of module/folder
    $autoload['language'] = ['../../modules/scanqrcode/language/french/MY_scanqrcode'];
    //$autoload['config'] = ['../modules/scanqrcode/config/MY_scanqrcode_config'];
}
