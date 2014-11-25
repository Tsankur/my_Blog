<?php
require_once 'bootstrap.php';

session_start();
header('Content-type:application/json');
if(isset($_SESSION['isAdmin']))
{
	if(isset($_GET['id']))
	{
		$commentManager = new Model_CommentManager();
		$commentManager->deleteComment($_GET['id']);
		echo $_GET['id'];
	}
}