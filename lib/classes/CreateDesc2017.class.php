<?php



/**
* 
*/
class CreateDesc2017
{
	private $_ebay_id;
	public $_steam_link;
	private $_ebay_data;
	private $_steam_de;
	private $_steam_en;
	private $_steam_fr;
	private $_steam_es;
	private $_steam_it;
	private $_data_array;
	private $_images_arr;
	private $_extra_field;
	public $error_text = 'good';

	function __construct($ebay_id = 0)
	{
		$this->_ebay_id = $ebay_id;
	}

	public function scip()
	{
		// if(  strlen($this->_steam_de['sys_req']) > 510 || 
		// 	 strlen($this->_steam_en['sys_req']) > 510 ||
		// 	 strlen($this->_steam_fr['sys_req']) > 510 ||
		// 	 strlen($this->_steam_es['sys_req']) > 510 ||
		// 	 strlen($this->_steam_it['sys_req']) > 510 ){
		// 	return false;
		// }
		if ($this->_extra_field === 'desc2017may') {
			return true;
		}
		return false;
	}

	public function getSteamLink()
	{
		$ebay_id = _esc($this->_ebay_id);
		$steam_link = arrayDB("SELECT steam_link,extra_field FROM games WHERE ebay_id = '$ebay_id'");
		if(!$steam_link) return false;
		$this->_steam_link = $steam_link[0]['steam_link'];
		$this->_extra_field = $steam_link[0]['extra_field'];
		return true;
	}

	public function getSteamLinkBySteamId($sid)
	{
		$steam_link = arrayDB("SELECT link FROM steam_de WHERE id = '$sid'");
		$this->_steam_link = $steam_link[0]['link'];
		if(!$steam_link){
			$this->error_text = "SELECT link FROM steam_de WHERE id = '$sid'";
			return false;
		}
		return true;
	}

	public function readEbayData()
	{
		$ebay_id = _esc($this->_ebay_id);
		$ebay_data = arrayDB("SELECT img3d,images,game_desc FROM ebay_data WHERE ebay_id = '$ebay_id' order by id desc limit 1");
		if(!$ebay_data) return false;
		$this->_ebay_data = $ebay_data[0];
		return true;
	}

	public function setImagesArr($images_arr)
	{
		$this->_images_arr = $images_arr;
	}

	public function readSteamDe()
	{
		$steam_link = _esc($this->_steam_link);
		$steam_de = arrayDB("SELECT * FROM steam_de WHERE link = '$steam_link'");
		if(!$steam_de) return false;
		$this->_steam_de = $steam_de[0];
		return true;
	}

	public function goDeutchToAll()
	{
		$this->_steam_en = $this->_steam_de;
		$this->_steam_fr = $this->_steam_de;
		$this->_steam_es = $this->_steam_de;
		$this->_steam_it = $this->_steam_de;
	}

	public function readSteamEn()
	{
		$steam_link = _esc($this->_steam_link);
		$steam_xx = arrayDB("SELECT * FROM steam_en WHERE link = '$steam_link'");
		if(!$steam_xx) return false;
		$this->_steam_en = $steam_xx[0];
		return true;
	}

	public function readSteamFr()
	{
		$steam_link = _esc($this->_steam_link);
		$steam_xx = arrayDB("SELECT * FROM steam_fr WHERE link = '$steam_link'");
		if(!$steam_xx) return false;
		$this->_steam_fr = $steam_xx[0];
		return true;
	}

	public function readSteamEs()
	{
		$steam_link = _esc($this->_steam_link);
		$steam_xx = arrayDB("SELECT * FROM steam_es WHERE link = '$steam_link'");
		if(!$steam_xx) return false;
		$this->_steam_es = $steam_xx[0];
		return true;
	}

	public function readSteamIt()
	{
		$steam_link = _esc($this->_steam_link);
		$steam_xx = arrayDB("SELECT * FROM steam_it WHERE link = '$steam_link'");
		if(!$steam_xx) return false;
		$this->_steam_it = $steam_xx[0];
		return true;
	}

	public function getDataArray()
	{
		$this->_data_array = [];
		$this->_data_array['title'] = $this->_steam_de['title'];
		if ($this->_steam_de['lang']) {
			$this->_data_array['langs'] = implode(', ',
				array_map(function($el){
					return get_country_code($el);},
					explode(',',$this->_steam_de['lang'])));
		}else{
			$this->_data_array['langs'] = 'EN';
		}
		$this->_data_array['plattform'] = os_shorter($this->_steam_de['os']);

		if ($this->_ebay_data && $this->_ebay_data['images']) {
			$images = explode(',', $this->_ebay_data['images']);
			$this->_data_array['img1'] = $images[0];
			$this->_data_array['img2'] = $images[1];
			$this->_data_array['img3'] = $images[2];
		}elseif ($this->_images_arr) {
			$this->_data_array['img1'] = $this->_images_arr[0];
			$this->_data_array['img2'] = $this->_images_arr[1];
			$this->_data_array['img3'] = $this->_images_arr[2];
		}else{
			return false;
		}
		if (strpos($this->_steam_link, 'sub') !== false)
			$this->_data_array['img4'] = 'http://cdn.akamai.steamstatic.com/steam/subs/'.$this->_steam_de['appid'].'/header_586x192.jpg';
		else $this->_data_array['img4'] = 'http://cdn.akamai.steamstatic.com/steam/apps/'.$this->_steam_de['appid'].'/header.jpg';

		$this->_data_array['about_de'] = add_dlc_addon_to_desc($this->_steam_de, 'de');
		$this->_data_array['about_en'] = add_dlc_addon_to_desc($this->_steam_en, 'en');
		$this->_data_array['about_fr'] = add_dlc_addon_to_desc($this->_steam_fr, 'fr');
		$this->_data_array['about_es'] = add_dlc_addon_to_desc($this->_steam_es, 'es');
		$this->_data_array['about_it'] = add_dlc_addon_to_desc($this->_steam_it, 'it');
		$this->_data_array['sydreq_de'] = str_replace(['\r\n',"\r\n"], '<br>', $this->_steam_de['sys_req']);
		$this->_data_array['sydreq_en'] = str_replace(['\r\n',"\r\n"], '<br>', $this->_steam_en['sys_req']);
		$this->_data_array['sydreq_fr'] = str_replace(['\r\n',"\r\n"], '<br>', $this->_steam_fr['sys_req']);
		$this->_data_array['sydreq_es'] = str_replace(['\r\n',"\r\n"], '<br>', $this->_steam_es['sys_req']);
		$this->_data_array['sydreq_it'] = str_replace(['\r\n',"\r\n"], '<br>', $this->_steam_it['sys_req']);
		return true;
	}

	public function getNewFullDesc()
	{
		$proto = file_get_contents('http://parser.gig-games.de/ebay-2017/index.html');
		if(!$proto) return false;

		$search = 'href="style-2017.css"';
		$replace = 'href="http://hot-body.net/gig-less/css/style-2017.css"';
		$new_full_desc = str_replace($search, $replace, $proto);

		$search = [
			'/(gig-title">).*(<\/h1><!--title-->)/',
			'/(gig-aliases">).*(<\/span><!--aliases-end-->)/',
			'/(gig-platts">).*(<\/span><!--platts-end-->)/',
			'/(data-img1a src=").*(" data-img1b)/',
			'/(data-img2a src=").*(" data-img2b)/',
			'/(data-img3a src=").*(" data-img3b)/',
			'/(data-img4a src=").*(" data-img4b)/',
			'/(gig-quelle">.+: ).*(<\/a><!--link-end-->)/',
			'/(gig-about-de">).*(<!--about-de-end-->)/',
			'/(gig-about-en">).*(<!--about-en-end-->)/',
			'/(gig-about-fr">).*(<!--about-fr-end-->)/',
			'/(gig-about-es">).*(<!--about-es-end-->)/',
			'/(gig-about-it">).*(<!--about-it-end-->)/',
			'/(gig-sys-de">).*(<!--sys-de-end-->)/',
			'/(gig-sys-en">).*(<!--sys-en-end-->)/',
			'/(gig-sys-fr">).*(<!--sys-fr-end-->)/',
			'/(gig-sys-es">).*(<!--sys-es-end-->)/',
			'/(gig-sys-it">).*(<!--sys-it-end-->)/',
			'/(item=).*(&fb=1)/',
			'/(iid=).*(&requested)/',
		];

		$replace = [
			'${1}'.$this->_data_array['title'].'$2',
			'${1}'.$this->_data_array['langs'].'$2',
			'${1}'.$this->_data_array['plattform'].'$2',
			'${1}'.$this->_data_array['img1'].'$2',
			'${1}'.$this->_data_array['img2'].'$2',
			'${1}'.$this->_data_array['img3'].'$2',
			'${1}'.$this->_data_array['img4'].'$2',
			'${1}'.$this->_steam_link.'$2',
			'${1}'.$this->_data_array['about_de'].'$2',
			'${1}'.$this->_data_array['about_en'].'$2',
			'${1}'.$this->_data_array['about_fr'].'$2',
			'${1}'.$this->_data_array['about_es'].'$2',
			'${1}'.$this->_data_array['about_it'].'$2',
			'${1}'.$this->_data_array['sydreq_de'].'$2',
			'${1}'.$this->_data_array['sydreq_en'].'$2',
			'${1}'.$this->_data_array['sydreq_fr'].'$2',
			'${1}'.$this->_data_array['sydreq_es'].'$2',
			'${1}'.$this->_data_array['sydreq_it'].'$2',
			'item='.$this->_ebay_id.'$2',
			'iid='.$this->_ebay_id.'$2',
		];

		$new_full_desc = preg_replace($search, $replace, $new_full_desc);

		return $new_full_desc;
	}




	public function run()
	{
		var_dump($this->_ebay_data);
		// sa($this->_data_array);
		// sa($this->_ebay_data);
	}
}










?>