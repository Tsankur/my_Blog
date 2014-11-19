<?php
class BlogOptions
{
	private $options;
	function __construct()
	{
		$db = new Helper_Database('config.ini');
		$options = $db->query('SELECT option_name, option_value FROM options');
				for ($i=0; $i < count($options); $i++) { 
			$this->options[$options[$i]['option_name']] = $options[$i]['option_value'];
		}
	}
	function get($optionName)
	{
		if(array_key_exists($optionName, $this->options))
		{
			return $this->options[$optionName];
		}
		else
		{
			return null;
		}
	}
}