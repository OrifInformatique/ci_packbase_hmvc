<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User Administraton
 *
 * @author      Orif (ViDi, BuYa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * @version     2.0
 */
class QRCode extends MY_Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        /* Define controller access level */
        $this->access_level = $this->config->item('access_lvl_registered');

        parent::__construct();

        // Load required items
        $this->load->library('form_validation');

        // Assign form_validation CI instance to this
        $this->form_validation->CI =& $this;
    }

    public function index()
    {
        $this->display_view('qrcode/scan');
    }
}
