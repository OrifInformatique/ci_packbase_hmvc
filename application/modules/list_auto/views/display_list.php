<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * View of items' list
 *
 * @author      Orif, section informatique (jubnl)
 * @link        https://github.com/OrifInformatique/gestion_questionnaires
 * @copyright   Copyright (c) Orif (http://www.orif.ch)
 * @version     1.0
 */
?>
<div class="row">
    <div id="pagination_top" class="col-sm-9"><?=$pagination?></div>
    <div class="col-sm-3">
        <!-- dropdown nb items per page -->
        <form onsubmit="return changeselect()">
            <b class="form-label"><?php echo $this->lang->line('item_per_page'); ?></b>
            <select onchange="changeselect()" id="nb_items_selected" class="form-control">
                <?php
                    foreach($pagination_nb as $object){
                        ?>
                        <option value='<?php echo $object?>' <?php if(isset($_GET['nb_items'])){if($object==$_GET['nb_items']){echo"selected";}}; ?>><?php echo $object?></option>
                        <?php
                        }
                        ?>
            </select>
        </form>
    </div>
</div>
<div class="row">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <?php
                    foreach($thead as $thead){ 
                        echo $thead; 
                        echo "<a onclick='sortClick(\"".(isset($_GET['sort'])?$_GET['sort']."\"":"\"").", \"Table_auto\")' class='sorted_btn btn btn-default'>$item_sort</a>";
                    }
                    ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $compteur = 0;
                foreach ($items as $key => $item) {
                    $compteur ++;
                    displayItem($item);
                }
                ?>
            </tbody>
        </table>
        <?php
        if($compteur == 0){
            echo "<div class='alert alert-danger'>"
                . $this->lang->line('no_items_found') . "</div>";
        }
        ?>
    </div>
</div>
<div><?=$pagination?></div>
