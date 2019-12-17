<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Admin System
 *
 * @author      Orif (ViDi)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * @version     2.0
 */
class Admin extends MY_Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        /* Define controller access level */
        $this->access_level = $this->config->item('access_lvl_admin');

        parent::__construct();

        $this->load->module('auth');

        // Assign form_validation CI instance to this
        $this->form_validation->CI =& $this;
    }

    /**
     * Displays the primary index
     *
     * @param any $args = Arguments to pass to the primary index
     * @return void
     */
    public function index(...$args)
    {
        $this->user_index(...$args);
    }

    /*************************
     * Users-related functions
     *************************/
    
    /**
     * Displays the list of users
     *
     * @param boolean $with_deleted = Whether to select inactive users or only active
     * @return void
     */
    public function user_index($with_deleted = FALSE)
    {
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
        $this->display_view('admin/user/index', $output);
    }

    /**
     * Adds or modify an user
     *
     * @param integer $user_id = The id of the user to modify, leave blank to create a new one
     * @return void
     */
    public function user_add($user_id = 0, array $old_values = [])
    {
        $output = array(
            'title' => $this->lang->line('user_'.((bool)$user_id ? 'update' : 'new').'_title'),
            'user' => $this->user_model->with_deleted()->get($user_id),
            'user_types' => $this->user_type_model->dropdown('name'),
            'user_name' => $old_values['user_name'] ?? NULL,
            'user_usertype' => $old_values['user_usertype'] ?? NULL
        );
        $this->display_view('admin/user/form', $output);
    }

    /**
     * Validates the user input and inserts it in the database
     *
     * @return void
     */
    public function user_form()
    {
        $user_id = $this->input->post('id');

        $this->form_validation->set_rules(
            'id', 'id',
            'callback_cb_not_null_user',
            ['cb_not_null_user' => $this->lang->line('msg_err_user_not_exist')]
        );
        $this->form_validation->set_rules('user_name', 'lang:user_name', [
            'required', 'trim',
            'min_length['.$this->config->item('username_min_length').']',
            'max_length['.$this->config->item('username_max_length').']',
            "callback_cb_unique_user[{$user_id}]"
        ], ['cb_unique_user' => $this->lang->line('msg_err_user_not_unique')]);
        $this->form_validation->set_rules('user_usertype', 'lang:user_usertype',
            ['required', 'callback_cb_not_null_user_type'],
            ['cb_not_null_user_type' => $this->lang->line('msg_err_user_type_not_exist')]
        );

        $this->form_validation->set_rules(
            'deactivate', 'lang:btn_deactivate',
            "callback_cb_not_inactive_user[{$user_id}]",
            ['cb_not_inactive_user' => $this->lang->line('msg_err_user_already_inactive')]
        );
        $this->form_validation->set_rules(
            'reactivate', 'lang:btn_reactivate',
            "callback_cb_not_active_user[{$user_id}]",
            ['cb_not_active_user' => $this->lang->line('msg_err_user_already_active')]
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
            redirect('admin/user_index');
        } else {
            $old_values = [
                'user_name' => $this->input->post('user_name'),
                'user_usertype' => $this->input->post('user_usertype')
            ];
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
        $user = $this->user_model->with_deleted()->get($user_id);
        if (is_null($user)) redirect('admin/user_index');

        switch($action) {
            case 0: // Display confirmation
                $output = array(
                    'user' => $user,
                    'title' => lang('user_delete_title')
                );
                $this->display_view('admin/user/delete', $output);
                break;
            case 1: // Deactivate (soft delete) user
                $this->user_model->delete($user_id, FALSE);
                redirect('admin/user_index');
            case 2: // Delete user
                $this->user_model->delete($user_id, TRUE);
                redirect('admin/user_index');
            default: // Do nothing
                redirect('admin/user_index');
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
        $user = $this->user_model->with_deleted()->get($user_id);
        if (is_null($user)) {
            redirect('admin/user_index');
        } else {
            $this->user_model->undelete($user_id);
            redirect('admin/user_add/'.$user_id);
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
        $user = $this->user_model->with_deleted()->get($user_id);
        if (is_null($user)) redirect('admin/user_index');

        $output = array(
            'user' => $user,
            'title' => $this->lang->line('user_password_reset_title')
        );

        $this->display_view('admin/user/change_password', $output);
    }

    /**
     * Validates the password change and updates the database
     *
     * @return void
     */
    public function user_password_change_form()
    {
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
            redirect('admin/user_index');
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
     * Checks that an user is inactive
     *
     * @param string $disactivate = Value provided by CodeIgniter
     * @param integer $user_id = Id of the user to check
     * @return boolean = TRUE if disactivate is NULL or if the user is active
     */
    public function cb_not_inactive_user($disactivate, $user_id) : bool
    {
        if (is_null($disactivate)) return TRUE;
        $user = $this->user_model->with_deleted()->get($user_id);
        if (is_null($user)) return FALSE;
        return $user->archive == 0;
    }
    /**
     * Checks that an user is active
     *
     * @param string $disactivate = Value provided by CodeIgniter
     * @param integer $user_id = Id of the user to check
     * @return boolean = TRUE if disactivate is NULL or if the user is inactive
     */
    public function cb_not_active_user($disactivate, $user_id) : bool
    {
        if (is_null($disactivate)) return TRUE;
        $user = $this->user_model->with_deleted()->get($user_id);
        if (is_null($user)) return FALSE;
        return $user->archive == 1;
    }
}
