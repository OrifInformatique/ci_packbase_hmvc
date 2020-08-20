<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * List items + paging automation
 *
 * @author      Orif (jubnl)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * @version     1.0
 */

class List_auto extends MY_Controller{
    /* MY_Controller variables definition */

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }

    public function index($base_url, $data){

        $this->load->library('pagination');

        $config = array(
			'base_url' => $base_url,
			'total_rows' => count($output['items']),
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

        $output = array(
			'pagination' => $this->pagination->create_links(),
			'items' => array_slice($output['items'], ($page-1)*$items_per_page, $items_per_page),
			'items_nb' => $items_nb
        );
        
        $items_nb = array();

        foreach(ITEMS_NB as $value){
			array_push($items_nb, $value);
		};
        array_push($items_nb, $this->lang->line('all_items'));
        
        $this->pagination->initialize($config);
    }
}