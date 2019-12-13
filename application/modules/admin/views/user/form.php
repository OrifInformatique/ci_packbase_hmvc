<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Required for config values
$this->load->module('auth');
$update = !is_null($user);
?>
<div class="container">
    <!-- TITLE -->
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= lang('user_'.($update ? 'update' : 'new').'_title'); ?></h1>
        </div>
    </div>
    
    <!-- INFORMATION MESSAGE IF USER IS DISABLED -->
    <?php if ($update && $user->archive) { ?>
        <div class="col-12 alert alert-info">
            <?= lang("user_disabled_info"); ?>
        </div>
    <?php } ?>
    
    <!-- FORM OPEN -->
    <?php
    $attributes = array(
        'id' => 'user_form',
        'name' => 'user_form'
    );
    echo form_open('admin/user_form', $attributes, [
        'id' => $user->id ?? 0
    ]);
    ?>

        <!-- ERROR MESSAGES -->
        <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

        <!-- USER FIELDS -->
        <div class="row">
            <div class="col-sm-6 form-group">
                <?= form_label(lang('user_name'), 'user_name', ['class' => 'form-label']); ?>
                <?= form_input('user_name', $user->username ?? '', [
                    'maxlength' => $this->config->item('username_max_length'),
                    'class' => 'form-control', 'id' => 'user_name'
                ]); ?>
            </div>
            <div class="col-sm-6 form-group">
                <?= form_label(lang('user_usertype'), 'user_usertype', ['class' => 'form-label']); ?>
                <?= form_dropdown('user_usertype', $user_types, $user->fk_user_type ?? NULL, [
                    'class' => 'form-control', 'id' => 'user_usertype'
                ]); ?>
            </div>
        </div>
        <?php if (!$update) { ?>
            <!-- PASSWORD FIELDS ONLY FOR NEW USERS -->
            <div class="row">
                <div class="col-sm-6 form-group">
                    <?= form_label(lang('user_password'), 'user_password', ['class' => 'form-label']); ?>
                    <?= form_password('user_password', '', [
                        'class' => 'form-control', 'id' => 'user_password'
                    ]); ?>
                </div>
                <div class="col-sm-6 form-group">
                    <?= form_label(lang('user_password_again'), 'user_password_again', ['class' => 'form-label']); ?>
                    <?= form_password('user_password_again', '', [
                        'maxlength' => $this->config->item('password_max_length'),
                        'class' => 'form-control', 'id' => 'user_password_again'
                    ]); ?>
                </div>
            </div>
        <?php } else { ?>
            <div class="row">
                <!-- RESET PASSWORD FOR EXISTING USER -->
                <div class="col-12">
                    <a href="<?= base_url('admin/user_password_change/'.$user->id); ?>" >
                        <?= lang("user_password_reset_title"); ?>
                    </a>
                </div>
                
                <!-- ACTIVATE / DISABLE EXISTING USER -->
                <?php if ($user->archive) { ?>
                    <div class="col-12">
                        <a href="<?= base_url('admin/user_reactivate/'.$user->id); ?>" >
                            <?= lang("user_reactivate"); ?>
                        </a>
                    </div>
                    <div class="col-12">
                        <a href="<?= base_url('admin/user_delete/'.$user->id); ?>" class="text-danger" >
                            <?= lang("btn_hard_delete"); ?>
                        </a>
                    </div>
                <?php } else { ?>
                    <div class="col-12">
                        <a href="<?= base_url('admin/user_delete/'.$user->id); ?>" class="text-danger" >
                            <?= lang("user_delete"); ?>
                        </a>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
                    
        <!-- FORM BUTTONS -->
        <div class="row">
            <div class="col text-right">
                <a class="btn btn-default" href="<?= base_url('admin/user_index'); ?>"><?= lang('btn_cancel'); ?></a>
                <?= form_submit('save', lang('btn_save'), ['class' => 'btn btn-primary']); ?>
            </div>
        </div>
    <?= form_close(); ?>
</div>