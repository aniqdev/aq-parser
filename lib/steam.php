<?php
$offsets = get_steam_offsets();

$offsets_new = get_steam_offsets_new();

$total_count = arrayDB("SELECT count(*) FROM slist WHERE  scan = (select scan from slist order by id desc limit 1)")[0]['count(*)'];
?><style>
.nums,.get-steam-btn{
	display: inline-block;
	width: 84px;
	text-align: center;
	cursor: pointer;
}
hr{ margin: 8px; }
</style>
<h3>парсим игры Стим (подробно)</h3>
<form id="parse_steam" class="parse-steam-form">
    <button name="steam2" value="steam_de" type="button" class="js-get-steam2 get-steam-btn">Steam DE</button>
    <button name="steam2" value="steam_en" type="button" class="js-get-steam2 get-steam-btn">Steam EN</button>
    <button name="steam2" value="steam_fr" type="button" class="js-get-steam2 get-steam-btn">Steam FR</button>
    <button name="steam2" value="steam_es" type="button" class="js-get-steam2 get-steam-btn">Steam ES</button>
    <button name="steam2" value="steam_it" type="button" class="js-get-steam2 get-steam-btn">Steam IT</button>
    <button name="steam2" value="steam_ru" type="button" class="js-get-steam2 get-steam-btn">Steam RU</button>
    offset: <input type="number" id="offset" value="0" style="width: 70px; padding: 1px 5px;">
    <hr>
    <div class="nums" title="last offset"><?= @(int)$offsets['steam_de'];?></div>
    <div class="nums" title="last offset"><?= @(int)$offsets['steam_en'];?></div>
    <div class="nums" title="last offset"><?= @(int)$offsets['steam_fr'];?></div>
    <div class="nums" title="last offset"><?= @(int)$offsets['steam_es'];?></div>
    <div class="nums" title="last offset"><?= @(int)$offsets['steam_it'];?></div>
    <div class="nums" title="last offset"><?= @(int)$offsets['steam_ru'];?></div>
    <hr>
    <div class="nums" title="last offset"><?= @(int)$offsets_new['steam_de'];?></div>
    <div class="nums" title="last offset"><?= @(int)$offsets_new['steam_en'];?></div>
    <div class="nums" title="last offset"><?= @(int)$offsets_new['steam_fr'];?></div>
    <div class="nums" title="last offset"><?= @(int)$offsets_new['steam_es'];?></div>
    <div class="nums" title="last offset"><?= @(int)$offsets_new['steam_it'];?></div>
    <div class="nums" title="last offset"><?= @(int)$offsets_new['steam_ru'];?></div>
    <div class="nums">of <b><?= $total_count; ?></b></div>
</form><br><br><br>
<script>
$('.nums').on('click', function() {
	document.all.offset.value = $(this).text();
});
</script>
<span class="loading"></span>
<h3>Состояние процесса:</h3>
<ul id="message" class="message"><li></li></ul>