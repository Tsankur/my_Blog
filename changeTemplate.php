<?php
require_once 'bootstrap.php';

session_start();

if(isset($_SESSION['isAdmin']))
{
	if(isset($_GET['templateName']))
	{
		$options = new Model_BlogOptions();
		$options->set('current_template', $_GET['templateName']);
	}
}