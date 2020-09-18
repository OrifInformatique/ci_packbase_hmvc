<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * View of items' list
 *
 * @author      Orif, section informatique (guju, vidi)
 * @link        https://github.com/OrifInformatique/gestion_questionnaires
 * @copyright   Copyright (c) Orif (http://www.orif.ch)
 * @version     1.0
 */
?>

<div class="container list_auto">
    <div class="row">
        <div id="pagination_top" class="col-sm-9"><?=$pagination?></div>
        <div class="col-sm-3">
            <!-- dropdown nb items per page -->
            <form>
                <label for="items_per_page"><?php echo $this->lang->line('items_per_page'); ?></label>
                <select onchange="set_items_per_page()" id="items_per_page" class="form-control">
                <?php
                    foreach($pagination_nb as $number) {
                        echo '<option value="'.$number.'" ';
                        if(isset($_GET['nb_items']) && $number==$_GET['nb_items']) {
                            echo "selected ";
                        }
                        echo ">".$number.'</option>';
                    }
                ?>
                </select>
            </form>
        </div>
    </div>
    <div class="row" style="margin-top:15px;">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                    <?php
                        foreach ($columns as $field_name => $field_text) {
                            echo "<th><a onclick='sortClick(\"".$field_name."\")' class='sorted_btn'>".$field_text."</a></th>";
                        }
                    ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($items) == 0) {
                        echo "<div class='alert alert-danger'>".$this->lang->line('no_items_found')."</div>";
                    } else {
                        foreach ($items as $item) {
                            echo "<tr>";
                            // Display item's fields given in $columns variable
                            foreach ($columns as $field_name => $field_text) {
                                // Manage special columns (such as edit/delete)
                                switch ($field_name) {
                                    // TODO : manage col_edit ... and others ?
                                    case "col_delete":
                                        if (!isset($delete_function)) {
                                            // The name of delete function is not given, use default function name
                                            $delete_function = "delete";
                                        }
                                        echo '<td><a href="'.base_url($controller.'/'.$delete_function.'/'.$item['id']).'" class="close">x</td>';
                                        break;
                                    default:
                                        echo "<td>".$item[$field_name]."</td>";
                                }
                            }
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div><?=$pagination?></div>
</div>

<script>
    function sortClick($sort_field){
        // TODO : Use jQuery to update items list

        /*
        var sort = "";
        if(actual_sort == sort_click + '_asc')
        {
            sort = sort_click + '_desc';
        }
        else
        {
            sort = sort_click + '_asc';
        }
        window.location =  updateURLParameter(window.location.toString(), "sort", sort);
        */
    }

    function updateURLParameter(url, param, paramVal){
        // TODO : Use jQuery to update items list
        var newAdditionalURL = "";
        var tempArray = url.split("?");
        var baseURL = tempArray[0];
        var additionalURL = tempArray[1];
        var temp = "";
        if (additionalURL) {
            tempArray = additionalURL.split("&");
            for (var i=0; i<tempArray.length; i++){
                if(tempArray[i].split('=')[0] != param){
                    newAdditionalURL += temp + tempArray[i];
                    temp = "&";
                }
            }
        }

        var rows_txt = temp + "" + param + "=" + paramVal;
        return baseURL + "?" + newAdditionalURL + rows_txt;
    }

    function set_items_per_page() {
        // TODO : Use jQuery to update items list
        alert("Fonction à créer : set_items_per_page");
    }
</script>