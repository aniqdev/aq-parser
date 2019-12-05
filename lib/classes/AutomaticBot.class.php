<?php

/**
*
example :

AutomaticBot::sendMessage('Test message 1');  // msg to gig-automatic

AutomaticBot::setChatId('-195283152');

AutomaticBot::sendMessage('Test message 2'); // msg to gig-automatic-group
*/
class AutomaticBot
{
	private static $bot_token = GIG_BOT_TOKEN;
	private static $api_url = 'https://api.telegram.org/bot';
	//чат по умолчанию для вызова статических методов
	private static $chat_id = '278472749';

	//чат по умолчанию для вызова методов объекта
	// 278472749 => gig-automatic
	// -195283152 => gig-automatic-group
	function __construct($chat_id = '278472749')
	{
		self::$chat_id = $chat_id;
	}


	public static function setChatId($chat_id = '278472749')
	{
		self::$chat_id = $chat_id;
	}


	public static function sendMessage($opts)
	{
		if (is_string($opts)) $opts = ['text' => $opts];
		
		$query = array_merge([
			'chat_id' => self::$chat_id,
			'text' => 'test',
		], $opts);

		$url = self::$api_url . self::$bot_token . '/sendMessage?' . http_build_query($query);

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