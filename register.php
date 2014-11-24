<?php
require_once 'bootstrap.php';

session_start();
$error = '';
if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['pseudo']) && isset($_POST['email']))
{
	if(strlen($_POST['username']) > 0 && ctype_alnum($_POST['username']) && ctype_alnum($_POST['pseudo']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
	{
		$userManager = new Model_UserManager();
		if($userManager->userExist($_POST['username'], $_POST['pseudo']) == null)
		{
			$userManager->RegisterUser($_POST['username'], $_POST['password'], $_POST['pseudo'], $_POST['email']);
		
			$_SESSION['pseudo'] = $_POST['pseudo'];
			header('Location:'.$_POST['referer']);
		}
		else
		{
			$referer = $_POST['referer'];
			$error = '<p class="error">Nom d\'utilisateur déjà utilisé</p>';
			include 'view/registerform.phtml';
		}
	}
	else
	{
		$referer = $_POST['referer'];
		$error = '<p class="error">Nom d\'utilisateur et le pseudo ne doivent contenir que des caractère alpha numérique, l\'email doit être au bon format</p>';
		include 'view/registerform.phtml';
	}
}
else
{
	if(isset($_SERVER['HTTP_REFERER']))
	{
		$referer = $_SERVER['HTTP_REFERER'];
	}
	else
	{
		$referer = 'index.php';
	}
	include 'view/registerform.phtml';
}