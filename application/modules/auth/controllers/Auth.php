<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Authentication System
 *
 * @author      Orif, section informatique (ViDi)
 * @link        https://github.com/OrifInformatique/stock
 * @copyright   Copyright (c), Orif <http://www.orif.ch>
 * @version     2.0
 */
class Auth extends MY_Controller
{
    /* MY_Controller variables definition */
    protected $access_level = "*";

    /**
    * Constructor
    */
    public function __construct() {
        parent::__construct();
        $this->load->model(['user_model', 'user_type_model']);
        $this->load->library('form_validation');
    }

    public function index() {
        redirect(base_url('auth/auth/login'));
    }

    public function login() {

        $output = array();

        if(isset($_POST['btn_login'])){
            $this->form_validation->set_rules('username', $this->lang->line('field_username'), 'required');
            $this->form_validation->set_rules('password', $this->lang->line('field_password'), 'required');

            if($this->form_validation->run()){
                if($this->user_model->check_password($this->input->post('username'), $this->input->post('password'))){
                    $user = $this->user_model->with('user_type')->get_by('User', $username);

                    $_SESSION['user_id'] = (int)$user->ID;
                    $_SESSION['username'] = (string)$user->User;
                    $_SESSION['user_access'] = (int)$user->user_type->access_level;
                    $_SESSION['logged_in'] = (bool)true;

                    redirect(base_url(REDIRECT_AFTER_LOGIN));
                } else {
                    $output['message'] = $this->lang->line('msg_login_error');
                }
            }
        }

        $this->display_view('login', $output);
    }

    public function logout() {
        session_destroy();
        redirect(base_url('auth/auth/login'));
    }

}