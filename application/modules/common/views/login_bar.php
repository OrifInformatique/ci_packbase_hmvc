<div class="container" >
  <div class="row xs-center">
    <div class="col-sm-3">
      <a href="<?php echo base_url(); ?>" ><img src="<?php echo base_url("assets/images/logo.png"); ?>" ></a>
    </div>
    <div class="col-sm-6">
      <a href="<?php echo base_url(); ?>" class="text-info"><h1><?php echo lang('app_title'); ?></h1></a>
    </div>
    <div class="col-sm-3" >
      <div class="nav nav-pills" >
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true) { ?>
          
          <!-- ADMIN ACCESS ONLY -->
          <?php if ($_SESSION['user_access'] >= ACCESS_LVL_ADMIN) { ?>
              <a href="<?php echo base_url("admin/"); ?>" ><?php echo lang('btn_admin'); ?></a><br />
          <?php } ?>
          <!-- END OF ADMIN ACCESS -->

          <!-- Logged in, display a "logout" button -->
          <a href="<?php echo base_url("auth/logout"); ?>" ><?php echo lang('btn_logout'); ?></a>

        <?php } else { ?>
          <!-- Not logged in, display a "login" button -->
          <a href="<?php echo base_url("auth/login"); ?>" ><?php echo lang('btn_login'); ?></a>
        <?php } ?>
      </div>
    </div>
  </div>
</div>
<hr />
