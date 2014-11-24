<?php
require_once 'bootstrap.php';

session_start();
header('Content-type:application/json');

if(isset($_SESSION['isAdmin']))
{
	if(isset($_POST['content']) && isset($_POST['title']) && strlen($_POST['title']) > 0)
	{
		$options = new Model_BlogOptions();
		$postsPerPage = (int)$options->get('posts_per_page');
		$postManager = new Model_PostManager($postsPerPage);
		
		if(isset($_GET['id']))
		{
			$postManager->UpdatePost($_GET['id'], $_POST['title'], $_POST['content']);
			echo 'Post modifié';
		}
		else
		{
			$postId = $postManager->AddPost($_POST['title'], $_POST['content'], $_SESSION['user_id']);
			$result = array('message'=>'Post créé', 'postID'=>$postId);
			echo json_encode($result);
		}
	}
	else
	{
		echo 'Titre manquant';
	}
}