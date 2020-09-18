<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * table auto + dropdown + paging auto + sort (asc/desc).
 * 
 * 
 * 
 * @author      Orif (jubnl)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * @version     1.0
 */

class Manager extends MY_Controller{

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();

        $this->load->model(['user/user_model', 'user/user_type_model']);
    }

    public function index($page = 1) {
        $output['items'] = $this->get_items();
        $output['columns'] = array('id'=>'Id', 'username'=>'Nom d\'utilisateur', 'col_delete'=>"");
        $output['sort'] = array('sort_field'=>'username', 'sort_order'=>'asc');
        $output['controller'] = "test_view";

        // Pagination
        $this->load->library('pagination');
        $output['pagination_nb'] = $this->config->item('pagination_nb');
        array_push($output['pagination_nb'], $this->lang->line('all_items'));

        // var needed for pagination
        $count_data_items = count($output['items']);
        $items_per_page = $output['pagination_nb'][0];

        $config = array(
			'base_url' => base_url('list_auto/manager/index'),
			'total_rows' => $count_data_items,
			'per_page' => $items_per_page,
			'use_page_numbers' => TRUE,
			'reuse_query_string' => TRUE,

			'full_tag_open' => '<ul class="pagination">',
			'full_tag_close' => '</ul>',

			'first_link' => '&laquo;',
			'first_tag_open' => '<li class="page-item">',
			'first_tag_close' => '</li>',

			'last_link' => '&raquo;',
			'last_tag_open' => '<li class="page-item">',
			'last_tag_close' => '</li>',

			'next_link' => FALSE,
			'prev_link' => FALSE,

			'cur_tag_open' => '<li class="page-item active"><a class="page-link" href="#">',
			'cur_tag_close' => '</a></li>',
			'num_links' => 5,

			'num_tag_open' => '<li class="page-item">',
			'num_tag_close' => '</li>',
			'attributes' => ['class' => 'page-link']
        );
        $this->pagination->initialize($config);
        
        $output['pagination'] = $this->pagination->create_links();
        $output['page_nb'] = $page;

        // Keep only the slice of items corresponding to the current page
        $output["items"] = array_slice($output["items"], ($output['page_nb']-1)*$items_per_page, $items_per_page);

        $this->display_view('list_auto/list', $output);
    }

    protected function get_items($sort_field = NULL, $sort_order = 'asc') {
        if (isset($sort_field)) {
            $this->db->order_by($sort_field.' '.$sort_order);
        }
        return $this->user_model->as_array()->get_all();
    }
}