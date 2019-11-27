<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| CUSTOM CONSTANTS
|--------------------------------------------------------------------------
|
| These are constants defined specially for this application.
|
| Add line "include'MY_constants.php';" in constants.php to load these
*/

/*
|--------------------------------------------------------------------------
| Authentication system constants
|--------------------------------------------------------------------------
*/
define('ACCESS_LVL_GUEST', 1);
define('ACCESS_LVL_REGISTERED', 2);
define('ACCESS_LVL_ADMIN', 4);

define('USERNAME_MIN_LENGTH', 3);
define('USERNAME_MAX_LENGTH', 45);
define('PASSWORD_MIN_LENGTH', 6);
define('PASSWORD_MAX_LENGTH', 72);
define('TOPIC_MAX_LENGTH', 150);

define('PASSWORD_HASH_ALGORITHM', PASSWORD_BCRYPT);
