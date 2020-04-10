<?php ini_get('safe_mode') or set_time_limit(2000); // Указываем скрипту, чтобы не обрывал связь.

$words = [];
foreach (json_decode(Cdvet::filter_search(),1) as $key => $val) {
	$words = array_merge($words, explode(' ', $val['title']), explode(' ', $val['short_desc']));
}
usort($words, function ($a, $b)
{
    return (strlen($a) > strlen($b)) ? -1 : 1;
});
sa(array_values(array_unique($words)));