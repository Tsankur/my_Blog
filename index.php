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
$options = new Model_BlogOptions();
$templateName = $options->get('current_template');
$postsPerPage = (int)$options->get('posts_per_page');
$postManager = new Model_PostManager($postsPerPage);
if(isset($_GET['id']))
{
	$postManager->loadPost($_GET['id']);
}
else
{
	if(isset($_GET['page']))
	{
		$postManager->loadPage($_GET['page']);
	}
	else
	{
		$postManager->loadPage(0);
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