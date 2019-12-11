<?php
// Load admin controller for callback tests
require_once(__DIR__.'/../../modules/admin/controllers/Admin.php');

/**
 * Class for tests for Admin controller
 * 
 * @author      Orif, section informatique (ViDi, MeSa, BuYa)
 * @link        https://github.com/OrifInformatique/gestion_questionnaires
 * @copyright   Copyright (c) Orif (http://www.orif.ch)
 */
class Admin_Test extends TestCase {
    /**
     * Stores the dummy values for dummy entries
     *
     * @var array
     */
    private static $_dummy_values = [
        'user' => [
            'name' => 'admin_dummy_user',
            'name_alt' => 'admin_user_dummy',
            'pass' => 'dummy_password',
            'pass_alt' => 'password_dummy',
            'type' => NULL
        ]
    ];

    /**
     * Stores the current dummy ids
     * 
     * Only use these through reference
     *
     * @var array
     */
    private static $_dummy_ids = [
        'user' => NULL
    ];

    /*******************
     * START/END METHODS
     *******************/
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $CI =& get_instance();
        if($CI instanceof CIPHPUnitTestNullCodeIgniter) {
            CIPHPUnitTest::createCodeIgniterInstance();
            $CI =& get_instance();
        }
        $CI->load->database();
        $CI->load->model('auth/user_type_model');

        self::$_dummy_values['user']['type'] = $CI->user_type_model->get_all()[0]->id;

        // Make sure everything exists before counting
        self::_dummy_user_create();
    }
    public function setUp()
    {
        $this->resetInstance();
        // Required to load the correct url_helper
        $this->CI->load->helper('url');
        // Tests cannot work without this
        self::_login_as_admin();
        // Make sure everything exists before testing
        self::_dummy_user_create();

        // Load Admin for future use
        $this->class_map['Admin'] = Admin::class;
    }
    public function tearDown()
    {
        parent::tearDown();

        self::_logout();
        self::_dummy_user_reset();
    }
    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        self::_dummy_users_wipe();
    }

    /*******
     * TESTS
     *******/
    /**
     * Test for `Admin::index`
     * 
     * @dataProvider provider_index
     *
     * @param string $with_deleted = Value to pass to $with_deleted
     * @param integer $expected_count = Amount of users expected
     * @return void
     */
    public function test_index(string $with_deleted, int $expected_count)
    {
        $output = $this->request('GET', 'admin/index/'.$with_deleted);

        // Each user is in a <tr>, and so are the headers
        $actual_count = substr_count($output, '<tr>')-1;

        $this->assertEquals($expected_count, $actual_count);
    }
    /**
     * Test for `Admin::user_index`
     * 
     * @dataProvider provider_index
     *
     * @param string $with_deleted = Value to pass to $with_deleted
     * @param integer $expected_count = Amount of users expected
     * @return void
     */
    public function test_user_index(string $with_deleted, int $expected_count)
    {
        $output = $this->request('GET', 'admin/user_index/'.$with_deleted);

        // Each user is in a <tr>, and so are the headers
        $actual_count = substr_count($output, '<tr>')-1;

        $this->assertEquals($expected_count, $actual_count);
    }
    /**
     * Test for `Admin::user_add`
     * 
     * @dataProvider provider_user_add
     *
     * @param string $user_id = ID of the user to modify
     * @param string $expected_title = Expected title of the page
     * @return void
     */
    public function test_user_add(string $user_id, string $expected_title, callable $setup)
    {
        $setup($user_id);
        $this->CI->lang->load('MY_application');
        $expected_title = '<title>'.$this->CI->lang->line('page_prefix').' - '.$expected_title.'</title>';

        // First request always fails, so make sure it's not first
        $this->request('GET', 'admin/user_add');
        $output = $this->request('GET', 'admin/user_add/'.$user_id);

        $this->assertTrue(strpos($output, $expected_title) !== FALSE);
    }
    /**
     * Test for `Admin::user_form`
     * 
     * @dataProvider provider_user_form
     *
     * @param array $post_params = Parameters to pass to $_POST
     * @param boolean $error_expected = Whether or not an error is expected in `validation_errors()`
     * @param boolean $redirect_expected = Whether or not a redirect is expected
     * @return void
     */
    public function test_user_form(array $post_params, bool $error_expected, bool $redirect_expected)
    {
        $this->request('POST', 'admin/user_form', $post_params);

        if ($error_expected) {
            $this->assertNotEmpty(validation_errors());
        } else {
            $this->assertEmpty(validation_errors());
        }

        if ($redirect_expected) {
            $this->assertRedirect('admin/user_index');
        }
    }
    /**
     * Test for `Admin::user_delete`
     * 
     * @dataProvider provider_user_delete
     *
     * @param string $user_id = ID of the user to "delete"
     * @param string $action = Value to pass to $action
     * @param array $status = Status of the user
     * @param boolean $redirect_expected = Whether a redirect is expected
     * @return void
     */
    public function test_user_delete(string $user_id, string $action, array $status, bool $redirect_expected)
    {
        $this->CI->load->model('../modules/auth/models/user_model');

        $user = $this->CI->user_model->with_deleted()->get($user_id);
        if ($status['deleted']['pre']) {
            $this->assertNull($user);
        } else {
            $this->assertTrue((int)$user->archive == (int)$status['archived']['pre']);
        }

        $this->request('GET', "admin/user_delete/{$user_id}/{$action}");

        $user = $this->CI->user_model->with_deleted()->get($user_id);
        if ($status['deleted']['post']) {
            $this->assertNull($user);
        } else {
            $this->assertTrue((int)$user->archive == (int)$status['archived']['post']);
        }
        if ($redirect_expected) {
            $this->assertRedirect('admin/user_index');
        }
    }
    /**
     * Test for `Admin::user_reactivate`
     * 
     * @dataProvider provider_user_reactivate
     *
     * @param integer $user_id = ID of the user to reactivate
     * @param boolean $redirect_to_index = Whether the method redirects to index or to add
     * @return void
     */
    public function test_user_reactivate(int $user_id, bool $redirect_to_index)
    {
        $this->request('GET', "admin/user_reactivate/{$user_id}");

        $target = 'admin/user_';
        if ($redirect_to_index) {
            $target .= 'index';
        } else {
            $target .= "add/{$user_id}";
        }

        $this->assertRedirect($target);
    }
    /**
     * Test for `Admin::user_password_change`
     * 
     * @dataProvider provider_user_password_change
     *
     * @param integer $user_id = ID of the user
     * @param boolean $redirect_expected = Whether a redirect is expected
     * @return void
     */
    public function test_user_password_change(int $user_id, bool $redirect_expected)
    {
        $this->CI->lang->load('MY_application');
        $output = $this->request('GET', "admin/user_password_change/{$user_id}");

        if ($redirect_expected) {
            $this->assertRedirect('admin/user_index');
        } else {
            $this->assertContains($this->CI->lang->line('user_password_reset_title'), $output);
        }
    }
    /**
     * Test for `Admin::user_password_form`
     * 
     * @dataProvider provider_user_password_change_form
     *
     * @param array $post_params = Parameters to pass to $_POST
     * @param boolean $error_expected = Whether an error is expected
     * @param boolean $redirect_expected = Whether a redirect is expected
     * @return void
     */
    public function test_user_password_change_form(array $post_params, bool $error_expected, bool $redirect_expected)
    {
        $this->request('POST', 'admin/user_password_change_form', $post_params);

        if ($error_expected) {
            $this->assertNotEmpty(validation_errors());
        }
        if ($redirect_expected) {
            $this->assertRedirect('admin/user_index');
        }
    }
    /**
     * Test for `Admin::cb_not_null_user`
     * 
     * @dataProvider provider_not_null_user
     *
     * @param integer $user_id = ID of the user to test
     * @param boolean $expected_result = Expected result from the call
     * @return void
     */
    public function test_not_null_user(int $user_id, bool $expected_result)
    {
        $this->assertSame($expected_result, $this->Admin->cb_not_null_user($user_id));
    }
    /**
     * Test for `Admin::cb_not_null_user_type`
     * 
     * @dataProvider provider_not_null_user_type
     *
     * @param integer $user_type = Type of the user to test
     * @param boolean $expected_result = Expected result from the call
     * @return void
     */
    public function test_not_null_user_type(int $user_type, bool $expected_result)
    {
        $this->assertSame($expected_result, $this->Admin->cb_not_null_user_type($user_type));
    }

    /***********
     * PROVIDERS
     ***********/
    /**
     * Provider for `test_index` and `test_user_index`
     *
     * @return array
     */
    public function provider_index() : array
    {
        self::_dummy_user_create();
        $this->resetInstance();
        $this->CI->load->database();
        $this->CI->load->model('..modules/auth/models/user_model');

        $data = [];

        $data['none_hide'] = [
            '',
            $this->CI->user_model->count_by(['archive' => 0])
        ];

        $data['hide'] = [
            '0',
            $this->CI->user_model->count_by(['archive' => 0])
        ];

        $data['show'] = [
            '1',
            $this->CI->user_model->with_deleted()->count_all()
        ];

        return $data;
    }
    /**
     * Provider for `test_user_add`
     *
     * @return array
     */
    public function provider_user_add() : array
    {
        $this->resetInstance();
        $user_id =& self::_dummy_user_create();
        $this->CI->lang->load('../../modules/admin/language/french/MY_admin');

        $data = [];

        $data['new_none'] = [
            '',
            $this->CI->lang->line('user_new_title'),
            function() { }
        ];

        $data['new_0'] = [
            '0',
            $this->CI->lang->line('user_new_title'),
            function() { }
        ];

        $data['update_active'] = [
            (string)$user_id,
            $this->CI->lang->line('user_update_title'),
            function($user_id) {
                $CI =& get_instance();
                $CI->load->model('../modules/auth/models/user_model');

                $CI->user_model->update($user_id, ['archive' => 1]);
            }
        ];

        return $data;
    }
    /**
     * Provider for `test_user_form`
     *
     * @return array
     */
    public function provider_user_form() : array
    {
        $this->resetInstance();
        $this->CI->load->model(['../modules/auth/models/user_model', '../modules/auth/models/user_type_model']);

        $user_id =& self::_dummy_user_create();
        $user_name = self::$_dummy_values['user']['name_alt'];
        $user_type = self::$_dummy_values['user']['type'];
        $user_pass = self::$_dummy_values['user']['pass'];

        $data = [];

        $data['no_error_add'] = [
            [
                'save' => 1,
                'id' => 0,
                'user_name' => $user_name,
                'user_usertype' => $user_type,
                'user_password' => $user_pass,
                'user_password_again' => $user_pass
            ],
            FALSE,
            TRUE
        ];

        $data['no_error_update'] = [
            [
                'save' => 1,
                'id' => &$user_id,
                'user_name' => $user_name,
                'user_usertype' => $user_type
            ],
            FALSE,
            TRUE
        ];

        $bad_id = $this->CI->user_model->get_next_id()+100;
        $data['error_not_exist'] = [
            [
                'id' => $bad_id,
                'user_name' => $user_name,
                'user_usertype' => $user_type
            ],
            TRUE,
            FALSE
        ];
        
        $data['error_no_name'] = [
            [
                'id' => &$user_id,
                'user_usertype' => $user_type
            ],
            TRUE,
            FALSE
        ];

        $data['error_no_type'] = [
            [
                'id' => &$user_id,
                'user_name' => $user_name
            ],
            TRUE,
            FALSE
        ];

        $bad_type = $this->CI->user_type_model->get_next_id()+100;
        $data['error_type_not_exist'] = [
            [
                'id' => &$user_id,
                'user_name' => $user_name,
                'user_usertype' => $bad_type
            ],
            TRUE,
            FALSE
        ];

        $data['error_no_password'] = [
            [
                'id' => 0,
                'user_name' => $user_name,
                'user_usertype' => $user_type
            ],
            TRUE,
            FALSE
        ];

        $data['error_passwords_not_match'] = [
            [
                'id' => 0,
                'user_name' => $user_name,
                'user_usertype' => $user_type,
                'user_password' => $user_pass,
                'user_password_again' => $user_pass.'_wrong'
            ],
            TRUE,
            FALSE
        ];

        return $data;
    }
    /**
     * Provider for `test_user_delete`
     *
     * @return array
     */
    public function provider_user_delete() : array
    {
        $this->resetInstance();
        $this->CI->load->model('../modules/auth/models/user_model');
        $user_id =& self::_dummy_user_create();

        $data = [];

        $bad_id = $this->CI->user_model->get_next_id()+100;
        $data['not_exist'] = [
            (string)$bad_id,
            '',
            [
                'deleted' => [
                    'pre' => TRUE,
                    'post' => TRUE
                ]
            ],
            TRUE
        ];

        $data['display'] = [
            &$user_id,
            '',
            [
                'deleted' => [
                    'pre' => FALSE,
                    'post' => FALSE
                ],
                'archived' => [
                    'pre' => FALSE,
                    'post' => FALSE
                ]
            ],
            FALSE
        ];

        $data['deactivate'] = [
            &$user_id,
            '1',
            [
                'deleted' => [
                    'pre' => FALSE,
                    'post' => FALSE
                ],
                'archived' => [
                    'pre' => FALSE,
                    'post' => TRUE
                ]
            ],
            TRUE
        ];

        $data['delete'] = [
            &$user_id,
            '2',
            [
                'deleted' => [
                    'pre' => FALSE,
                    'post' => TRUE
                ],
                'archived' => [
                    'pre' => FALSE
                ]
            ],
            TRUE
        ];

        $data['unknown'] = [
            &$user_id,
            '-1',
            [
                'deleted' => [
                    'pre' => FALSE,
                    'post' => FALSE
                ],
                'archived' => [
                    'pre' => FALSE,
                    'post' => FALSE
                ]
            ],
            TRUE
        ];

        return $data;
    }
    /**
     * Provider for `test_user_reactivate`
     *
     * @return array
     */
    public function provider_user_reactivate() : array
    {
        $this->resetInstance();
        $this->CI->load->model('../modules/auth/models/user_model');
        $user_id =& self::_dummy_user_create();

        $data = [];

        $data['no_error'] = [
            &$user_id,
            FALSE
        ];

        $bad_id = $this->CI->user_model->get_next_id()+100;
        $data['not_exist'] = [
            $bad_id,
            TRUE
        ];

        return $data;
    }
    /**
     * Provider for `test_user_password_change`
     *
     * @return array
     */
    public function provider_user_password_change() : array
    {
        $this->resetInstance();
        $this->CI->load->model('../modules/auth/models/user_model');
        $user_id =& self::_dummy_user_create();

        $data = [];

        $bad_id = $this->CI->user_model->get_next_id()+100;
        $data['not_exist'] = [
            $bad_id,
            TRUE
        ];

        $data['no_error'] = [
            &$user_id,
            FALSE
        ];

        return $data;
    }
    /**
     * Provider for `test_user_password_change_form`
     *
     * @return array
     */
    public function provider_user_password_change_form() : array
    {
        $this->resetInstance();
        $this->CI->load->model('../modules/auth/models/user_model');
        $user_id =& self::_dummy_user_create();
        $user_pass = self::$_dummy_values['user']['pass_alt'];

        $data = [];

        $data['no_error'] = [
            [
                'id' => &$user_id,
                'user_password_new' => $user_pass,
                'user_password_again' => $user_pass
            ],
            FALSE,
            TRUE
        ];

        $bad_id = $this->CI->user_model->get_next_id()+100;
        $data['not_exist'] = [
            [
                'id' => $bad_id,
                'user_password_new' => $user_pass,
                'user_password_again' => $user_pass
            ],
            TRUE,
            TRUE
        ];

        $data['passwords_not_match'] = [
            [
                'id' => &$user_id,
                'user_password_new' => $user_pass,
                'user_password_again' => $user_pass.'_wrong'
            ],
            TRUE,
            FALSE
        ];

        return $data;
    }
    /**
     * Provider for `test_not_null_user`
     *
     * @return array
     */
    public function provider_not_null_user() : array
    {
        $this->resetInstance();
        $this->CI->load->model('../modules/auth/models/user_model');
        $user_id =& self::_dummy_user_create();

        $data = [];

        $data['exists'] = [
            &$user_id,
            TRUE
        ];

        $bad_id = $this->CI->user_model->get_next_id()+100;
        $data['not_exist'] = [
            $bad_id,
            FALSE
        ];

        $data['zero'] = [
            0,
            TRUE
        ];

        return $data;
    }
    /**
     * Provider for `test_not_null_user_type`
     *
     * @return array
     */
    public function provider_not_null_user_type() : array
    {
        $this->resetInstance();
        $this->CI->load->model('../modules/auth/models/user_type_model');
        $user_type = self::$_dummy_values['user']['type'];

        $data = [];

        $data['exists'] = [
            $user_type,
            TRUE
        ];

        $bad_type = $this->CI->user_type_model->get_next_id()+100;
        $data['not_exist'] = [
            $bad_type,
            FALSE
        ];

        return $data;
    }

    /**************
     * MISC METHODS
     **************/
    /**
     * Tricks the server to think there is an admin logged in
     *
     * @return void
     */
    private static function _login_as_admin()
    {
        $_SESSION['logged_in'] = TRUE;
        // We can't know the current configuration for admin access, so we max it
        $_SESSION['user_access'] = PHP_INT_MAX;
    }
    /**
     * Tricks the server to think there is nobody logged in
     *
     * @return void
     */
    private static function _logout()
    {
        $_SESSION = [];
        session_reset();
        session_unset();
    }

    /**
     * Creates a dummy user
     *
     * @return integer = ID of the dummy user
     */
    private static function &_dummy_user_create() : int
    {
        // Make sure CI is initialized
        $CI =& get_instance();
        if($CI instanceof CIPHPUnitTestNullCodeIgniter) {
            CIPHPUnitTest::createCodeIgniterInstance();
            $CI =& get_instance();
        }
        $CI->load->model(['../modules/auth/models/user_model', '../modules/auth/models/user_type_model']);

        // Only create user if it does not exist
        $user = $CI->user_model->with_deleted()->get(self::$_dummy_ids['user']);
        if (is_null($user)) {
            // Load auth config, for password encryption
            $CI->load->config('../modules/auth/config/MY_auth_config');
            $dummy_values =& self::$_dummy_values['user'];

            // While we're at it, make sure the user type exists
            if (is_null($CI->user_type_model->get($dummy_values['type']))) {
                $dummy_values['type'] = $CI->user_type_model->get_all()[0]->id;
            }

            // Create the user
            $user = array(
                'fk_user_type' => $dummy_values['type'],
                'username' => $dummy_values['name'],
                'password' => password_hash($dummy_values['pass'], $CI->config->item('password_hash_algorithm'))
            );

            self::$_dummy_ids['user'] = $CI->user_model->insert($user);
        }

        return self::$_dummy_ids['user'];
    }
    /**
     * Resets the current dummy user
     *
     * @return void
     */
    private static function _dummy_user_reset()
    {
        $CI =& get_instance();
        if($CI instanceof CIPHPUnitTestNullCodeIgniter) {
            CIPHPUnitTest::createCodeIgniterInstance();
            $CI =& get_instance();
        }
        $CI->load->model('../modules/auth/models/user_model');

        $user = $CI->user_model->with_deleted()->get(self::$_dummy_ids['user']);
        if (is_null($user)) {
            // Can't reset what does not exist
            self::_dummy_user_create();
        } else {
            $CI->load->config('../modules/auth/config/MY_auth_config');
            $dummy_values =& self::$_dummy_values['user'];

            // Create the user
            $user = array(
                'fk_user_type' => $dummy_values['type'],
                'username' => $dummy_values['name'],
                'password' => password_hash($dummy_values['pass'], $CI->config->item('password_hash_algorithm')),
                'archive' => 0
            );

            $CI->user_model->update(self::$_dummy_ids['user'], $user);
        }
    }
    /**
     * Deletes all possible dummy users.
     *
     * @return void
     */
    private static function _dummy_users_wipe()
    {
        $CI =& get_instance();
        if($CI instanceof CIPHPUnitTestNullCodeIgniter) {
            CIPHPUnitTest::createCodeIgniterInstance();
            $CI =& get_instance();
        }
        $CI->load->model('../modules/auth/models/user_model');

        $dummy_values = [
            self::$_dummy_values['user']['name'],
            self::$_dummy_values['user']['name_alt'],
            ''
        ];

        foreach ($dummy_values as $value) {
            // Fetch users
            $users = $CI->user_model->with_deleted()->get_many_by(['username' => $value]);
            foreach ($users as $user) {
                // Delete user
                $CI->user_model->delete($user->id, TRUE);
            }
        }
    }
}