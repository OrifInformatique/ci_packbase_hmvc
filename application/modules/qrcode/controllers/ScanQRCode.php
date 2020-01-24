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
        redirect('qrcode/scanQRCode/scan');
    }

    public function generate($type = '', $value = '')
    {
        if(!empty($type) && !empty($value)){
            $this->load->library('ciqrcode');

            $json = [
                'type' => $type,
                'value' => $value
            ];
            $params['data'] = json_encode($json);

            header("Content-Type: image/png");
            $this->ciqrcode->generate($params);
        } else {
            redirect('qrcode/scanQRCode');
        }
    }

    public function scan()
    {
        $this->display_view('qrcode/scan');
    }

    public function read()
    {
        if(isset($_POST['json'])){
            $json = json_decode($_POST['json']);
            switch ($json->type) {
                case 'text':
                    echo urldecode($json->value);
                    break;
                case 'user':
                    redirect('user/admin/save_user/'.$json->value);
                    break;
                default:
                    redirect('qrcode/scanQRCode');
                    break;
            }
        } else {
            redirect('qrcode/scanQRCode');
        }
    }
}
