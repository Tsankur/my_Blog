<?php
class Model_BlogOptions
{
	private $options;
	private $db;
	function __construct()
	{
		$this->db = new Helper_Database('config.ini');
		$options = $this->db->query('SELECT option_name, option_value FROM options');
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
	function set($optionName, $value)
	{
		if(array_key_exists($optionName, $this->options))
		{
			$this->db->execute('UPDATE options SET option_value = ? WHERE option_name = ?', array($value, $optionName));
		}
	}
}