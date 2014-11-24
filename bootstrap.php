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
