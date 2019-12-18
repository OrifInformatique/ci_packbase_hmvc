<?php

class MY_Exceptions extends CI_Exceptions {
    public function show_error($heading, $message, $template = 'error_general', $status_code = 500)
    {
        $title = $heading;
		$errors_templates_path = config_item('error_views_path');
		if (empty($errors_templates_path))
		{
			$errors_templates_path = VIEWPATH.'errors'.DIRECTORY_SEPARATOR;
		}

		if (is_cli())
		{
			$message = "\t".(is_array($message) ? implode("\n\t", $message) : $message);
			$template = 'cli'.DIRECTORY_SEPARATOR.$template;
		}
		else
		{
			set_status_header($status_code);
			$message = '<p>'.(is_array($message) ? implode('</p><p>', $message) : $message).'</p>';
			$template = 'html'.DIRECTORY_SEPARATOR.$template;
        }

        // Default to application/views/common
        $common_templates_path = VIEWPATH.'common'.DIRECTORY_SEPARATOR;
        if (!file_exists($common_templates_path)) {
            // Select the views in the common module
            $common_templates_path = APPPATH.'modules'.DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR;
        }

		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
        }
        ob_start();
        if (!is_cli()) {
            include($common_templates_path.'header.php');
            include($common_templates_path.'login_bar.php');
        }
        include($errors_templates_path.$template.'.php');
        if (!is_cli()) {
            include($common_templates_path.'footer.php');
        }
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }
}