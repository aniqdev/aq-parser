<pre><?php
ini_get('safe_mode') or set_time_limit(1300); // Указываем скрипту, чтобы не обрывал связь.

$genres = get_steam_languages();


var_dump(count($genres));
print_r($genres);





?></pre>

