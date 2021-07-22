<style>
.modal-wrapper{
    position: fixed;
    top: 0;
    width: 100%;
    height: 100%;
/*     display: flex;
    align-items: center;
    justify-content: center; */
    background: #0000006b;
    display: none;
}
.modal-wrapper.active {
    display: grid;
}
.modal-wrapper.active .modal-inner{
    animation: .4s modal-show;
}
.modal-wrapper.fadde .modal-inner{
    animation: .4s modal-fadde;
}
.modal-inner{
    max-width: 600px;
    background: #2c2c2c;
    border-radius: 8px;
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -60%);
}
@keyframes modal-show {
	from{
		margin-top: -300px;
		opacity: 0;
	}
	to{
		margin-top: 0;
		opacity: 1;
	}
}
@keyframes modal-fadde {
	from{
		opacity: 1;
		margin-top: 0;
	}
	to{
		opacity: 0;
		margin-top: -200px;
	}
}
.modal-header{
	user-select: none;
}
</style>
<div class="modal-wrapper js-open-modal fadde" id="test_modal1" data-target="test_modal1" >
	<div class="modal-inner">
		<div class="modal-header">
			<h3 style="margin: 0;">
				Login
				<small class="js-open-modal" data-target="test_modal1" style="float:right;cursor:pointer">Close</small>
			</h3>
		</div>
		<div class="modal-body">
			<form>
			  <div class="form-group">
			    <label for="exampleInputEmail1">Email address</label>
			    <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
			  </div>
			  <div class="form-group">
			    <label for="exampleInputPassword1">Password</label>
			    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
			  </div>
			  <div class="form-group">
			    <label for="exampleInputFile">File input</label>
			    <input type="file" id="exampleInputFile">
			    <p class="help-block">Example block-level help text here.</p>
			  </div>
			  <div class="checkbox">
			    <label>
			      <input type="checkbox"> Check me out
			    </label>
			  </div>
			</form>
		</div>
		<div class="modal-footer">
			<a class="btn btn-default" href="#" role="button">Link</a>
			<button class="btn btn-default" type="submit">Button</button>
		</div>
	</div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function(event) {
document.querySelectorAll('.js-open-modal').forEach(function(el){
    el.addEventListener('click', function(e){
    	if(e.target !== this) return false
        if(!(modal = document.getElementById(this.dataset.target)))	return false
    	
    	if(modal.classList.toggle('fadde')){
    		setTimeout(function(){modal.classList.remove('active')}, 400)
    	}else{
    		modal.classList.add('active')
    	}
    })
})
});
</script>
<p>
	Lorem ipsum, dolor sit amet, consectetur adipisicing elit. Perferendis, possimus dignissimos amet quisquam quis odit, explicabo impedit iure esse nemo iste ad, quas quos delectus natus veniam est voluptatum necessitatibus unde vitae quia omnis. Delectus porro, doloremque sunt quis soluta libero ad iusto adipisci mollitia omnis voluptates eum inventore laborum dolores rem voluptatem corrupti id, maiores. Repellendus necessitatibus minima iure beatae in, recusandae, dicta perspiciatis blanditiis saepe. Voluptates, similique veritatis? Sed quasi obcaecati ullam a, dolorem unde, repudiandae ipsum perspiciatis vero molestiae nobis sit eos nulla nemo maiores ut eligendi at tenetur quo accusamus. Repellat voluptas explicabo eum dignissimos consectetur officiis dicta, ea eaque at modi voluptates rem reprehenderit unde porro cumque hic dolorem neque, cupiditate temporibus quam. Impedit numquam facilis cupiditate voluptatum hic, illo nobis vitae repudiandae alias fuga ut soluta natus fugit, quas distinctio a similique dignissimos aperiam corrupti expedita. Sint dignissimos aspernatur accusamus nulla, distinctio id quibusdam? Earum suscipit porro obcaecati fuga quisquam esse amet, nam, neque ratione similique ea deserunt iure et explicabo ut officia! Ipsam reprehenderit et deserunt consequatur possimus quod quidem nesciunt molestias quia harum! Consequatur sequi assumenda ex alias, totam laboriosam soluta vitae quis perspiciatis, tempore culpa consectetur dolores nostrum modi ullam voluptas eius. Eaque hic rerum fugiat corporis repellat illo nulla laborum quos minus numquam, maiores, reprehenderit saepe. Id quia consequuntur, et perferendis quae. Voluptatem, similique. Fuga ut optio dolore maxime commodi accusantium natus dolorum eius vero sed! Expedita porro esse aspernatur fugiat a assumenda accusamus ipsam, alias et ea dolore dolores error sunt soluta, deserunt necessitatibus quae voluptatibus aut eos quod facere ipsum officia pariatur, nobis. Deserunt, unde ratione animi eius suscipit neque minima laudantium eveniet obcaecati quaerat, <button class="btn btn-success js-open-modal" data-target="test_modal1" type="button">Button</button> voluptas repellat accusamus! Deleniti unde repellendus accusamus illum corrupti natus ipsa saepe. Vero, odit doloremque et nesciunt. Tenetur architecto, illum consectetur, quam repellendus ipsam laudantium. Quia odit deleniti corrupti? Deleniti ipsa odio, minus. Voluptates accusamus officia porro dolore repudiandae et, dolores maiores incidunt, delectus culpa ratione debitis, provident. Repudiandae eaque repellendus voluptates illo quia, qui et eius quas ipsam quos ipsum laborum dicta sapiente assumenda a suscipit soluta ad atque odio libero? Error, itaque odio adipisci praesentium? Aliquam sequi voluptatum, in quo dolores vel itaque et. Et tenetur officiis, fuga ipsa, numquam necessitatibus provident voluptatum, animi voluptatibus cupiditate quod. Nulla nam, necessitatibus, aliquid labore eum doloribus magni, esse, est officiis quo nihil. Quisquam molestiae vel optio rem temporibus dolorem fugit fuga officiis atque ullam mollitia, aliquam numquam, quidem voluptas aspernatur doloremque. Qui magni exercitationem, quasi rerum ducimus nisi nulla expedita, maiores sed, facere sequi. Atque veniam et eum laudantium, in ab quis iure libero aliquam sunt obcaecati quas odit doloremque nesciunt recusandae consequatur eos cupiditate harum voluptatibus modi est dignissimos, quod magni explicabo debitis! Iure fugiat quam illum porro, accusamus blanditiis asperiores vero consectetur labore, quas eius eos, aliquam, numquam. Exercitationem incidunt eius, debitis magnam earum in sapiente labore, quasi, repellendus ratione ullam libero voluptate. Ducimus vitae fuga labore recusandae excepturi ea aliquid nobis eligendi repudiandae perspiciatis voluptatem quo totam placeat neque rerum nihil distinctio asperiores animi ipsum, iure et, sequi deserunt adipisci quas. Unde, ducimus dolore ut modi obcaecati exercitationem quia earum blanditiis commodi aspernatur mollitia, fugit, at qui quod inventore cumque ad cum doloribus. Quo, reprehenderit ullam dolorem placeat ad animi non illo accusamus recusandae, at iusto laboriosam eum accusantium vitae dolor fuga. Fuga laborum ea quia labore soluta saepe ratione, deleniti. Amet ipsa modi perferendis aliquid, harum quidem neque iste odit recusandae pariatur autem, vitae facilis ipsam deserunt officiis repudiandae, libero, repellat adipisci officia. Quisquam neque beatae repellendus facere explicabo, laudantium sequi facilis quae corporis! Optio quibusdam natus doloribus deserunt mollitia quam iusto harum repellat? Aperiam quasi minima corporis totam laborum similique delectus, nam, neque voluptatibus illum placeat mollitia commodi rem temporibus quos debitis illo odit fugiat consequatur ipsa, doloribus ea ut ipsum. Veritatis tenetur temporibus, quidem. Doloremque soluta dolore eligendi veritatis atque, mollitia accusantium a, cupiditate, tempora est perspiciatis. Repudiandae minima, ut, est accusamus, eius debitis recusandae amet accusantium distinctio perspiciatis dolor itaque. Eos voluptate neque dolore reiciendis accusamus corrupti illum magnam fugit, debitis alias quisquam aspernatur commodi. Atque cumque asperiores ratione! Sequi quisquam id alias dolores nostrum repudiandae laudantium culpa laboriosam perspiciatis minus, quia a corrupti, mollitia labore, exercitationem eum impedit magnam harum. Possimus, autem libero, cupiditate excepturi eos quam, facilis omnis, minus sunt doloribus laudantium deserunt quas aliquid impedit molestias vitae porro sit. Delectus, nihil eveniet sed explicabo harum corporis, id velit doloribus, quos voluptas iure sequi placeat deleniti perspiciatis aliquam officiis praesentium recusandae nostrum. Harum, accusamus ducimus repudiandae reiciendis sequi itaque enim, unde autem rem in impedit dolor, eaque distinctio cum facilis perspiciatis dicta, quos. Amet fugiat minima quo, aliquid atque nisi veniam accusamus harum aut magnam, animi nam iste molestiae quidem temporibus omnis architecto minus? Facere temporibus nemo eaque fugit atque unde, dolore ex culpa est pariatur dolores itaque accusamus consectetur magni non modi magnam. Voluptate delectus debitis quas sed incidunt, iusto? Omnis quam nesciunt a! Incidunt iusto est minus qui perferendis modi dolorem corrupti tempora corporis in vel tenetur quo labore, et repudiandae! Velit obcaecati nulla nostrum reprehenderit, ad veritatis ratione harum aspernatur. Eos laboriosam blanditiis rerum, atque mollitia vero ipsam aperiam, ut minus aliquam dolore tempora inventore, quas molestiae, tenetur porro voluptatem accusamus voluptas maxime voluptatibus? Sequi doloribus, consectetur nostrum reiciendis accusamus assumenda aperiam? Officia odio repellat quod facilis debitis, ad, eligendi dolor dicta. Natus necessitatibus dolore libero, cumque ipsa! Adipisci corporis natus doloremque, architecto tenetur illum laboriosam porro placeat labore, voluptatibus, fuga dicta laudantium? Provident nihil, itaque dolores vel maxime ullam dignissimos. Quos odit itaque sit vitae repudiandae odio, illum accusantium officia iusto aperiam eaque illo voluptatibus nesciunt blanditiis tempora reprehenderit doloribus earum numquam praesentium. Perspiciatis sequi ex suscipit libero aliquid vero saepe, non, laboriosam, hic dolore, veritatis culpa. Nam exercitationem, consectetur modi voluptate ut praesentium deserunt nemo pariatur dolores doloremque, quasi, commodi cupiditate temporibus molestias impedit explicabo placeat perspiciatis ab aperiam, molestiae amet omnis ipsa ea? Alias dolores voluptas distinctio ullam enim optio asperiores, sunt aperiam reprehenderit ipsa tempora doloribus. Sapiente.
</p>
<?php 
return;
function tui_parameters($data = []){
	$data = array_merge([
	 'samo_action' => 'PRICES',
	  'TOWNFROMINC' => '274286',
	  'STATEINC' => '210357',
	  'TOURTYPE' => '0',
	  'TOURINC' => '0',
	  'PROGRAMINC' => '0',
	  'CHECKIN_BEG' => '20210410',
	  'NIGHTS_FROM' => '4',
	  'CHECKIN_END' => '20210410',
	  'NIGHTS_TILL' => '7',
	  'ADULT' => '2',
	  'CURRENCY' => '1',
	  'CHILD' => '0',
	  'TOWNS_ANY' => '1',
	  'TOWNS' => '',
	  'STARS_ANY' => '1',
	  'STARS' => '',
	  'hotelsearch' => '0',
	  'HOTELS_ANY' => '1',
	  'HOTELS' => '',
	  'MEALS_ANY' => '0',
	  'MEALS' => '10004',
	  'ROOMS_ANY' => '1',
	  'ROOMS' => '',
	  'FREIGHT' => '1',
	  'FILTER' => '1',
	  'MOMENT_CONFIRM' => '0',
	  'WITHOUT_PROMO' => '0',
	  'UFILTER' => '',
	  'HOTELTYPES' => '',
	  'PARTITION_PRICE' => '224',
	  'PRICEPAGE' => '1',
	  'rev' => '1641272844',
	], $data);
	return http_build_query($data);
}

$url = 'https://b2b.tui.ru/search_tour?';
$parameters = [
	'CHECKIN_BEG' => date('Ymd', time() + (60*60*24*2)),
	'CHECKIN_END' => date('Ymd', time() + (60*60*24*2)),
	'NIGHTS_FROM' => 1,
	'NIGHTS_TILL' => 1,
	'ADULT' => 2,
	'CHILD' => 0,
	'TOWNS' => 'NaN,319441,319438,319444,319447', // Анапа
];
$query = tui_parameters($parameters);
// $query = 'samo_action=PRICES&TOWNFROMINC=274286&STATEINC=210357&TOURTYPE=0&TOURINC=0&PROGRAMINC=0&CHECKIN_BEG=20210405&NIGHTS_FROM=1&CHECKIN_END=20210405&NIGHTS_TILL=14&ADULT=2&CURRENCY=1&CHILD=0&TOWNS_ANY=0&TOWNS=NaN%2C319441%2C319438%2C319444%2C319447&STARS_ANY=1&STARS=&hotelsearch=0&HOTELS_ANY=1&HOTELS=&MEALS_ANY=0&MEALS=10004&ROOMS_ANY=1&ROOMS=&FREIGHT=1&FILTER=1&MOMENT_CONFIRM=0&WITHOUT_PROMO=0&UFILTER=&HOTELTYPES=&PARTITION_PRICE=224&PRICEPAGE=1&rev=1641272844';
parse_str($query, $query_arr);

$parameters2 = [
	'CHECKIN_BEG' => date('Ymd', time() + (60*60*24*2)),
	'CHECKIN_END' => date('Ymd', time() + (60*60*24*2)),
	'NIGHTS_FROM' => 7,
	'NIGHTS_TILL' => 7,
	'ADULT' => 2,
	'CHILD' => 0,
	'TOWNS' => 'NaN,319474,434539,319480,319483,319486,355102,355101,319492', // Сочи
];
$query2 = tui_parameters($parameters2);
// $query2 = 'samo_action=PRICES&TOWNFROMINC=274286&STATEINC=210357&TOURTYPE=0&TOURINC=0&PROGRAMINC=0&CHECKIN_BEG=20210406&NIGHTS_FROM=7&CHECKIN_END=20210406&NIGHTS_TILL=10&ADULT=2&CURRENCY=1&CHILD=0&TOWNS_ANY=0&TOWNS=NaN%2C319474%2C434539%2C319480%2C319483%2C319486%2C355102%2C355101%2C319492&STARS_ANY=1&STARS=&hotelsearch=0&HOTELS_ANY=1&HOTELS=&MEALS_ANY=0&MEALS=10004&ROOMS_ANY=1&ROOMS=&FREIGHT=1&FILTER=1&MOMENT_CONFIRM=0&WITHOUT_PROMO=0&UFILTER=&HOTELTYPES=&PARTITION_PRICE=224&PRICEPAGE=1&rev=1641272844';
parse_str($query2, $query_arr2);
// xa($query_arr2);

// sa(array_diff($query_arr2, $query_arr));

?>
<div class="container">
	<div class="row">
		<div class="col-sm-6"><?= sa($query_arr); ?></div>
		<div class="col-sm-6"><?= sa($query_arr2); ?></div>
	</div>
</div>
<div class="container-fluid" style="max-width: 1600px;">
	<div class="row">
		<div class="col-sm-6">
		<?php
			$product_data = get_tui_data($parameters);
			sa($product_data);
		?>
		</div>
		<div class="col-sm-6">
		<?php 
			$product_data = get_tui_data($parameters2);
			sa($product_data);
	 	?>
	 	</div>
	</div>
</div>
<?php

function tui_get_dom($query)
{
	// $html = file_get_contents('https://b2b.tui.ru/search_tour?' . $query);
	$html = file_get_contents(ROOT.'/Files/tui-table.html');
	$html = trim($html);
	$html = preg_replace('/^.+ehtml\(/', '', $html);
	$html = preg_replace('/table>.+$/', '<\/table>"', $html);
	$html = str_replace('\n', '', $html);
	$html = str_replace('  ', ' ', $html);
	$html = trim($html);
	$html = json_decode($html);
	// echo ($html);
	return str_get_html($html);
}

function get_tui_data($parameters){
	$query = tui_parameters($parameters);
	$dom = tui_get_dom($query);
	$variants = [];
	$hotels = [];
	if($dom){
		echo '<hr>';
		$trs = $dom->find('tbody tr');
		sa(count($trs));
		if($trs){
			foreach ($trs as $trs_key => $tr) {
				if($trs_key > 5) break;
				$res = [];
				$tour = $tr->find('.tour', 0);
				if($tour){
					$res['tour'] = trim($tour->plaintext);
					$res['nights'] = trim($tour->next_sibling()->plaintext);
					if($res['nights'] < $parameters['NIGHTS_FROM'] || $res['nights'] > $parameters['NIGHTS_TILL']){
						// continue;
					} 
				} 
				$hotel = $tr->find('.link-hotel a', 0);
				if($hotel) $res['hotel'] = trim($hotel->plaintext);
				if($hotel) $res['link'] = trim($hotel->href);
				$desc = $tr->find('td', 7);
				if($desc) $res['desc'] = trim($desc->plaintext);
				$price = $tr->find('.price', 0);
				if($price) $res['price'] = preg_replace('/\D/', '', trim($price->plaintext));
				// sa($res);
				$variants[] = $res;
				if($hotel) $hotels[] = $res['hotel'];
			}
		}
	}
	if (isset($variants[0]['link'])) {
		$images = get_hotel_images($variants[0]['link']);
	}else{
		$images = [];
	}
	return [
		'hotels' => $hotels,
		'variants' => $variants,
		'images' => $images,
	];
}


function get_hotel_images($hotel_link)
{
	return [];
	$hotel_dom = file_get_html($hotel_link);
	$ret_arr = [];
	if ($hotel_dom) {
		$links = $hotel_dom->find('.ad-thumb-list a');
		foreach ($links as $key => $link) {
			if($key >= 4) break;
			$img_src = preg_replace('/\?.+/', '', $link->href);
			$img_src = 'https://agent.tui.ru/' . $img_src;
			$ret_arr[] = $img_src;
		}
	}
	return $ret_arr;
}