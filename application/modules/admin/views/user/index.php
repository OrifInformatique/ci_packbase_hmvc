<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Users List View
 *
 * @author      Orif, section informatique (UlSi, ViDi)
 * @link        https://github.com/OrifInformatique/gestion_questionnaires
 * @copyright   Copyright (c) Orif (http://www.orif.ch)
 */
?>
<div class="container">
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= $this->lang->line('user_list_title'); ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3 text-left">
            <a href="<?= base_url('admin/user_add'); ?>" class="btn btn-primary">
                <?= $this->lang->line('btn_add_m'); ?>
            </a>
        </div>
        <div class="col-sm-9 text-right">
            <label class="btn btn-default form-check-label" for="toggle_active">
                <?= $this->lang->line('btn_inactive_users_display'); ?>
            </label>
                <?= form_checkbox('toggle_active', '', $active_only, [
                    'id' => 'toggle_active'
                ]); ?>
            </label>
        </div>
    </div>
    <div class="row mt-2">
        <table class="table table-hover">
        <thead>
            <tr>
                <th><?= $this->lang->line('user_name'); ?></th>
                <th><?= $this->lang->line('user_usertype'); ?></th>
                <th><?= $this->lang->line('user_active'); ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody id="userlist">
            <?php foreach($users as $user) { ?>
                <tr>
                    <td><a href="<?= base_url('admin/user_add/'.$user->id); ?>"><?= $user->username; ?></td>
                    <td><?= $user_types[$user->fk_user_type]; ?></td>
                    <td><?= $this->lang->line($user->archive ? 'no' : 'yes'); ?></td>
                    <td><a href="<?= base_url('admin/user_delete/'.$user->id); ?>" class="close">×</td>
                </tr>
            <?php } ?>
        </tbody>
        </table>
    </div>
</div>
<script>
    $('#toggle_active').change(e => {
        let checked = !e.currentTarget.checked;
        $.post('admin/user_index/'+(+checked), data => {
            $('#userlist').empty();
            $('#userlist')[0].innerHTML = $(data).find('#userlist')[0].innerHTML;
        });
    });
</script>