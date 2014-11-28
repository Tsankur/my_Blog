<?php
require_once 'bootstrap.php';

session_start();
header('Content-type:application/json');
if(isset($_SESSION['isAdmin']))
{
	if(isset($_POST['tags']) && isset($_GET['id']))
	{
		$tagManager = new Model_TagManager();
		$tags = json_decode($_POST['tags']);
		if(array_key_exists('toAdd', $tags))
		{
			foreach ($tags->toAdd as $value) {
				$tagManager->addTagRelationship($value, $_GET['id']);
			}
		}
		if(array_key_exists('toDelete', $tags))
		{
			foreach ($tags->toDelete as $value) {
				$tagManager->deleteTagRelationship($value, $_GET['id']);
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