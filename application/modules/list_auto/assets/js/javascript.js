/**
* Javascript file for dynamic pages
*
* @author      Orif (jubnl)
* @link        https://github.com/OrifInformatique
* @copyright   Copyright (c), Orif (https://www.orif.ch)
* @version     1.0
*/

function sortClick(actual_sort, sort_click){
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
}

function updateURLParameter(url, param, paramVal){
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

function changeselect() {
    var nb_questions = document.getElementById("nb_items_selected").value;

    window.location = '?nb_items=' + nb_items;

    return false;
}