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
 * 											$thead = array(
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
 * 											$sort = array(
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
    public function index($page = 1){

		$this->load->library('pagination');

		// var needed for this controller
		$count_data_items = count(list_user());

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

		$thead = array(
			$this->lang->line('field_user_name'),
			$this->lang->line('field_email'),
			$this->lang->line('field_user_usertype'),
			$this->lang->line('field_user_active')
		);

		$output = array(
			'pagination' => $this->pagination->create_links(),
			'items' => array_slice(list_user(), ($page-1)*$items_per_page, $items_per_page),
			'pagination_nb' => $pagination_nb,
			'thead' => $thead,
		);

		$this->display_view('list_auto/test_display_list', $output);
	}

	// function mysql_field_array($query){
	// 	$field = mysqli_num_fields($query);
	// 	for ( $i = 0; $i < $field; $i++ ){
	// 		$names[] = mysql_field_name( $query, $i );
	// 	}
	// 	return $names;
	// }

	public function list_user(){
		$users = $this->user_model->get_all();
		$this->user_type_model->dropdown('name');

        $output = array(
            'users' => $users,
            'user_types' => $user_types,
        );
        return $output;
    }

	function displayItems($item){
		$base_url = base_url()
		?>
		<tr>
            <td><a href="_blank"><?= $user->username; ?></td>
            <td><?= $user->email; ?></td>
            <td><?= $user_types[$user->fk_user_type]; ?></td>
            <td><?= $this->lang->line($user->archive ? 'no' : 'yes'); ?></td>
            <td><a href="_blank" class="close">×</td>
        </tr>
		<?php
	}
	?>
}