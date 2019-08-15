<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <?php 
                $attributes = array("class" => "form-horizontal",
                                    "id" => "loginform",
                                    "name" => "loginform");
                echo form_open("auth/auth/login", $attributes);
            ?>
            <fieldset>
                <legend><?= $this->lang->line('page_login'); ?></legend>

                <?php if(isset($message)){ ?>
                    <div class="alert alert-danger"><?= $message; ?></div>
                <?php } ?>
                
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="username" class="control-label"><?= $this->lang->line('field_username'); ?></label>
                        </div>
                        <div class="col-lg-8">
                            <input class="form-control" id="username" name="username" placeholder="<?= $this->lang->line('field_username'); ?>" type="text" value="<?= set_value('username'); ?>" />
                            <span class="text-danger"><?= form_error('username'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-4">
                            <label for="password" class="control-label"><?= $this->lang->line('field_password'); ?></label>
                        </div>
                        <div class="col-lg-8">
                            <input class="form-control" id="password" name="password" placeholder="<?= $this->lang->line('field_password'); ?>" type="password" value="<?= set_value('password'); ?>" />
                            <span class="text-danger"><?= form_error('password'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-4 offset-lg-4 col-6">
                            <input id="btn_login" name="btn_login" type="submit" class="btn btn-primary btn-block" value="<?= $this->lang->line('btn_login'); ?>" />
                        </div>
                        <div class="col-lg-4 col-6">
                            <a id="btn_cancel" class="btn btn-danger btn-block" href="<?= base_url(); ?>"><?= $this->lang->line('btn_cancel'); ?></a>
                        </div>
                    </div>
                </div>
            </fieldset>
            <?= form_close(); ?>
        </div>
    </div>
</div>