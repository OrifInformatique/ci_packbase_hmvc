<?php

$config['nav'][] = [
    'cond' => function() {return !isset($_SESSION['logged_in']) || !$_SESSION['logged_in'];},
    'link' => 'user/auth/login',
    'text' => 'btn_login'
];
$config['nav'][] = [
    'cond' => function() {
		$CI =& get_instance();
		return isset($_SESSION['user_access']) && $_SESSION['user_access'] >= $CI->config->item('access_lvl_admin');
	},
    'link' => 'user/admin/list_user',
    'text' => 'btn_admin'
];
$config['nav'][] = [
    'cond' => function() {return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true;},
    'link' => 'user/auth/change_password',
    'text' => 'btn_change_my_password'
];
$config['nav'][] = [
    'cond' => function() {return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true;},
    'link' => 'user/auth/logout',
    'text' => 'btn_logout'
];
