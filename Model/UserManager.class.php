<?php
class Model_UserManager
{
	private $db;
	function __construct()
	{
		$this->db = new Helper_Database('config.ini');
	}
	function LogUser($username, $password)
	{
		$user = $this->db->queryOne('SELECT user_id, pseudo, isAdmin FROM users WHERE user_name = ? AND user_password = ?', array($username, md5($password)));
		
		return $user;
	}
	function userExist($username, $pseudo)
	{
		$user = $this->db->queryOne('SELECT pseudo FROM users WHERE user_name = ? OR pseudo = ?', array($username, $pseudo));
		
		return $user;
	}
	function RegisterUser($username, $password, $pseudo, $email)
	{
		return $this->db->execute('INSERT INTO users (user_name, user_password, pseudo, email) VALUES (?, ?, ?, ?)', array($username, md5($password), $pseudo, $email));
	}
	function GetUsers()
	{
		return $this->db->query('SELECT user_name, pseudo, email, isAdmin, user_id FROM users');
	}
}