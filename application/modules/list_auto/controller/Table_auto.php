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

class Table_auto extends MY_Controller{

    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct();
    }


/**
 * table auto + dropdown + paging auto + sort (asc/desc).
 * 
 * @param array 		$data			an associative array which contains :
 * 
 * @param array 		$items			the datas you want to display from the database (return from a model)
 * 
 * @param array 		$thead			an array which contains the group header for the table (use your language file) 
 * 
 * @param array			$sort			an associative array which contains the param you need to sort your items. 
 * 										!!DO NOT FORGET DEFAULT CASE!! 
 * 										exemple :
 * 										
 * 										$sort = (
 * 											'question_asc'  		= "Question ASC, FK_Question_Type ASC, Points ASC, ID ASC"
 * 											'question_desc' 		= "Question DESC, FK_Question_Type ASC, Points ASC, ID ASC"
 * 											'question_type_asc'		= "FK_Question_Type ASC, Question ASC, Points ASC, ID ASC"
 * 											'question_type_desc'	= "FK_Question_Type DESC, Question ASC, Points ASC, ID ASC"
 * 											'points_asc'			= "Points ASC, Question ASC, FK_Question_Type ASC, ID ASC"
 * 											'points_desc'			= "Points DESC, Question ASC, FK_Question_Type ASC, ID ASC"
 * 											'default'				= "Question ASC, FK_Question_Type ASC, Points ASC, ID ASC"
 * 										)
 * 
 * @return html_code 	html code for a table which has paging, sort (asc or desc), display all of your items
 */
    public function table_auto($data = NULL, $page = 1){



        $this->load->library('pagination');

        $config = array(
			'base_url' => base_url(),
			'total_rows' => count($data['items']),
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

        $items_nb = array();

        foreach(ITEMS_NB as $value){
			array_push($items_nb, $value);
		};
        array_push($items_nb, $this->lang->line('all_items'));

		$this->pagination->initialize($config);

		$this->db->order_by($orderby);

		$output = array(
			'pagination' => $this->pagination->create_links(),
			'items' => array_slice($data['items'], ($page-1)*$items_per_page, $items_per_page),
			'items_nb' => $items_nb,
			'thead' => $thead,
			'sort' => $item_sort
		);

		if(is_array($data)){
			$list_auto = $this->load->view('display_list', $output, true);
		}
		else{
			$list_auto = NULL;
		}

		return $list_auto;
    }
}