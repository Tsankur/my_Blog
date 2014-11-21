<?php
function my_autoload($class)
{
	// dossier courant;
	$filePath = str_replace('_', '/', $class).'.class.php';
	if (file_exists($filePath)) 
	{
		require_once($filePath);
	}
	else
	{
		// dossier parent;
		$filePath = '../'.$filePath;
		if (file_exists($filePath)) 
		{
			require_once($filePath);
		}
		else
		{
			error_log('Class "'.$class.'" could not be autoloaded');
		}
	}
}
spl_autoload_register("my_autoload");

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
	$loginString = '<p>Admin logged as '.$_SESSION['pseudo'].' | <a href="disconnect.php">Déconnexion</a></p>';

	$images = scandir('content/images/', 0);
	$imagesString = '';
	for ($i=2; $i < count($images); $i++)
	{ 
		$imagesString .= '<img src="content/images/'.$images[$i].'"/>';
	}
	if(isset($_GET['id']))
	{
		$options = new Model_BlogOptions();
		$postsPerPage = (int)$options->get('posts_per_page');
		$postManager = new Model_PostManager($postsPerPage);
		$postManager->loadPost($_GET['id']);
		$post = $postManager->getNextPost();
		
	}
	include 'admin/admin.phtml';
}
