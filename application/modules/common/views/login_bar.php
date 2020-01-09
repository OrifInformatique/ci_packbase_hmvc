<?php
$CI =& get_instance();
if (ENVIRONMENT !== 'testing') {
	$CI->config->load('user/MY_user_config');
	$CI->lang->load('user/MY_user');
} else {
	// CI-PHPUnit checks from application/folder instead of module/folder
	$CI->config->load('../modules/user/config/MY_user_config');
	$CI->lang->load('../../modules/user/language/french/MY_user');
}
?>
<div class="container" >
  <div class="row xs-center">
    <div class="col-sm-3">
      <a href="<?php echo base_url(); ?>" ><img src="<?php echo base_url("assets/images/logo.png"); ?>" ></a>
    </div>
    <div class="col-sm-6">
      <a href="<?php echo base_url(); ?>" class="text-info"><h1><?php echo lang('app_title'); ?></h1></a>
    </div>
    <div class="col-sm-3" >
      <div class="nav flex-column" >
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) { ?>
          
          <!-- ADMIN ACCESS ONLY -->
          <?php if ($_SESSION['user_access'] >= $CI->config->item('access_lvl_admin')) { ?>
              <a href="<?php echo base_url("user/admin/list_user"); ?>" ><?php echo lang('btn_admin'); ?></a><br />
          <?php } ?>
          <!-- END OF ADMIN ACCESS -->

          <!-- Logged in, display a "change password" button -->
          <a href="<?php echo base_url("user/auth/change_password"); ?>" ><?php echo lang('btn_change_my_password'); ?></a>
          <!-- and a "logout" button -->
          <a href="<?php echo base_url("user/auth/logout"); ?>" ><?php echo lang('btn_logout'); ?></a><br />

        <?php } else { ?>
          <!-- Not logged in, display a "login" button -->
          <a href="<?php echo base_url("user/auth/login"); ?>" ><?php echo lang('btn_login'); ?></a>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<hr />
