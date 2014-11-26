<?php
require_once 'bootstrap.php';

session_start();
header('Content-type:application/json');
if(isset($_SESSION['isAdmin']))
{
	if(isset($_GET['name']))
	{
		$tagManager = new Model_TagManager();
		$result = $tagManager->addTag($_GET['name']);
		if($result)
		{
			echo json_encode($_GET['name']);
		}
		else
		{
			echo json_encode(null);
		}
	}
}