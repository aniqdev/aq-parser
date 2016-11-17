<?php

/**
* 
*/
class QueryString
{
	private $query_string = '';


	function __construct()
	{
		$this->query_string = $_SERVER['QUERY_STRING'];
	}


	public function set($name = '', $value = '')
	{
		parse_str($this->query_string, $query_arr);
		$query_arr[$name] = $value;
		$query = http_build_query($query_arr);
		$this->query_string = preg_replace('/%5B[0-9]+%5D/simU', '[]', $query);

		return $this;
	}


	public function del($name = '', $value = '')
	{
		parse_str($this->query_string, $query_arr);

		if ($name) {
			unset($query_arr[$name]);
		}elseif ($value) {
			foreach ($query_arr as $k => $val) {
				if ($val === $value) {
					unset($query_arr[$k]);
				}
			}
		}

		$query = http_build_query($query_arr);
		$this->query_string = preg_replace('/%5B[0-9]+%5D/simU', '[]', $query);

		return $this;
	}


	public function give()
	{
		return $this->query_string;
	}
}