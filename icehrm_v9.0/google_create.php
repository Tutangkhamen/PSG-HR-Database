<?php
include ("include.common.php");
include 'app/config.php';
include 'server.includes.inc.php';
include 'custom.class.php';

if(isset($_GET['json']))
{	
	$json = $_GET['json'];

	$object = json_decode($json);

	echo $object->id;
	echo "found";
}

?>