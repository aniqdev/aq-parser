<pre><?php

var_dump(md5('kajmad'));
$wl = arrayDB("SELECT page_action FROM gig_users_page_list WHERE list_name='shoper' AND white_or_black='white'");
foreach ($wl as &$qwasd) $qwasd = $qwasd['page_action'];
print_r($wl);



?></pre>