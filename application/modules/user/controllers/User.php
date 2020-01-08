<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User Administraton and Authentication
 *
 * @author      Orif (ViDi)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * @version     2.0
 */
class User extends MY_Controller
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
        /* Define controller access level */
		$this->access_level = '*';

		parent::__construct();

		// Load required items
        $this->load->library('form_validation');
        $this->load->model(['user_model', 'user_type_model']);

        // Assign form_validation CI instance to this
		$this->form_validation->CI =& $this;
	}

	/*********************
	 * User Authentication
	 *********************/
	/**
	 * Login user and create session variables
	 *
	 * @return void
	 */
	public function login()
	{
        // If user already logged
        if(!(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)) {
            // Store the redirection URL in a session variable
            if (!is_null($this->input->post('after_login_redirect'))) {
                $_SESSION['after_login_redirect'] = $this->input->post('after_login_redirect');
            }
            // If no redirection URL is provided or the redirection URL is the
            // login form, redirect to site's root after login
            if (!isset($_SESSION['after_login_redirect'])
                    || $_SESSION['after_login_redirect'] == current_url()) {

                $_SESSION['after_login_redirect'] = base_url();
            }

            // Check if the form has been submitted, else just display the form
            if (!is_null($this->input->post('btn_login'))) {
                // Define fields validation rules
                $validation_rules = array(
                    array(
                        'field' => 'username',
                        'label' => 'lang:field_username',
                        'rules' => 'trim|required|'
                                 . 'min_length['.$this->config->item('username_min_length').']|'
                                 . 'max_length['.$this->config->item('username_max_length').']'
                    ),
                    array(
                        'field' => 'password',
                        'label' => 'lang:field_password',
                        'rules' => 'trim|required|'
                                 . 'min_length['.$this->config->item('password_min_length').']|'
                                 . 'max_length['.$this->config->item('password_max_length').']'
                    )
                );
                $this->form_validation->set_rules($validation_rules);

                // Check fields validation rules
                if ($this->form_validation->run() == true) {
                    $username = $this->input->post('username');
                    $password = $this->input->post('password');

                    if ($this->user_model->check_password($username, $password)) {
                        // Login success
                        $user = $this->user_model->with('user_type')
                                                 ->get_by('username', $username);

                        // Set session variables
                        $_SESSION['user_id'] = (int)$user->id;
                        $_SESSION['username'] = (string)$user->username;
                        $_SESSION['user_access'] = (int)$user->user_type->access_level;
                        $_SESSION['logged_in'] = (bool)true;

                        // Send the user to the redirection URL
                        redirect($_SESSION['after_login_redirect']);

                    } else {
                        // Login failed
                        $this->session->set_flashdata('message-danger', lang('msg_err_invalid_password'));
                    }
                }
            }

            // Display login page
            $output = array('title' => lang('page_login'));
            $this->display_view('user/login_form', $output);
        } else {
            redirect(base_url());
        }
	}

	/**
	 * Logout and destroy session
	 *
	 * @return void
	 */
	public function logout()
	{
        // Restart session with empty parameters
        $_SESSION = [];
        session_reset();
        session_unset();

        redirect(base_url());
	}

	/**
	 * Displays a form to let user change his password
	 *
	 * @return void
	 */
	public function change_password()
	{
        // Check if access is allowed
        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) {

            // Check if the form has been submitted, else just display the form
            if (!is_null($this->input->post('btn_change_password'))) {
                $username = $_SESSION["username"];

                // Define fields validation rules
                $validation_rules = array(
                    array(
                        'field' => 'old_password',
                        'label' => 'lang:field_old_password',
                        'rules' => 'trim|required|'
                                 . 'min_length['.$this->config->item('password_min_length').']|'
                                 . 'max_length['.$this->config->item('password_max_length').']|'
                                 . 'callback_old_password_check['.$username.']',
                        'errors' => array(
                            'old_password_check' => lang('msg_err_invalid_old_password')
                        )
                    ),
                    array(
                        'field' => 'new_password',
                        'label' => 'lang:field_new_password',
                        'rules' => 'trim|required|'
                                 . 'min_length['.$this->config->item('password_min_length').']|'
                                 . 'max_length['.$this->config->item('password_max_length').']'
                    ),
                    array(
                        'field' => 'confirm_password',
                        'label' => 'lang:field_password_confirm',
                        'rules' => 'trim|required|'
                                 . 'min_length['.$this->config->item('password_min_length').']|'
                                 . 'max_length['.$this->config->item('password_max_length').']|'
                                 . 'matches[new_password]'
                    )
                );
                $this->form_validation->set_rules($validation_rules);

                // Check fields validation rules
                if ($this->form_validation->run() == true) {
                    $old_password = $this->input->post('old_password');
                    $new_password = $this->input->post('new_password');
                    $confirm_password = $this->input->post('confirm_password');

                    $this->load->model('user_model');
                    $this->user_model->update($_SESSION['user_id'],
                            array("password" => password_hash($new_password, $this->config->item('password_hash_algorithm'))));

                    // Send the user back to the site's root
                    redirect(base_url());
                }
            }

            // Display the password change form
            $output['title'] = $this->lang->line('page_password_change');
            $this->display_view('user/password_change_form', $output);
        } else {
            // Access is not allowed
            $this->login();
        }
	}

	/**
	 * Callback method for change_password validation rule
	 *
     * @param string $pwd = The previous password
     * @param string $user = The username
     * @return boolean = Whether or not the combination is correct
	 */
	public function old_password_check($pwd, $user) {
        return $this->user_model->check_password($user, $pwd);
	}

	/*********************
	 * User Administration
	 *********************/
    /**
     * Displays the list of users
     *
     * @param boolean $with_deleted = Whether to select inactive users or only active
     * @return void
     */
    public function user_index($with_deleted = FALSE)
    {
		$this->redirect_if_not_admin();

        if ($with_deleted) {
            $users = $this->user_model->with_deleted()->get_all();
        } else {
            $users = $this->user_model->get_all();
        }

        $output = array(
            'users' => $users,
            'user_types' => $this->user_type_model->dropdown('name'),
            'with_deleted' => $with_deleted
        );
        $this->display_view('user/user/index', $output);
    }

    /**
     * Adds or modify an user
     *
     * @param integer $user_id = The id of the user to modify, leave blank to create a new one
     * @return void
     */
    public function user_add($user_id = 0, array $old_values = [])
    {
		$this->redirect_if_not_admin();

        $output = array(
            'title' => $this->lang->line('user_'.((bool)$user_id ? 'update' : 'new').'_title'),
            'user' => $this->user_model->with_deleted()->get($user_id),
            'user_types' => $this->user_type_model->dropdown('name'),
            'user_name' => $old_values['user_name'] ?? NULL,
            'user_usertype' => $old_values['user_usertype'] ?? NULL
        );
        $this->display_view('user/user/form', $output);
    }

    /**
     * Validates the user input and inserts it in the database
     *
     * @return void
     */
    public function user_form()
    {
		$this->redirect_if_not_admin();

        $user_id = $this->input->post('id');

        $this->form_validation->set_rules(
            'id', 'id',
            'callback_cb_not_null_user',
            ['cb_not_null_user' => $this->lang->line('msg_err_user_not_exist')]
        );
		$this->form_validation->set_rules('user_name', 'lang:user_name',
			[
				'required', 'trim',
				'min_length['.$this->config->item('username_min_length').']',
				'max_length['.$this->config->item('username_max_length').']',
				"callback_cb_unique_user[{$user_id}]"
			],
			['cb_unique_user' => $this->lang->line('msg_err_user_not_unique')]
		);
        $this->form_validation->set_rules('user_usertype', 'lang:user_usertype',
            ['required', 'callback_cb_not_null_user_type'],
            ['cb_not_null_user_type' => $this->lang->line('msg_err_user_type_not_exist')]
        );

        if ($user_id == 0) {
            $this->form_validation->set_rules('user_password', lang('field_password'), [
                'required', 'trim',
                'min_length['.$this->config->item('password_min_length').']',
                'max_length['.$this->config->item('password_max_length').']'
            ]);
            $this->form_validation->set_rules('user_password_again', $this->lang->line('field_password_confirm'), [
                'required', 'trim', 'matches[user_password]',
                'min_length['.$this->config->item('password_min_length').']',
                'max_length['.$this->config->item('password_max_length').']'
            ]);
        }

        if ($this->form_validation->run()) {
            $user = array(
                'fk_user_type' => $this->input->post('user_usertype'),
                'username' => $this->input->post('user_name')
            );
            if ($user_id > 0) {
                $this->user_model->update($user_id, $user);
            } else {
                $password = $this->input->post('user_password');
                $user['password'] = password_hash($password, $this->config->item('password_hash_algorithm'));
                $this->user_model->insert($user);
            }
            redirect('user/user_index');
        } else {
            $old_values['user_name'] = $this->input->post('user_name');
            if($_SESSION['user_id'] != $user_id)
                $old_values['user_usertype'] = $this->input->post('user_usertype');
            $this->user_add($user_id, $old_values);
        }
    }

    /**
     * Deletes or deactivate a user depending on $action
     *
     * @param integer $user_id = ID of the user to affect
     * @param integer $action = Action to apply on the user:
     *  - 0 for displaying the confirmation
     *  - 1 for deactivating (soft delete)
     *  - 2 for deleting (hard delete)
     * @return void
     */
    public function user_delete($user_id, $action = 0)
    {
		$this->redirect_if_not_admin();

        $user = $this->user_model->with_deleted()->get($user_id);
        if (is_null($user)) {
            redirect('user/user_index');
        }

        switch($action) {
            case 0: // Display confirmation
                $output = array(
                    'user' => $user,
                    'title' => lang('user_delete_title')
                );
                $this->display_view('user/user/delete', $output);
                break;
            case 1: // Deactivate (soft delete) user
                if ($_SESSION['user_id'] != $user->id) {
                    $this->user_model->delete($user_id, FALSE);
                }
                redirect('user/user_index');
            case 2: // Delete user
                if ($_SESSION['user_id'] != $user->id) {
                    $this->user_model->delete($user_id, TRUE);
                }
                redirect('user/user_index');
            default: // Do nothing
                redirect('user/user_index');
        }
    }
    
    /**
     * Reactivate a disabled user.
     *
     * @param integer $user_id = ID of the user to affect
     * @return void
     */
    public function user_reactivate($user_id)
    {
		$this->redirect_if_not_admin();

        $user = $this->user_model->with_deleted()->get($user_id);
        if (is_null($user)) {
            redirect('user/user_index');
        } else {
            $this->user_model->undelete($user_id);
            redirect('user/user_add/'.$user_id);
        }
    }

    /**
     * Displays a form to change an user's password
     *
     * @param integer $user_id = ID of the user to update
     * @return void
     */
    public function user_password_change($user_id)
    {
		$this->redirect_if_not_admin();

        $user = $this->user_model->with_deleted()->get($user_id);
        if (is_null($user)) redirect('user/user_index');

        $output = array(
            'user' => $user,
            'title' => $this->lang->line('user_password_reset_title')
        );

        $this->display_view('user/user/change_password', $output);
    }

    /**
     * Validates the password change and updates the database
     *
     * @return void
     */
    public function user_password_change_form()
    {
		$this->redirect_if_not_admin();

        $user_id = $this->input->post('id');

        $this->form_validation->set_rules(
            'id', 'id',
            'callback_cb_not_null_user',
            $this->lang->line('msg_err_user_not_exist')
        );
        $this->form_validation->set_rules('user_password_new', lang('field_new_password'), [
            'required', 'trim',
            'min_length['.$this->config->item('password_min_length').']',
            'max_length['.$this->config->item('password_max_length').']'
        ]);
        $this->form_validation->set_rules('user_password_again', $this->lang->line('field_password_confirm'), [
            'required', 'trim', 'matches[user_password_new]',
            'min_length['.$this->config->item('password_min_length').']',
            'max_length['.$this->config->item('password_max_length').']'
        ]);

        if ($this->form_validation->run()) {
            $password = $this->input->post('user_password_new');
            $password = password_hash($password, $this->config->item('password_hash_algorithm'));
            $this->user_model->update($user_id, ['password' => $password]);
            redirect('user/user_index');
        } else {
            $this->user_password_change($user_id);
        }
    }

    /**
     * Checks that an username doesn't not exist
     *
     * @param string $username = Username to check
     * @param int $user_id = ID of the user if it is an update
     * @return boolean = TRUE if the username is unique, FALSE otherwise
     */
    public function cb_unique_user($username, $user_id) : bool
    {
        $user = $this->user_model->with_deleted()->get_by('username', $username);
        return is_null($user) || $user->id == $user_id;
    }
    /**
     * Checks that an user exists
     *
     * @param integer $user_id = Id of the user to check
     * @return boolean = TRUE if the id is 0 or if the user exists, FALSE otherwise
     */
    public function cb_not_null_user($user_id) : bool
    {
        return $user_id == 0 || !is_null($this->user_model->with_deleted()->get($user_id));
    }
    /**
     * Checks that an user type exists
     *
     * @param integer $user_type_id = Id of the user type to check
     * @return boolean = TRUE if the user type exists, FALSE otherwise
     */
    public function cb_not_null_user_type($user_type_id) : bool
    {
        return !is_null($this->user_type_model->get($user_type_id));
	}

	/**
	 * Checks if the user is an admin and redirects to user/login if they are not
	 *
	 * @return void
	 */
	private function redirect_if_not_admin()
	{
		if (!$this->check_permission($this->config->item('access_lvl_admin'))) {
			redirect('user/login');
		}
	}
}
