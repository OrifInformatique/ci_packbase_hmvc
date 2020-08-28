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
 * @param array 		$data				an associative array which contains :
 * 
 * @param array 		$items				the datas you want to display from the database (return from a model)
 * 
 * @param array 		$thead				an associative array which contains the group header for the table (use your language file) + names of corresponding
 * 											colomns in your database
 * 
 * 											example :
 * 										
 * 											$thead = (
 * 												$this->lang->line('question') = 'question',
 * 												$this->lang->line('question_type') = 'question_type',
 * 												$this->lang->line('points') = 'points',
 * 											)
 * 
 * @param array			$sort				an associative array which contains the param you need to sort your items. 
 * 											/!\ DO NOT FORGET DEFAULT CASE /!\
 * 											/!\ CASE SENSITIVE /!\
 * 	
 * 											exemple :
 * 
 * 											$sort = (
 * 												'question_asc'  		= "Question ASC, FK_Question_Type ASC, Points ASC, ID ASC"
 * 												'question_desc' 		= "Question DESC, FK_Question_Type ASC, Points ASC, ID ASC"
 * 												'question_type_asc'		= "FK_Question_Type ASC, Question ASC, Points ASC, ID ASC"
 * 												'question_type_desc'	= "FK_Question_Type DESC, Question ASC, Points ASC, ID ASC"
 * 												'points_asc'			= "Points ASC, Question ASC, FK_Question_Type ASC, ID ASC"
 * 												'points_desc'			= "Points DESC, Question ASC, FK_Question_Type ASC, ID ASC"
 * 												'default'				= "Question ASC, FK_Question_Type ASC, Points ASC, ID ASC"
 * 											)
 * 
 * 											/!\  /!\
 * 
 * @param string		$controller_crud	A string which contains the name of your CRUD controller.
 * 											/!\ CASE SENSITIVE /!\
 * 
 * @param string		$method_update		A string which contains the update method's name in your CRUD controller
 * 											/!\ CASE SENSITIVE /!\
 * 
 * @param string		$method_delete		A string which contains the delete method's name in your CRUD controller
 * 											/!\ CASE SENSITIVE /!\
 * 
 * @param string		$view				A string which contains the view file's name where you want the items to be displayed
 * 											/!\ CASE SENSITIVE /!\
 * 
 * @return HTML 		html code for a table which has paging, sort (asc or desc), display all of your items
 */
    public function table_auto($data = NULL, $page = 1){

		$this->load->library('pagination');
		
		// var needed in view file
		$controller_crud = $data['controller_crud'];
		$method_update = $data['method_update'];
		$method_delete = $data['method_delete'];

		// var needed for this controller
		$count_data_items = count($data['items']);

		settype($items_per_page, "integer"); // initialize var

		if(empty($_GET['nb_items'])){ // setup how many item to display per page
			$items_per_page = PAGINATION_NB[0];
		} elseif($_GET['nb_items'] == $this->lang->line('all_items')){
			$items_per_page = $count_data_items;
		} else{
			$items_per_page = $_GET['nb_items'];
		}

	    if(($page - 1) * $items_per_page > $count_data_items) {
	    	redirect($controller_crud.'/'.$data['view'].'/1');
		}

		$pagination_nb = array(); // initialize var 

		$this->load->library('pagination');
		
		$base_url = base_url();

        $config = array(
			'base_url' => $base_url,
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

        $pagination_nb = array(); // initialize var 

        foreach(PAGINATION_NB as $value){
			array_push($pagination_nb, $value);
		};
        array_push($pagination_nb, $this->lang->line('all_items'));

		$this->pagination->initialize($config);

		$orderby="";

		$this->db->order_by($orderby);

		$controller_crud = $data['controller_crud'];
		$method_update = $data['method_update'];
		$method_delete = $data['method_delete'];

		$output = array(
			'pagination' => $this->pagination->create_links(),
			'items' => array_slice($data['items'], ($page-1)*$items_per_page, $items_per_page),
			'pagination_nb' => $pagination_nb,
			'thead' => $thead,
			'sort' => $item_sort,
		);

		if(is_array($data)){ // if data recevied are in array, let the function start, else, do nothing
			$list_auto = $this->load->view('display_list', $output, true);
		}
		else{
			$list_auto = NULL;
		}

		return $list_auto;
	}

	// function mysql_field_array($query){
	// 	$field = mysqli_num_fields($query);
	// 	for ( $i = 0; $i < $field; $i++ ){
	// 		$names[] = mysql_field_name( $query, $i );
	// 	}
	// 	return $names;
	// }

	function displayItems($item){
		$base_url = base_url()
		?>
		<tr id="<?=$item->ID; ?>" >
			<td id="item"><a href="<?=$base_url?>/<?=$controller_crud?>/<?=$method_update?>/<?=$item->ID;?>">
				<?php 
				/*
				Edit here: each item displayed here will have a link to the update method.
				*/
				?>
			</td>
			<?/*Add new <td></td> for more colomns per row.

				the 2 following lines make a link respectively to the update method and the delete method.*/?>
			<td style="text-align: center;"><a class="close" id="btn_update" href="<?=$base_url?>/<?=$controller_crud?>/<?=$method_update?>/<?=$item->ID;?>">✎</a></td>
			<td style="text-align: center;"><a class="close" id="btn_del" href="<?=$base_url?>/<?=$controller_crud?>/<?=$method_delete?>/<?=$item->ID;?>">×</a></td>
		</tr>
		<?php
	}
	?>
}