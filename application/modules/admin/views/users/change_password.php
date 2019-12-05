<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
// Required for config values
$this->load->module('auth');
?>
<div class="container">
    <h1 class="title-section"><?= $this->lang->line('user_password_change_title'); ?></h1>
    <?php
    $attributes = array(
        'id' => 'user_change_password_form',
        'name' => 'user_change_password_form'
    );
    echo form_open('admin/user_password_change_form', $attributes, [
        'id' => $user->id ?? 0
    ]);
    ?>
        <!-- SUBMIT / CANCEL -->
        <div class="row form-group">
            <a name="cancel" class="btn btn-danger col-4" href="<?= base_url('admin/user_index'); ?>"><?= $this->lang->line('btn_cancel'); ?></a>
            <?= form_submit('save', $this->lang->line('btn_save'), ['class' => 'btn btn-success col-4 offset-4']); ?>
        </div>

        <!-- ERRORS -->
        <?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

        <!-- PASSWORD -->
        <div class="row form-group">
            <?= form_label($this->lang->line('user_password'), 'user_password_new', ['class' => 'form-label']); ?>
            <?= form_password('user_password_new', '', [
                'class' => 'form-control', 'id' => 'user_password_new',
                'maxlength' => $this->config->item('password_max_length')
            ]); ?>
        </div>
        <div class="row form-group">
                <?= form_label($this->lang->line('user_password_again'), 'user_password_again', ['class' => 'form-label']); ?>
                <?= form_password('user_password_again', '', [
                    'class' => 'form-control', 'id' => 'user_password_new',
                    'maxlength' => $this->config->item('password_max_length')
                ]); ?>
        </div>
    <?= form_close(); ?>
</div>