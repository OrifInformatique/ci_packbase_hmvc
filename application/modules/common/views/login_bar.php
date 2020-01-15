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
$modules = scandir('application/modules');
$modules = array_filter($modules, function($module) {
  // Remove hidden folders and files
  return $module[0] != '.' && is_dir("application/modules/{$module}");
});
foreach ($modules as $module) {
  if (file_exists("application/modules/{$module}/config/MY_{$module}_nav.php")) {
    $CI->load->config("{$module}/MY_{$module}_nav.php");
  }
}
// Stores navigation links for display
$navLinks = $CI->config->item('nav');
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
      <div class="nav flex-column">
				<?php foreach($navLinks as $navLink) {
					if(!$navLink['cond']()) continue; ?>
					<a href="<?=base_url($navLink['link']);?>">
						<?=lang($navLink['text']);?>
					</a>
				<?php } ?>
      </div>
    </div>
  </div>
</div>
<hr />
