<?php
require_once 'bootstrap.php';

session_start();
header('Content-type:application/json');
if(isset($_POST['id']) && isset($_POST['last_id']))
{
	$commentManager = new Model_CommentManager();
	$comments =$commentManager->getLastComments($_POST['id'], $_POST['last_id']);
	echo json_encode($comments);
}