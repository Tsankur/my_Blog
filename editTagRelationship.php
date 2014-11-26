<?php
require_once 'bootstrap.php';

session_start();
header('Content-type:application/json');
if(isset($_SESSION['isAdmin']))
{
	if(isset($_POST['tags']) && isset($_GET['id']))
	{
		$tagManager = new Model_TagManager();
		if(array_key_exists('toAdd', $_POST['tags']))
		{
			foreach ($_POST['tags']['toAdd'] as $value) {
				$tagManager->addTagRelationship($value, $_GET['id']);
			}
		}
		if(array_key_exists('toDelete', $_POST['tags']))
		{
			foreach ($_POST['tags']['toDelete'] as $value) {
				$tagManager->removeTagRelationship($value, $_GET['id']);
			}
		}
		echo json_encode('finished');
	}
	else
	{
		echo json_encode('error');
	}
}
else
{
	echo json_encode('not admin');
}