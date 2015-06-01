<?php

if(isset($_POST["json"]))
{	
     		$array = json_decode($_POST["json"], true);
     		//print_r(array_values($array));
     		//echo $array[0];
     		echo $array["displayName"];
     		echo $array["name"]["familyName"];
     		echo $array["name"]["givenName"];
     		echo $array["emails"][0]["value"];
}
?>