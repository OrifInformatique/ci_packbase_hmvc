<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User Administraton
 *
 * @author      Orif (ViDi, BuYa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * @version     2.0
 */
class ScanQRCode extends MY_Controller
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
        redirect('scanqrcode/scan');
    }

    public function generate($type = '', $id = 0)
    {
        if(!empty($type) && !empty($id)){
            $this->load->library('ciqrcode');

            $json = [
                'type' => $type,
                'id' => $id
            ];
            $params['data'] = json_encode($json);

            header("Content-Type: image/png");
            $this->ciqrcode->generate($params);
        } else {
            redirect('scanqrcode');
        }
    }

    public function scan()
    {
        $this->display_view('scanqrcode/scan');
    }

    public function read()
    {
        if(isset($_POST['json'])){
            $json = json_decode($_POST['json']);
            switch ($json->type) {
                case 'user':
                    redirect('user/admin/save_user/'.$json->id);
                    break;
                default:
                    redirect('scanqrcode');
                    break;
            }
        } else {
            redirect('scanqrcode');
        }
    }
}
