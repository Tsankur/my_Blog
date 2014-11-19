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
		$this->posts = $this->db->query('SELECT * FROM posts WHERE id = '.(int)$id);
		$this->onePost = true;
	}
	function loadPage($id)
	{
		$this->posts = $this->db->query('SELECT * FROM posts ORDER BY id DESC LIMIT '.((int)$id*$this->postsPerPage).', '.$this->postsPerPage);
		$this->onePost = false;
		$this->currentPage = $id;
	}
	function havePosts()
	{
		return count($this->posts);
	}
	function getNextPost()
	{
		return array_shift($this->posts);
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
					$linksString .= '<a href="index.php?page='.($i+1).'">'.$i.'</a>';
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