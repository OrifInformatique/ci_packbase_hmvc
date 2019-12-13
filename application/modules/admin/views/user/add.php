<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Required for config values
$this->load->module('auth');
$update = !is_null($user);
?>
<div class="container">
    <h1 class="title-section"><?= $this->lang->line('user_'.($update ? 'update' : 'new').'_title'); ?></h1>
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
        <div class="row form-group">
            <?= form_label($this->lang->line('user_name'), 'user_name', ['class' => 'form-label']); ?>
            <?= form_input('user_name', $user->username ?? '', [
                'maxlength' => $this->config->item('username_max_length'),
                'class' => 'form-control', 'id' => 'user_name'
            ]); ?>
        </div>
        <div class="row form-group">
            <?= form_label($this->lang->line('user_usertype'), 'user_usertype', ['class' => 'form-label']); ?>
            <?= form_dropdown('user_usertype', $user_types, $user->fk_user_type ?? NULL, [
                'class' => 'form-control', 'id' => 'user_usertype'
            ]); ?>
        </div>
        <?php if (!$update) { ?>
            <!-- PASSWORD ONLY FOR NEW USERS -->
            <div class="row form-group">
                <?= form_label($this->lang->line('user_password'), 'user_password', ['class' => 'form-label']); ?>
                <?= form_password('user_password', '', [
                    'class' => 'form-control', 'id' => 'user_password'
                ]); ?>
            </div>
            <div class="row form-group">
                <?= form_label($this->lang->line('user_password_again'), 'user_password_again', ['class' => 'form-label']); ?>
                <?= form_password('user_password_again', '', [
                    'maxlength' => $this->config->item('password_max_length'),
                    'class' => 'form-control', 'id' => 'user_password_again'
                ]); ?>
            </div>
        <?php } ?>

        <!-- FORM SUBMIT / CANCEL -->
        <div class="row form-group">
            <a class="btn btn-default" href="<?= base_url('admin/user_index'); ?>"><?= $this->lang->line('btn_cancel'); ?></a>&nbsp;
            <?php if ($update) { ?>
                <!-- ACTIVATE / DEACTIVATE EXISTING USER -->
                <?= form_submit(
                    ($user->archive ? 'reactivate' : 'deactivate'),
                    $this->lang->line('btn_'.($user->archive ? 'reactivate' : 'deactivate')),
                    ['class' => 'btn btn-secondary']
                ); ?>&nbsp;
                <a href="<?= base_url('admin/user_password_change/'.$user->id); ?>" class="btn btn-secondary">
                    <?= $this->lang->line("user_password_change_title"); ?>
                </a>&nbsp;
            <?php } ?>
            <?= form_submit('save', $this->lang->line('btn_save'), ['class' => 'btn btn-primary']); ?>
        </div>

    <?= form_close(); ?>
</div>