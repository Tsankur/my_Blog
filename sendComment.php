<?php
require_once 'bootstrap.php';

session_start();
header('Content-type:application/json');
if(isset($_SESSION['pseudo']))
{
	if(isset($_POST['id']) && isset($_POST['content']))
	{
		$commentManager = new Model_CommentManager();
		echo $commentManager->addComment($_POST['id'], $_SESSION['user_id'], htmlspecialchars($_POST['content']));
	}
}