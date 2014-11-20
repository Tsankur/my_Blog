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
	$loginString = '<p>';
	$loginString .= 'Admin ';
	$loginString .= 'logged as '.$_SESSION['pseudo'].' |';
	$loginString .= ' <a href="disconnect.php">Déconnexion</a>';
	
	$loginString .= '</p>';
	include 'admin/admin.phtml';
}
