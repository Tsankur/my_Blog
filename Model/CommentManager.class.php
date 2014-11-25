<?php

class Model_CommentManager
{
	private $db;
	private $commentCount;
	function __construct()
	{
		$this->db = new Helper_Database('config.ini');
	}
	function getCommentCount($id)
	{
		return $this->db->queryOne('SELECT count(*) as commentCount FROM comments WHERE post_id = ?', array($id));
	}
	function getComments($id)
	{
		return $this->db->query('SELECT comments.*, users.pseudo FROM comments INNER JOIN users ON comments.user_id = users.user_id WHERE post_id = ? ORDER BY id', array($id));
	}
	function addComment($post_id, $user_id, $content)
	{
		return $this->db->execute('INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)', array($post_id, $user_id, $content));
	}
	function deleteComment($id)
	{
		$this->db->execute('DELETE FROM comments WHERE id = ?', array($id));
	}
	function getLastComments($id, $last_id)
	{
		return $this->db->query('SELECT comments.*, users.pseudo FROM comments INNER JOIN users ON comments.user_id = users.user_id WHERE post_id = ? AND id > ? ORDER BY id', array($id, $last_id));
	}
}