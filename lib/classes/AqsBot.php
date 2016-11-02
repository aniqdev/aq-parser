<?php

/**
* 
*/
class AqsBot
{
	private static $bot_token = AQS_BOT_TOKEN;
	private static $api_url = 'https://api.telegram.org/bot';
	private static $chat_id = '278472749';

	function __construct() {}


	public static function send($latitude, $longitude, $title, $address)
	{
		$query = [
			'chat_id' => self::$chat_id,
			'text' => $text,
		];

		$url = self::$api_url . self::$bot_token . '/sendVenue?' . http_build_query($query);

		return file_get_contents($url);
	}


	public function venue()
	{
		$query = [
			'chat_id' => self::$chat_id,
			'latitude' => $longitude,
			'longitude' => $longitude,
			'title' => $title,
			'address' => $address,
		];

		$url = self::$api_url . self::$bot_token . '/sendMessage?' . http_build_query($query);

		return file_get_contents($url);
	}
}