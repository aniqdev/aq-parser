<?php


$sql_table = 'filter_langs';
$page_title = '<b>(ebay)</b>';
if (isset($_REQUEST['for']) && $_REQUEST['for'] === 'gig-games') {
    $sql_table = 'filter_langs_gig';
    $page_title = '<b>(gig-games.de)</b>';
}


if (isset($_POST['action']) && $_POST['action'] === 'save_filter_lang') {
    
    $slug = _esc(preg_replace('/[^\d\w-_]/', '', $_POST['slug']));
    $lang_de = _esc($_POST['de']);
    $lang_en = _esc($_POST['en']);
    $lang_fr = _esc($_POST['fr']);
    $lang_es = _esc($_POST['es']);
    $lang_it = _esc($_POST['it']);
    $lang_ru = _esc($_POST['ru']);

    if (arrayDB("SELECT id FROM `$sql_table` WHERE slug = '$slug' LIMIT 1")) {
        $res = arrayDB("UPDATE `$sql_table` SET
                de = '$lang_de',
                en = '$lang_en',
                fr = '$lang_fr',
                es = '$lang_es',
                it = '$lang_it',
                ru = '$lang_ru'
            WHERE slug = '$slug'");
    }else{
        $res = arrayDB("INSERT INTO `$sql_table` (slug,de,en,fr,es,it,ru)
            VALUES('$slug','$lang_de','$lang_en','$lang_fr','$lang_es','$lang_it','$lang_ru')");
    }

    echo json_encode(['post'=>$_POST,'res'=>$res,'errors'=>$_ERROS]);
    return;
}


$langs_arr = arrayDB("SELECT * FROM `$sql_table`");


?>
<style>
.fl-container{
    background: #fff;
    color: #555;
}
.aqs-display-table{
    display: table;

}
.aqs-table-row{
    display: table-row;
}
.aqs-table-row:nth-of-type(odd) {
    background-color: #f9f9f9;
}
.aqs-display-table .aqs-table-head-row{
    background: initial;
}
.aqs-table-cell{
    display: table-cell;
    padding: 8px;
    line-height: 1.42857143;
    vertical-align: top;
    border-top: 1px solid #ddd;
}
.aqs-table-head-row .aqs-table-cell{
    border-top: 0;
    vertical-align: bottom;
    border-bottom: 2px solid #ddd;
    /*text-align: center;*/
    font-weight: bold;
}
</style>
<div class="container-fluid fl-container">
    <h4 class="header-title">Filter languages <?= $page_title; ?></h4>
    <p class="text-muted">do not forget to save changes after editing</p>

    <div id="js_table" class="aqs-display-table table table-striped">
        <div class="aqs-table-row aqs-table-head-row">
            <div class="aqs-table-cell">slug</div>
            <div class="aqs-table-cell">German</div>
            <div class="aqs-table-cell">Russian</div>
            <div class="aqs-table-cell">English</div>
            <div class="aqs-table-cell">French</div>
            <div class="aqs-table-cell">Spanish</div>
            <div class="aqs-table-cell">Italian</div>
            <div class="aqs-table-cell">Save</div>
        </div>
<?php
    foreach ($langs_arr as $val) {
        echo '<form class="aqs-table-row js-save-form">
            <div class="aqs-table-cell"><input class="form-control" type="text" name="slug" value="',$val['slug'],'" readonly></div>
            <div class="aqs-table-cell"><input class="form-control" type="text" name="de" value="',htmlspecialchars($val['de']),'"></div>
            <div class="aqs-table-cell"><input class="form-control" type="text" name="ru" value="',htmlspecialchars($val['ru']),'"></div>
            <div class="aqs-table-cell"><input class="form-control" type="text" name="en" value="',htmlspecialchars($val['en']),'"></div>
            <div class="aqs-table-cell"><input class="form-control" type="text" name="fr" value="',htmlspecialchars($val['fr']),'"></div>
            <div class="aqs-table-cell"><input class="form-control" type="text" name="es" value="',htmlspecialchars($val['es']),'"></div>
            <div class="aqs-table-cell"><input class="form-control" type="text" name="it" value="',htmlspecialchars($val['it']),'"></div>
            <div class="aqs-table-cell">
                <input type="hidden" name="action" value="save_filter_lang">
                <button class="btn btn-success pull-right js-test" type="submit">Save</button>
            </div>
        </form>';
    }
?>
    </div>

    <div class="row"> 
        <div class="col-md-8">

        </div> 
        <div class="col-md-4"> 
            <form id="add_form" class="input-group"> 
                <input id="slug_input" class="form-control" placeholder="slug"> 
                <span class="input-group-btn"> 
                    <button class="btn btn-primary" type="submit">Add!</button> 
                </span> 
            </form> 
        </div> 
    </div><br>
</div><!-- /container-fluid -->

<script id="hidden_template" type="text/x-custom-template">
    <form class="aqs-table-row js-save-form">
        <div class="aqs-table-cell"><input class="form-control" type="text" name="slug" value="{{slug}}" readonly></div>
        <div class="aqs-table-cell"><input class="form-control" type="text" name="de" value=""></div>
        <div class="aqs-table-cell"><input class="form-control" type="text" name="ru" value=""></div>
        <div class="aqs-table-cell"><input class="form-control" type="text" name="en" value=""></div>
        <div class="aqs-table-cell"><input class="form-control" type="text" name="fr" value=""></div>
        <div class="aqs-table-cell"><input class="form-control" type="text" name="es" value=""></div>
        <div class="aqs-table-cell"><input class="form-control" type="text" name="it" value=""></div>
        <div class="aqs-table-cell">
            <input type="hidden" name="action" value="save_filter_lang">
            <button class="btn btn-success pull-right js-test" type="submit">Save</button>
        </div>
    </form>
</script>

<script>


// template.find('.js_slug').text(document.all.slug_input.value);

document.all.add_form.onsubmit = function(e) {
    e.preventDefault();
    var new_slug = $.trim(document.all.slug_input.value).replace( /[^a-z0-9-_]/g, '');
    var template = $('#hidden_template').html().replace('{{slug}}', new_slug);
    if(new_slug) $('#js_table').append(template);
    document.all.slug_input.value = '';
}

$('#js_table').on('submit', '.js-save-form', function(e) {
    e.preventDefault();
    var btn = $(this).find('button');
    var data = $(this).serialize();
    $.post('ajax.php?action=filter-langs&for=gig-games',
    data,
    function(data) {
        if(data.res) btn.append(' <i class="glyphicon glyphicon-saved title="saved"></i>');
        else btn.append(' <i class="glyphicon glyphicon-warning-sign title="error"></i>');
    },'json')
});

</script>