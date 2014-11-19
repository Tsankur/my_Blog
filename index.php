<?php
function my_autoload($class)
{
	// dossier courant;
	$filePath = str_replace('_', '/', $class).'.class.php';
	if (file_exists($filePath)) 
	{
		require_once($filePath);
	}
	else
	{
		// dossier parent;
		$filePath = '../'.$filePath;
		if (file_exists($filePath)) 
		{
			require_once($filePath);
		}
		else
		{
			error_log('Class "'.$class.'" could not be autoloaded');
		}
	}
}
spl_autoload_register("my_autoload");

$options = new BlogOptions();
$templateName = $options->get('current_template');
$postsPerPage = (int)$options->get('posts_per_page');
$postManager = new Model_PostManager($postsPerPage);
if(isset($_GET['id']))
{
	$posts = $postManager->loadPost($_GET['id']);
}
else
{
	if(isset($_GET['page']))
	{
		$posts = $postManager->loadPage($_GET['page']);
	}
	else
	{
		$posts = $postManager->loadPage(0);
	}	
}
include 'content/templates/'.$templateName.'/header.phtml';
include 'content/templates/'.$templateName.'/body.phtml';
include 'content/templates/'.$templateName.'/footer.phtml';