<?php

class Model_PostManager
{
	private $db;
	private $postsPerPage;
	private $posts;
	private $pageCount;
	private $currentPage;
	private $onePost;
	private $commentCount;
	function __construct($postsPerPage)
	{
		$this->onePost = false;
		$this->postsPerPage = $postsPerPage;
		$this->db = new Helper_Database('config.ini');
		$postCount = $this->db->queryOne('SELECT count(*) FROM posts');
		$this->pageCount = (int)$postCount['count(*)'] / $postsPerPage;
	}
	function loadPost($id)
	{
		$this->posts = $this->db->query('SELECT posts.*, users.pseudo FROM posts INNER JOIN users ON posts.user_id = users.user_id WHERE posts.id = ?',array($id));
		$this->onePost = true;
	}
	function loadPage($id)
	{
		$this->posts = $this->db->query('SELECT posts.*, users.pseudo FROM posts INNER JOIN users ON posts.user_id = users.user_id ORDER BY posts.id DESC LIMIT '.((int)$id*$this->postsPerPage).', '.$this->postsPerPage);
		$this->onePost = false;
		$this->currentPage = $id;
	}
	function havePosts()
	{
		return count($this->posts);
	}
	function getNextPost()
	{
		$post = array_shift($this->posts);
		if(!$this->onePost)
		{
			$content = $post['content'];
			if(strlen($content) > 500)
			{
				$content = substr($content, 0, 500);
				$content .= ' ... <a href="index.php?id='.$post['id'].'">Read more.</a>';
				$post['content'] = $content;
			}
		}
		return $post;
	}
	function isOnePost()
	{
		return $this->onePost;
	}
	function getPagedLinks()
	{
		$linksString = '';
		if(!$this->onePost)
		{
			$linksString .='<nav class="clearfix">';
			if($this->currentPage == 0)
			{
				$linksString .= '<span class="disabled">« Précédent</span>';
			}
			else
			{
				$linksString .= '<a href="index.php?page=0" ">« Précédent</a>';
			}
			for ($i=0; $i < $this->pageCount; $i++)
			{
				if($i == $this->currentPage)
				{
					$linksString .= '<span class="disabled">'.($i+1).'</span>';
				}
				else
				{
					$linksString .= '<a href="index.php?page='.$i.'">'.($i+1).'</a>';
				}
			}
			if($this->currentPage >= $this->pageCount - 1)
			{
				$linksString .= '<span class="disabled">Suivant »</span>';
			}
			else
			{
				$linksString .= '<a href="index.php?page='.($this->currentPage+1).'">Suivant »</a>';
			}
			$linksString .='</nav>';
		}
		return $linksString;
	}
}