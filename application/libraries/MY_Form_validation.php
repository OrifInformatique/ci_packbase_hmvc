<?php

class MY_Form_Validation extends CI_Form_validation {
    public function run($module = '', $group = '')
    {
        if (is_object($module)) $this->CI =& $module;
        return parent::run($group);
    }
}