<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<div id="page-content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div>
                    <span class="form-header"><?= $this->lang->line('user_delete_confirm').'"'.$user->username.'" ?' ?></span>
                </div>
                <div>
                    <a href="<?= base_url('admin/user_index'); ?>" class="btn btn-default btn-lg">
                        <?= $this->lang->line('btn_cancel'); ?>
                    </a>
                    <a href="<?= base_url(uri_string().'/2'); ?>" class="btn btn-danger btn-lg">
                        <?= $this->lang->line('btn_delete'); ?>
                    </a>
                    <?php if (!$user->archive) { ?>
                    <a href="<?= base_url(uri_string().'/1'); ?>" class="btn btn-primary btn-lg">
                        <?= $this->lang->line('btn_deactivate'); ?>
                    </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>