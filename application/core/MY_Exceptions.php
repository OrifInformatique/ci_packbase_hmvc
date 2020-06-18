<?php

class MY_Exceptions extends CI_Exceptions {
    public function show_error($heading, $message, $template = 'error_general', $status_code = 500)
    {
        // Common views path, in the common module
        static $common_views_path = APPPATH.'modules'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR;
        
        // Header and footer views displayed before and after the error messages
        static $header_views = ['header', 'login_bar'];
        static $footer_views = ['footer'];

        if (is_cli())
        {
            return parent::show_error($heading, $message, $template, $status_code);

        } else {
            $message = parent::show_error($heading, $message, $template, $status_code);

            if (ob_get_level() > $this->ob_level + 1)
            {
                ob_end_flush();
            }

            ob_start();
            // Include header views
            foreach ($header_views as $view)
            {
                include($common_views_path.$view.'.php');
            }

            // Include error message
            echo $message;

            // Include footer views
            foreach ($footer_views as $view)
            {
                include($common_views_path.$view.'.php');
            }

            $buffer = ob_get_contents();
            ob_end_clean();

            return $buffer;
        }
    }
}