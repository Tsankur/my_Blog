<?php
require_once 'bootstrap.php';

session_start();
$options = new Model_BlogOptions();
$templateName = $options->get('current_template');
$postsPerPage = (int)$options->get('posts_per_page');
$postManager = new Model_PostManager($postsPerPage);
$commentManager = new Model_CommentManager();
$tagManager = new Model_TagManager();
$page = 0;
if(isset($_GET['id']))
{
	$postManager->loadPost($_GET['id']);
}
else
{
	if(isset($_GET['page']))
	{
		$page = $_GET['page'];
	}
	if(isset($_GET['tag']))
	{
		$postManager->loadPosts($page, $_GET['tag']);
	}
	else
	{
		$postManager->loadPosts($page);
	}
}
//loggin header
$loginString = '<p>';
if(isset($_SESSION['pseudo']))
{
	if(isset($_SESSION['isAdmin']))
	{
		$loginString .= 'Admin ';
	}
	$loginString .= 'logged as '.$_SESSION['pseudo'].' |';
	$loginString .= ' <a href="disconnect.php">Déconnexion</a>';
}
else
{
	$loginString .= '<p><a href="login.php">Connexion</a> | <a href="register.php">Register</a></p>';
}
$loginString .= '</p>';


include 'content/templates/'.$templateName.'/header.phtml';
include 'content/templates/'.$templateName.'/body.phtml';
include 'content/templates/'.$templateName.'/footer.phtml';