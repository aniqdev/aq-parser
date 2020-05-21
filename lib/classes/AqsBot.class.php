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
	// Aniqs Channel😁 >>> -1001287057345
	// Aqs Bot >>> 278472749

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