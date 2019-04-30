<?php
$offsets = get_steam_offsets();
?><style>
.nums,.get-steam-btn{
	display: inline-block;
	width: 84px;
	text-align: center;
	cursor: pointer;
}
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
    <br>
    <div class="nums" title="last offset"><?= @(int)$offsets['steam_de'];?></div>
    <div class="nums" title="last offset"><?= @(int)$offsets['steam_en'];?></div>
    <div class="nums" title="last offset"><?= @(int)$offsets['steam_fr'];?></div>
    <div class="nums" title="last offset"><?= @(int)$offsets['steam_es'];?></div>
    <div class="nums" title="last offset"><?= @(int)$offsets['steam_it'];?></div>
    <div class="nums" title="last offset"><?= @(int)$offsets['steam_ru'];?></div>
</form><br><br><br>
<script>
$('.nums').on('click', function() {
	document.all.offset.value = $(this).text();
});
</script>
<span class="loading"></span>
<h3>Состояние процесса:</h3>
<ul id="message" class="message"><li></li></ul>