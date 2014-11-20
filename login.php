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
$error = '';
if(isset($_POST['username']) && isset($_POST['password']))
{
	if(ctype_alnum($_POST['username']))
	{
		$userManager = new Model_UserManager();
		$user = $userManager->LogUser($_POST['username'], $_POST['password']);
		if($user)
		{
			$_SESSION['pseudo'] = $user['pseudo'];
			if($user['isAdmin'] == '1')
			{
				$_SESSION['isAdmin'] = 1;
			}
			header('Location:'.$_POST['referer']);
		}
		else
		{
			$referer = $_POST['referer'];
			$error = '<p class="error">Nom d\'utilisateur ou mot de passe incorect</p>';
			include 'view/loginform.phtml';
		}
	}
	else
	{
		$referer = $_POST['referer'];
		$error = '<p class="error">Nom d\'utilisateur ne doit contenir que des caractère alpha numérique</p>';
		include 'view/loginform.phtml';
	}
}
else
{
	if(isset($_GET['referer']))
	{
		$referer = $_GET['referer'];
	}
	else if(isset($_SERVER['HTTP_REFERER']))
	{
		$referer = $_SERVER['HTTP_REFERER'];
	}
	else
	{
		$referer = 'index.php';
	}
	include 'view/loginform.phtml';
}