<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Constants file for listing items + paging automation
 *
 * @author      Orif (jubnl)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * @version     1.0
 */

// Define how many items displayed, will take the array's first value as default value
define('ITEMS_NB', array(
    25,
    50,
    100
    )
);

// Define CRUD controller
define('CONTROLLER_NAME', '');

// Define update method found in the CRUD controller 
define('METHOD_UPDATE_NAME', '');

// Define delete method found in the CRUD controller
define('METHOD_DELETE_NAME', '');
