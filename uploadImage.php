<?php
header('Content-type:application/json');
if(isset($_FILES['file']))
{
	$file = $_FILES['file'];
	if($file['error'] == 0)
	{
		$allowedType = array('image/png', 'image/jpeg', 'image/jpg', 'image/gif');
		$formats = array('.png', '.jpeg', '.jpg', '.gif');
		$formatId = array_search($file['type'], $allowedType);
		if($formatId !== false && $formatId < count($formats))
		{
			$fileName = uniqid().$formats[$formatId];
			move_uploaded_file($file['tmp_name'], 'content/images/'.$fileName);
			echo json_encode($fileName);
		}
		else
		{
			error_log("format d'image non supporte : ".$file['type']);
			echo json_encode('error');
		}
	}
	else
	{
		error_log("une erreur est survenu lors de l'envoi du fichier erreur No : ".$file['error']);
		echo json_encode('error');
	}
}
else
{
	error_log("aucun fichier envoyer");
	echo json_encode('error');
}