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
 * 										example :
 *
 * 										$thead = (
 * 											$this->lang->line('thead_1'),
 * 											$this->lang->line('thead_2'),
 * 											$this->lang->line('thead_3'),
 * 											$this->lang->line('thead_4')
 * 										)
 *
 *
 * @return html_code 	html code for a table which has paging, sort (asc or desc), display all of your items
 */
    public function table_auto($data = NULL, $page = 1){

		$this->load->library('pagination');
		
		// var needed in view file
		$controller = CONTROLLER_NAME;
		$method_update = METHOD_UPDATE_NAME;
		$method_delete = METHOD_DELETE_NAME;

		settype($items_per_page, "integer"); // initialize var

		if(empty($_GET['nb_items'])){ // setup how many item to display per page
			$items_per_page = ITEMS_NB[0];
		} elseif($_GET['nb_items'] == $this->lang->line('all_items')){
			$items_per_page = count($items);
		} else{
			$items_per_page = $_GET['nb_items'];
		}

	    if(($page - 1) * $items_per_page > count($items)) {
	    	redirect($controller.'/display_list/1');
		}

		$items_nb_dropdown = array(); // initialize var 

		foreach(ITEMS_NB as $value){	// setup dropdown
			array_push($items_nb_dropdown, $value);
		};
		array_push($items_nb_dropdown, $this->lang->line('all_items'));

		$config = array( 	// config for paging
			'base_url' => base_url(),
			'total_rows' => count($items),
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

		$field_names = mysql_field_array($items);
		$item_sort = array();
		$item_sort_asc = array();
		$item_sort_desc = array();
	
		foreach($field_names as $names){
			array_push($item_sort_asc, $names." ASC");
			array_push($item_sort_desc, $names." DESC");
		};
		array_push($item_sort, $item_sort_asc);
		array_push($item_sort, $item_sort_desc);

		$this->db->order_by($orderby);

		$output = array( // all the datas passed to the view
			'pagination' => $this->pagination->create_links(),
			'items' => array_slice($data['items'], ($page-1)*$items_per_page, $items_per_page),
			'items_nb_dropdown' => $items_nb_dropdown,
			'thead' => $thead,
			'item_sort' => $item_sort,
			'controller' => $controller,
			'method_update' => $method_update,
			'method_delete' => $method_delete,
			'field_names' => $field_names
		);

		if(is_array($data)){ // if data recevied are in array, let the function start, else, do nothing
			$list_auto = $this->load->view('display_list', $output, true);
		}
		else{
			$list_auto = NULL;
		}

		return $list_auto;
	}

	function mysql_field_array($data){ // get field's name and put them in an array
		$field = mysqli_num_fields($data);
		for ($i = 0; $i < $field; $i++){
			$field_names[] = mysql_field_name($data, $i);
		}
		return $field_names;
	}
}