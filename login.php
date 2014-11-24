<?php
require_once 'bootstrap.php';

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
			$_SESSION['user_id'] = $user['user_id'];
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