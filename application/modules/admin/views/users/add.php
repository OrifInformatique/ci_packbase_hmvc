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

        <!-- FORM SUBMIT / CANCEL -->
        <div class="row form-group">
            <a class="btn btn-danger col-4" href="<?= base_url('admin/user_index'); ?>"><?= $this->lang->line('btn_cancel'); ?></a>
            <?= form_submit('save', $this->lang->line('btn_save'), ['class' => 'btn btn-success col-4 offset-4']); ?>
        </div>

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
        <?php } else { ?>
            <!-- ACTIVATE / DEACTIVATE EXISTING USER -->
            <div class="row form-group">
                <?= form_submit(
                    ($user->archive ? 're' : 'de').'activate',
                    $this->lang->line('btn_'.($user->archive ? 're' : 'de').'activate'),
                    ['class' => 'btn btn-'.($user->archive ? 'primary' : 'warning').' col-4']
                ); ?>
                <a href="<?= base_url('admin/user_password_change/'.$user->id); ?>" class="btn btn-secondary col-4 offset-4">
                    <?= $this->lang->line("user_password_change_title"); ?>
                </a>
            </div>
        <?php } ?>

    <?= form_close(); ?>
</div>