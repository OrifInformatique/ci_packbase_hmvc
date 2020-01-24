<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (ENVIRONMENT !== 'testing') {
    $autoload['language'] = array('MY_qrcode');
    $autoload['config'] = array('MY_qrcode_config');
} else {
    // CI-PHPUnit checks from application/folder instead of module/folder
    $autoload['language'] = ['../../modules/qrcode/language/french/MY_qrcode'];
    $autoload['config'] = ['../modules/qrcode/config/MY_qrcode_config'];
}
