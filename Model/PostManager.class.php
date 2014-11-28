<?php

class Model_PostManager
{
	private $db;
	private $postsPerPage;
	private $posts;
	private $pageCount;
	private $currentPage;
	private $onePost;
	function __construct($postsPerPage)
	{
		$this->onePost = false;
		$this->postsPerPage = $postsPerPage;
		$this->db = new Helper_Database('config.ini');
	}
	function loadPost($id)
	{
		$this->posts = $this->db->query('SELECT posts.*, users.pseudo FROM posts INNER JOIN users ON posts.user_id = users.user_id WHERE posts.id = ?',array($id));
		$this->onePost = true;
	}
	function loadPosts($page, $tag_name = null)
	{
		if($tag_name)
		{
			$this->posts = $this->db->query('SELECT posts.*, users.pseudo FROM tags LEFT JOIN tags_relationship ON tags_relationship.tag_id = tags.id LEFT JOIN posts ON tags_relationship.post_id = posts.id LEFT JOIN users ON posts.user_id = users.user_id WHERE tags.name = ? ORDER BY posts.id DESC LIMIT '.((int)$page*$this->postsPerPage).', '.$this->postsPerPage,array($tag_name));
			$PostCount = $this->db->queryOne('SELECT count(posts.id) as postCount FROM tags LEFT JOIN tags_relationship ON tags_relationship.tag_id = tags.id LEFT JOIN posts ON tags_relationship.post_id = posts.id WHERE tags.name = ?',array($tag_name));
		}
		else
		{
			$this->posts = $this->db->query('SELECT posts.*, users.pseudo FROM posts INNER JOIN users ON posts.user_id = users.user_id ORDER BY posts.id DESC LIMIT '.((int)$page*$this->postsPerPage).', '.$this->postsPerPage);
			$PostCount = $this->db->queryOne('SELECT count(posts.id) as postCount FROM posts');
		}
		$this->onePost = false;
		$this->currentPage = $page;
		$this->pageCount = (int)$PostCount['postCount'] / $this->postsPerPage;
	}
	function havePosts()
	{
		return count($this->posts);
	}
	function getNextPost()
	{
		$post = array_shift($this->posts);
		$content = $post['content'];
		$content = str_replace('__IMAGES__', "content/images", $content);
		
		if(!$this->onePost)
		{
			$cutPos = strpos($content, '<!-- pagebreak -->');
			if($cutPos)
			{
				$content = substr($content, 0, $cutPos + 23);
				$content = str_replace('<!-- pagebreak -->', ' ... <a href="index.php?id='.$post['id'].'">Read more.</a>', $content);
				
			}
		}
		$post['content'] = $content;
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
				$linksString .= '<a href="index.php?page='.($this->currentPage-1).'" ">« Précédent</a>';
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
	function UpdatePost($id, $title, $content)
	{
		$this->db->query('UPDATE posts SET title = ?, content = ? WHERE id = ?', array($title, $content, $id));
	}
	function AddPost($title, $content, $userID)
	{
		return $this->db->execute('INSERT INTO posts (title, content, user_id) VALUES (?, ?, ?)', array($title, $content, $userID));
	}
	function getPosts()
	{
		return $this->db->query('SELECT posts.id, posts.title, posts.date, users.pseudo FROM posts INNER JOIN users ON posts.user_id = users.user_id ORDER BY id DESC');
	}
	function deletePost($id)
	{
		$this->db->execute('DELETE FROM posts WHERE id = ?', array($id));
	}
	function getTags($id)
	{
		return $this->db->query('SELECT tags.* FROM tags INNER JOIN tagsLink ON posts.post_id = tags.id WHERE post_id = ? ORDER BY id', array($id));
	}
}