<?php




/**
* 
*/
class SteamGame
{
	private $steam_link = '';
	private $steam_table = '';
	private $dom = false;
	private $data = [];

	
	function __construct($steam_link, $steam_table)
	{
		$this->steam_table = $steam_table;
		if (filter_var($steam_link, FILTER_VALIDATE_URL) !== false) {
		    $this->steam_link = $this->data['link'] = clean_steam_url($steam_link);
		    $this->game_exists = !!arrayDB("SELECT id from $steam_table where link = '$this->steam_link'");
		}else{
			// code...
		}
	}


	function isGameExists(){
		return !!arrayDB("SELECT id from $this->steam_table where link = '$this->steam_link'");
	}


	public function getDOM()
	{
		if(!$this->steam_link) return $this;

        $this->dom = file_get_html($this->steam_link, false, get_steam_context($this->steam_table));

		return $this;
	}


	public function getTitle()
	{
		if(!$this->steam_link) return $this;

		if ($title = $this->dom->find('.apphub_AppName',0)) { // для app|dls
	        $title = $title->innertext;
	    }elseif ($title = $this->dom->find('.pageheader',0)) { // для sub|bundle
	        $title = $title->innertext;
	    }else{
	        $title = '';
	    }

	    $this->data['title'] = $title;

		return $this;
	}


	public function getDescription()
	{
		if(!$this->steam_link) return $this;

		if ($this->dom->find('.apphub_AppName',0)) { // для app|dls

	        $desc = $this->dom->find('#game_area_description', 0);
	        $desc = ($desc) ? $desc->innertext : '';
	        $desc = strip_tags($desc, '<br><br/><br /><p><h2><strong><b><i><ul><li>');

	    }elseif ($this->dom->find('.pageheader',0)) { // для sub|bundle

	        $desc = [];
	        foreach ($this->dom->find('.tab_item_name') as $overlay) {
	            $desc[] = $overlay->plaintext;
	        }
	        $desc = implode('<br>', $desc);

	    }else{
	        $desc = '';
	    }

	    $this->data['desc'] = $desc;

		return $this;
	}


	public function getType()
	{
		if(!$this->steam_link) return $this;

        $appsub = explode('/', $this->steam_link)[3];

	    $main_game_title = '';
	    $main_game_link = '';
	    if ($appsub === 'app') {
	        if($glance_details = $this->dom->find('.glance_details' , 0)){
				$appsub = 'dlc';
				$main_game = $glance_details->find('a', 0);
	            $main_game_title = $main_game->plaintext;
	            $main_game_link = $main_game->href;
	        }
	    }

		$this->data['type'] = $appsub;
	    $this->data['main_game_title'] = $main_game_title;
	    $this->data['main_game_link'] = $main_game_link;
        
		return $this;
	}


	public function getAppId()
	{
		if(!$this->steam_link) return $this;

        $this->data['appid'] = explode('/', $this->steam_link)[4];
        
		return $this;
	}


	public function getPrices()
	{
		if(!$this->steam_link) return $this;

        $this->data['old_price'] = '';
        $this->data['reg_price'] = '';
		$price_block = $this->dom->find('.game_purchase_action' , 0);
		if ($discount_final_price = $this->dom->find('.discount_final_price' , 0)) {
			$this->data['old_price'] = ($dop = $this->dom->find('.discount_original_price' , 0)) ? $dop->plaintext:'0';
			$this->data['reg_price'] = $discount_final_price->plaintext;
			$this->data['old_price'] = preg_replace('/[^,\d]/', '', $this->data['old_price']);
			$this->data['reg_price'] = preg_replace('/[^,\d]/', '', $this->data['reg_price']);
	        $this->data['old_price'] = str_replace(',', '.', $this->data['old_price']);
	        $this->data['reg_price'] = str_replace(',', '.', $this->data['reg_price']);
		}else{
			$this->data['reg_price'] = $price_block->plaintext;
			$this->data['reg_price'] = preg_replace('/[^,\d]/', '', $this->data['reg_price']);
	        $this->data['reg_price'] = str_replace(',', '.', $this->data['reg_price']);
		}
        
		return $this;
	}



	public function getReleaseDate()
	{
		if(!$this->steam_link) return $this;

		$this->data['release_date'] = '';
		if ($date = $this->dom->find('.release_date .date', 0)) {
			$this->data['release_date'] = trim($date->plaintext);
			$this->data['year'] = substr($this->data['release_date'], -4);
		}elseif ($date = $this->dom->find('.details_block', 0)) {
			$this->data['release_date'] = trim($date->plaintext);
			// $this->data['release_date'] = preg_replace('/.*(\d{1,2}\.? \w{3,4}\.? \d{4}).*/s', '$1', $this->data['release_date']);
			if (preg_match('/.*(\d{1,2}\.? \w{3,4}\.? \d{4}).*/s', $this->data['release_date'], $mtchs)) {
			    $this->data['release_date'] = $mtchs[1];
				$this->data['year'] = substr($this->data['release_date'], -4);
			} else {
			    $this->data['release_date'] = '';
				$this->data['year'] = 0;
			}
		}
		

	    return $this;
	}


	public function getNotice()
	{
		if(!$this->steam_link) return $this;

	    $this->data['notice'] = ($pn = $this->dom->find('#purchase_note', 0)) ? trim($pn->plaintext) : '';

	    return $this;
	}


	public function getSpecs()
	{
		if(!$this->steam_link) return $this;

	    $this->data['specs'] = [];
	    foreach ($this->dom->find('.game_area_details_specs') as $dfbhet) $this->data['specs'][] = $dfbhet->plaintext;
	    $this->data['specs'] = implode(',', $this->data['specs']);

	    return $this;
	}


	public function getLanguages()
	{
		if(!$this->steam_link) return $this;

	// ==> Языки ($languages)
	    $languages = []; // для игр
	    foreach ($this->dom->find('.game_language_options tr[style]') as $lang_item) {
	        if (count($lang_item->find('img', 0)) > 0) {
	            $languages[] = trim($lang_item->find('td', 0)->plaintext);
	        }
	    }
	    $languages = implode(',', $languages);

	    // для паков
	    if($this->data['type'] === 'sub' || $this->data['type'] === 'bundle'){
		    $language_list = ($language_list = $this->dom->find('.language_list', 0)) ? $language_list->innertext : '';
			$language_list = strip_tags(preg_replace(['"<b[^>]*>.*</b>"','"<i[^>]*>.*</i>"'], '', $language_list));
			$languages = str_replace(', ', ',', $language_list);
	    }

	    $this->data['lang'] = trim($languages);

	    return $this;
	}


	public function getGenres()
	{
		if(!$this->steam_link) return $this;

	// ==> Жанры ($genres)
	    $genres = []; $genres_links = [];
	    $details_block = $this->dom->find('.game_details', 0);
	    if($details_block) $genres_links = $details_block->find("a[href*='genre']");
	    foreach ($genres_links as $wreds) $genres[] = trim($wreds->plaintext);
	    $genres = implode(',', $genres);

	    $this->data['genres'] = $genres;

	    return $this;
	}


	public function getDeveloper()
	{
		if(!$this->steam_link) return $this;

	    $developer = '';
	    $details_block = $this->dom->find('.game_details', 0);
	    if($details_block) $developer = $details_block->find("a[href*='developer']", 0);
	    if($developer) $developer = trim($developer->plaintext);

	    $this->data['developer'] = $developer;

	    return $this;
	}


	public function getPublisher()
	{
		if(!$this->steam_link) return $this;

	    $publisher = '';
	    $details_block = $this->dom->find('.game_details', 0);
	    if($details_block) $publisher = $details_block->find("a[href*='publisher']", 0);
	    if($publisher) $publisher = trim($publisher->plaintext);

	    $this->data['publisher'] = $publisher;

	    return $this;
	}


	public function getOs()
	{
		if(!$this->steam_link) return $this;
				
	// ==> Операционная система ($os)
	    $os = [];
	    if($this->data['type'] === 'sub' || $this->data['type'] === 'bundle'){
			$os_list = ($os_list = $this->dom->find('.tab_item_details', 0)) ? $os_list->find('.platform_img') : [];
			foreach ($os_list as $span) $os[] = str_ireplace('platform_img ', '', $span->class);
	    }else{
			$os_list = ($os_list = $this->dom->find('.game_area_purchase_platform', 0)) ? $os_list->find('.platform_img') : [];
			foreach ($os_list as $span) $os[] = str_ireplace('platform_img ', '', $span->class);
	    }
	    $os = implode(",", $os);
	    $os = str_ireplace(',hmd_separator', '', $os);

	    $this->data['os'] = $os;

	    return $this;
	}


	public function getSysReq()
	{
		if(!$this->steam_link) return $this;

	// ==> Системные требования ($sys_req)
	    $sys_req = $this->dom->find('.game_area_sys_req',0);
	    ($sys_req) ? $sys_req = $sys_req->plaintext : $sys_req = '';

	    $this->data['sys_req'] = $sys_req;

	    return $this;
	}


	public function getRatingReviews()
	{
		if(!$this->steam_link) return $this;

		// ==> Обзоры/рейтинг ($reviews, $rating)
		$recent_rating = ''; $recent_reviews = '';
		$overall_rating = ''; $overall_reviews = '';
		$reviews = $this->dom->find('div[data-store-tooltip]',0);
		if ($reviews2 = $this->dom->find('div[data-store-tooltip]',1)) {
			
			$reviews = $reviews ? $reviews->attr['data-store-tooltip'] : '';
			$reviews = str_replace('30', '', $reviews);
			if (preg_match_all("/[\d]+/", $reviews, $matches)) {
			    if (isset($matches[0][2])) {
			        $matches[0][1] = $matches[0][1].$matches[0][2]; }
			    $recent_rating = $matches[0][0];
			    $recent_reviews = $matches[0][1];
			}
			
			$reviews2 = $reviews2 ? $reviews2->attr['data-store-tooltip'] : '';
			if (preg_match_all("/[\d]+/", $reviews2, $matches)) {
			    if (isset($matches[0][2])) {
			        $matches[0][1] = $matches[0][1].$matches[0][2]; }
			    $overall_rating = $matches[0][0];
			    $overall_reviews = $matches[0][1];
			}
		}else{
			$reviews = $reviews ? $reviews->attr['data-store-tooltip'] : '';
			if (preg_match_all("/[\d]+/", $reviews, $matches)) {
			    if (isset($matches[0][2])) {
			        $matches[0][1] = $matches[0][1].$matches[0][2]; }
			    $overall_rating = $matches[0][0];
			    $overall_reviews = $matches[0][1];
			    $recent_rating = '';
			    $recent_reviews = '';
			}
		}
		$this->data['r_rating'] = $recent_rating; 
		$this->data['r_reviews'] = $recent_reviews;
		$this->data['o_rating'] = $overall_rating; 
		$this->data['o_reviews'] = $overall_reviews;

	    return $this;
	}


	public function getTags()
	{
		if(!$this->steam_link) return $this;

	    $tags_arr = [];
	    $arr = ($re = $this->dom->find('.glance_tags',0))?$re->find('a.app_tag'):[];
	    foreach ($arr as $mwqhg) $tags_arr[] = trim($mwqhg->plaintext);
	    $this->data['tags'] = implode(',', $tags_arr);

	    return $this;
	}


	public function getUsk()
	{
		if(!$this->steam_link) return $this;
		
	    $usk_links = [];
	    foreach ($this->dom->find('img[src*=ratings]') as $uu) $usk_links[] = $uu->src;
	    $usk_age = preg_replace("/[\D]+/", '', @$usk_links[0]);
	    $usk_links = implode(',', $usk_links);

		$this->data['usk_age'] = $usk_age;
		$this->data['usk_links'] = $usk_links;

	    return $this;
	}


	public function getIncludes()
	{	
		if(!$this->steam_link) return $this;
		// ==> Включаемые в пак игры ($includes)
		// сохраняет id игр через запятую 
		// формат ссылки: http://store.steampowered.com/app/10090/
		$includes = []; 
		$overlay = $this->dom->find('.tab_item_overlay');
		foreach ($overlay as $ol) $includes[] = preg_replace('/.*\/(\d+)\/.*/', '$1', $ol->href);
		$this->data['includes'] = implode(',', $includes);

	    return $this;
	}


	public function savePictures()
	{
		if(!$this->steam_link) return $this;

	    $dest = ROOT.'/steam-images/'.$this->data['type'].'s-'.$this->data['appid'];
	    $img_exists = file_exists($dest);
	    if (!$img_exists && !defined('DEV_MODE')) {
	    // if (!$img_exists) {
	    	if ($package_header = $this->dom->find('.package_header', 0)) {
	    		$img_src = $package_header->src;
	    	}else{
	        	$img_src = 'http://cdn.akamai.steamstatic.com/steam/'.$this->data['type'].'s/'.$this->data['appid'].'/header.jpg';
	    	}
	        @mkdir($dest, 0777, true);
	        $copied = copy($img_src, $dest.'/header.jpg');
	        if (!$copied){
	            $href = 'http://store.steampowered.com/'.$this->data['type'].'/'.$this->data['appid'].'/';
	            copy(ROOT.'/images/noimage.png', $dest.'/header.jpg');
	        }

	        $srcs = [];
	        foreach ($this->dom->find('a[href*=1920x1080]') as $kk => $img) {
	            $src = $img->getAttribute('href');
	            copy($src, $dest.'/big'.($kk+1).'.jpg');

	            $src = str_replace('1920x1080', '600x338', $src);
	            copy($src, $dest.'/small'.($kk+1).'.jpg');

	            if($kk > 2) break;
	        }
	    }
	    return $this;
	}


	public function save()
	{
		if(!$this->steam_link) return false;

		$prepared = array_map(function($el){return _esc(trim($el));}, $this->data);

    	$game_exists = arrayDB("SELECT id,notice from $this->steam_table where link = '$this->steam_link' LIMIT 1");
		if($game_exists){
            $steam_de_id = (int)$game_exists[0]['id'];
            arrayDB("UPDATE $this->steam_table SET
            `appid` = '$prepared[appid]',
            `type` = '$prepared[type]',
            `title` = '$prepared[title]',
            `link` = '$prepared[link]',
            `genres` = '$prepared[genres]',
            `notice` = '$prepared[notice]',
            `developer` = '$prepared[developer]',
            `publisher` = '$prepared[publisher]',
            `reg_price` = '$prepared[reg_price]',
            `old_price` = '$prepared[old_price]',
            `year` = '$prepared[year]',
            `release` = '$prepared[release_date]',
            `specs` = '$prepared[specs]',
            `lang` = '$prepared[lang]',
            `desc` = '$prepared[desc]',
            `os` = '$prepared[os]',
            `sys_req` = '$prepared[sys_req]',
            `r_rating` = '$prepared[r_rating]',
            `r_reviews` = '$prepared[r_reviews]',
            `o_rating` = '$prepared[o_rating]',
            `o_reviews` = '$prepared[o_reviews]',
            `tags` = '$prepared[tags]',
            `usk_links` = '$prepared[usk_links]',
            `usk_age` = '$prepared[usk_age]',
            `main_game_title` = '$prepared[main_game_title]',
            `main_game_link` = '$prepared[main_game_link]',
            `includes` = '$prepared[includes]'
            WHERE id = '$steam_de_id'");
        	return $steam_de_id;
        }else{
            arrayDB("INSERT INTO $this->steam_table (
            `appid`,
            `type`,
            `title`,
            `link`,
            `genres`,
            `notice`,
            `developer`,
            `publisher`,
            `reg_price`,
            `old_price`,
            `year`,
            `release`,
            `specs`,
            `lang`,
            `desc`,
            `os`,
            `sys_req`,
            `r_rating`,
            `r_reviews`,
            `o_rating`,
            `o_reviews`,
            `tags`,
            `usk_links`,
            `usk_age`,
            `main_game_title`,
            `main_game_link`,
            `includes`)
            VALUES (
            '$prepared[appid]',
            '$prepared[type]',
            '$prepared[title]',
            '$prepared[link]',
            '$prepared[genres]',
            '$prepared[notice]',
            '$prepared[developer]',
            '$prepared[publisher]',
            '$prepared[reg_price]',
            '$prepared[old_price]',
            '$prepared[year]',
            '$prepared[release_date]',
            '$prepared[specs]',
            '$prepared[lang]',
            '$prepared[desc]',
            '$prepared[os]',
            '$prepared[sys_req]',
            '$prepared[r_rating]',
            '$prepared[r_reviews]',
            '$prepared[o_rating]',
            '$prepared[o_reviews]',
            '$prepared[tags]',
            '$prepared[usk_links]',
            '$prepared[usk_age]',
            '$prepared[main_game_title]',
            '$prepared[main_game_link]',
            '$prepared[includes]')");
        	return arrayDB()->lastid();
        }
	}


	function __toString()
	{
		return '<pre>' . print_r($this->data, true) . '</pre>';
	}
}