<?php

class Model_TagManager
{
	private $db;
	function __construct()
	{
		$this->db = new Helper_Database('config.ini');
	}
	function getTags($post_id=0)
	{
		if($post_id > 0)
		{
			return $this->db->query('SELECT tags.name FROM tags_relationship INNER JOIN tags ON tags_relationship.tag_id = tags.id WHERE tags_relationship.post_id = ? ORDER BY tags.name', array($post_id));
		}
		else
		{
			return $this->db->query('SELECT name FROM tags ORDER BY name');
		}
	}
	function addTag($tagName)
	{
		return $this->db->execute('INSERT INTO tags (name) VALUES (?)', array($tagName));
	}
	function deleteTag($tagName)
	{
		$ids = $this->db->queryOne('SELECT id FROM tags WHERE name = ?', array($tagName));
		if($ids)
		{
			$id = $ids['id'];
			$this->db->execute('DELETE FROM tags WHERE id = ?', array($id));
		}
	}
	function getTagId($tagName)
	{
		$ids = $this->db->queryOne('SELECT id FROM tags WHERE name = ?', array($tagName));
		if($ids)
		{
			return $ids[0];
		}
		else
		{
			return 0;
		}
	}
	function addTagRelationship($tagName, $post_id)
	{
		$ids = $this->db->queryOne('SELECT id FROM tags WHERE name = ?', array($tagName));
		if($ids)
		{
			$id = $ids['id'];
			return $this->db->execute('INSERT INTO tags_relationship (tag_id, post_id) VALUES (?, ?)', array($id, $post_id));
		}
	}
	function deleteTagRelationship($tagName, $post_id)
	{
		$ids = $this->db->queryOne('SELECT id FROM tags WHERE name = ?', array($tagName));
		if($ids)
		{
			$id = $ids['id'];
			return $this->db->execute('DELETE FROM tags_relationship WHERE tag_id = ? AND post_id = ?', array($id, $post_id));
		}
	}
}