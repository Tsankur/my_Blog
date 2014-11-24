<?php
require_once 'bootstrap.php';

session_start();


if(!isset($_SESSION['isAdmin']))
{
	if(!isset($_SESSION['pseudo']))
	{
		header('Location:login.php?referer=admin.php');
	}
	else
	{
		header('Location:index.php');
	}
}
else
{
	$options = new Model_BlogOptions();
	$templateName = $options->get('current_template');
	$postId = 0;
	$loginString = '<p>Admin logged as '.$_SESSION['pseudo'].' | <a href="disconnect.php">Déconnexion</a></p>';

	//images
	$images = scandir('content/images/', 0);
	$imagesString = '';
	for ($i=2; $i < count($images); $i++)
	{ 
		$imagesString .= '<img src="content/images/'.$images[$i].'"/>';
	}
	//templates
	$templates = scandir('content/templates', 0);
	$templatesString = '';
	for ($i=2; $i < count($templates); $i++)
	{
		$templatesString .= '<div class="template'.($templateName == $templates[$i]?' selected':'').'"><p>'.$templates[$i].'</p> <img src="content/templates/'.$templates[$i].'/screenshot.png"/></div>';
	}
	//users
	$userManager = new Model_UserManager();
	$users = $userManager->GetUsers();
	$usersString = '';
	for ($i=0; $i < count($users); $i++)
	{
		$usersString .= '<tr><td>'.$users[$i]['user_name'].'</td><td>'.$users[$i]['pseudo'].'</td><td>'.$users[$i]['email'].'</td><td>'.($users[$i]['isAdmin']?'oui':'non').'</td></tr>';
	}
	//posts editor
	$postsPerPage = (int)$options->get('posts_per_page');
	$postManager = new Model_PostManager($postsPerPage);
	
	if(isset($_GET['id']))
	{
		$postManager->loadPost($_GET['id']);
		if($postManager->havePosts())
		{
			$post = $postManager->getNextPost();
			$postId = $_GET['id'];
		}
	}
	$posts = $postManager->getPosts();
	$postsString = '';
	for ($i=0; $i < count($posts); $i++)
	{
		$postsString .= '<tr data_id="'.$posts[$i]['id'].'"><td>'.$posts[$i]['id'].'</td><td>'.$posts[$i]['title'].'</td><td>'.$posts[$i]['pseudo'].'</td><td>'.$posts[$i]['date'].'</td><td><button class="edit" type="button" data="'.$posts[$i]['id'].'">Edit</button></td><td><button class="delete" type="button" data="'.$posts[$i]['id'].'">X</button></td></tr>';
	}

	include 'admin/admin.phtml';
}
