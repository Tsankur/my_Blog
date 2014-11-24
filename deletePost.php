<?php
require_once 'bootstrap.php';

session_start();
header('Content-type:application/json');
if(isset($_SESSION['isAdmin']))
{
	if(isset($_GET['id']))
	{
		$options = new Model_BlogOptions();
		$postsPerPage = (int)$options->get('posts_per_page');
		$postManager = new Model_PostManager($postsPerPage);
		$postManager->deletePost($_GET['id']);
		echo $_GET['id'];
	}
}