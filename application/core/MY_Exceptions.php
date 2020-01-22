<?php

class MY_Exceptions extends CI_Exceptions {
    public function show_error($heading, $message, $template = 'error_general', $status_code = 500)
    {
        // If the function calls itself, this line cleans the stored buffer
        ob_end_clean();
        // If there is an error in one of the views,
        // this prevents the view from being called again
        static $displayed = [
            'header' => 0,
            'login_bar' => 0,
            'error' => 0,
            'footer' => 0
        ];
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
            $displayed['header']++;
            if ($displayed['header'] <= 1) {
                include($common_templates_path.'header.php');
                $displayed['header']--;
            }
            $displayed['login_bar']++;
            if ($displayed['login_bar'] <= 1) {
                include($common_templates_path.'login_bar.php');
                $displayed['login_bar']--;
            }
        }
        $displayed['error']++;
        if ($displayed['error'] <= 1) {
            include($errors_templates_path.$template.'.php');
            $displayed['error']--;
        } else {
            // Basic error display, as the view is not working
            echo '==='.$heading.'==='.(is_cli() ? PHP_EOL : '<br />').$message;
        }
        if (!is_cli()) {
            $displayed['footer']++;
            if ($displayed['footer'] <= 1) {
                include($common_templates_path.'footer.php');
                $displayed['footer']--;
            }
        }
        $buffer = ob_get_contents();
        ob_end_clean();
        return $buffer;
    }
}