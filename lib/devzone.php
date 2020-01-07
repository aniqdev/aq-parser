
<br><div class="container text-center"><?php 

$files = scandir(__DIR__.'/devzone');

$prev_char = '';

echo '<div class="row char-block">';
for ($i=2; $i < count($files); $i++) {
    if(stripos($files[$i], '.php') === false) continue;
	$text = str_ireplace('.php', '', $files[$i]);
    if($prev_char !== $text[0]) {
        echo '</div><div class="row char-block">';
        echo '<div class="first-char col-xs-4"><b>'.$text[0].'</b></div>';
    }
    $prev_char = $text[0];
	echo '<div class="col-xs-4"><a class="devzone-btn" href="?action=devzone/',$text,'" title="',$text,'">',$text,'</a></div>';
}
echo '</div>';

?></div>

<script>
function hashCode(str) { // java String#hashCode
    var hash = 0;
    for (var i = 0; i < str.length; i++) {
       hash = str.charCodeAt(i) + ((hash << 5) - hash);
    }
    return hash;
} 

function intToRGB(i){
    var c = (i & 0x00FFFFFF).toString(16).toUpperCase();
    return "00000".substring(0, 6 - c.length) + c;
}



// $('.devzone-btn').map(function(i,el) {
// 	var text = el.innerHTML;
// 	var color = intToRGB(hashCode(text));
// 	el.style = 'background:#'+color;
// 	// console.log(el);
// });

</script>