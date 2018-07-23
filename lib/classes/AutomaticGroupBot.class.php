<?php

/**
* 
*/
class AutomaticGroupBot
{
	private static $bot_token = GIG_BOT_TOKEN;
	private static $api_url = 'https://api.telegram.org/bot';
	private static $chat_id = '-195283152';

	function __construct() {}



	//чат по умолчанию для вызова методов объекта
	// 278472749 => gig-automatic
	// -195283152 => gig-automatic-group
	public static function sendMessage($opts)
	{
		if (is_string($opts)) $opts = ['text' => $opts];
		
		$query = array_merge([
			'chat_id' => self::$chat_id,
			'parse_mode' => 'HTML',
			'text' => 'test',
		], $opts);

		$url = self::$api_url . self::$bot_token . '/sendMessage?' . http_build_query($query, null, ini_get('arg_separator.output'), PHP_QUERY_RFC3986);

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