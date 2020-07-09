<?php

/**
* 
*/
class AqsBot
{
	private static $bot_token = AQS_BOT_TOKEN;
	private static $api_url = 'https://api.telegram.org/bot';
	private static $chat_id = '278472749';
    private static $inst = null;
	// to Aniqs Channel😁 >>> -1001287057345
	// to me(Vodafone) >>> 278472749
	// to me(KyievStar) >>> 1154508851
	// to Ольга Бузова - TikTok >>> -1001449047445

	function __construct($chat_id = '') {
		if($chat_id) self::$chat_id = $chat_id;
	}


	public static function setChatId($chat_id = '')
	{
		if($chat_id) self::$chat_id = $chat_id;

		if( self::$inst == null )
        {
            self::$inst = new AqsBot();
        }
        return self::$inst;
	}


	public static function sendMessage($opts = [])
	{
		$query = [
			'chat_id' => self::$chat_id,
			'text' => 'test',
		];

		if(is_string($opts)){
			$query['text'] = $opts;
		}

		if(is_array($opts)){
			$query = array_merge($query, $opts);
		}

		$url = self::$api_url . self::$bot_token . '/sendMessage?' . http_build_query($query);

		return file_get_contents($url);
	}
	

	public static function sendPhoto($opts = [])
	{
		$query = [
			'chat_id' => self::$chat_id,
			'photo' => 'https://ireland.apollo.olxcdn.com/v1/files/sqvcip3x3xry1-UA/image;s=644x461',
		];

		if(is_string($opts)){
			$query['photo'] = $opts;
		}

		if(is_array($opts)){
			$query = array_merge($query, $opts);
		}

		$url = self::$api_url . self::$bot_token . '/sendPhoto?' . http_build_query($query);

		return file_get_contents($url);
	}


	public static function sendMediaGroup($opts = [])
	{
		$query = [
			'chat_id' => self::$chat_id,
			'media' => 'https://ireland.apollo.olxcdn.com/v1/files/sqvcip3x3xry1-UA/image;s=644x461',
		];

		if(is_string($opts)){
			$query['media'] = $opts;
		}

		if(is_array($opts)){
			$query = array_merge($query, $opts);
		}

		$url = self::$api_url . self::$bot_token . '/sendMediaGroup?' . http_build_query($query);

		return file_get_contents($url);
	}


	public static function sendVideo($opts = [])
	{
		$query = [
			'chat_id' => self::$chat_id,
			'video' => 'https://v16m.tiktokcdn.com/f10183ba0764ec99273896182317d739/5ecd49c2/video/tos/useast2a/tos-useast2a-pve-0068/7c890ed6d7f246e58518990894ef3095/?a=1233&br=3590&bt=1795&cr=0&cs=0&dr=0&ds=3&er=&l=202005261054160101890482202C01C980&lr=tiktok_m&mime_type=video%2Fmp4&qs=0&rc=M3M3Nnl3OnVodTMzOzczM0ApOzpkNDNkOTs3Nzo0NDlnOGdxL3E0L2pnMjNfLS1fMTZzc2EwMzBiNi5gMV80Xl8vMzM6Yw%3D%3D&vl=&vr=',
		];

		if(is_string($opts)){
			$query['video'] = $opts;
		}

		if(is_array($opts)){
			$query = array_merge($query, $opts);
		}

		$url = self::$api_url . self::$bot_token . '/sendVideo?' . http_build_query($query);

		return file_get_contents($url);
	}


	public static function getUpdates($opts = [])
	{
		$query = array_merge([

		], $opts);

		$url = self::$api_url . self::$bot_token . '/getUpdates?' . http_build_query($query);

		return file_get_contents($url);
	}


	public static function sendVenue($opts)
	{
		$query = array_merge([
			'chat_id' => self::$chat_id,
			'latitude' => '',
			'longitude' => '',
			'title' => '',
			'address' => '',
		], $opts);

		$url = self::$api_url . self::$bot_token . '/sendVenue?' . http_build_query($query);

		return file_get_contents($url);
	}


	public static function sendContact($opts)
	{
		$query = array_merge([
			'chat_id' => self::$chat_id,
			'phone_number' => '',
			'first_name' => '',
		], $opts);

		$url = self::$api_url . self::$bot_token . '/sendContact?' . http_build_query($query);

		return file_get_contents($url);
	}

}