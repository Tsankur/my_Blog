<?php
require_once 'bootstrap.php';

session_start();
header('Content-type:application/json');
if(isset($_SESSION['isAdmin']))
{
	if(isset($_GET['id']))
	{
		$tagManager = new Model_TagManager();
		$result = $tagManager->getTags($_GET['id']);
		echo json_encode($result);
	}
}