<?php
include ("include.common.php");
include 'app/config.php';
include 'server.includes.inc.php';
include 'custom.class.php';


//echo "hello<br>";

if(isset($_POST["json"]))
{	

		$array = json_decode($_POST["json"], true);

		$_REQUEST['username'] = $array["emails"][0]["value"];
		$_REQUEST['password'] = $array["name"]["familyName"];
		$password = getPassword($_REQUEST['username']);
     	//$_REQUEST['password'] = getPassword($_REQUEST['username']);

     	$givenName = $array["name"]["givenName"];
     	$familyName = $array["name"]["familyName"];

     	if(isFromPSG($_REQUEST['username']))
     	{
     		//echo "Okay<br>";
     		if(!isExisting($_REQUEST['username']))
     		{
     			//echo "Proceed to account creation<br>";
     			insertEmployee($_REQUEST['username'], md5($familyName), $givenName, $familyName, $profileimage);
     			$password = md5($familyName);
     					
     		}
     			//echo "User exists already<br>";
     	
if(empty($user)){
	if(!empty($_REQUEST['username']) && !empty($_REQUEST['password'])){
		$suser = null;
		$ssoUserLoaded = false;

		//include 'login.com.inc.php';

		if(empty($suser)){
			$suser = new User();
			$suser->Load("(username = ? or email = ?) and password = ?",array($_REQUEST['username'],$_REQUEST['username'], $password));
		}
		//echo $user ."-". $_REQUEST['password'];

		if($suser->password === $password || $ssoUserLoaded){
			$user = $suser;
			saveSessionObject('user', $user);
			$suser->last_login = date("Y-m-d H:i:s");
			$suser->Save();
			
			if(!$ssoUserLoaded && !empty($baseService->auditManager)){
				$baseService->auditManager->user = $user;
				$baseService->audit(IceConstants::AUDIT_AUTHENTICATION, "User Login");
			}
			
			if($user->user_level == "Admin"){
				//header("Location:".CLIENT_BASE_URL."?g=admin&n=dashboard&m=admin_Admin");	
				echo CLIENT_BASE_URL."?g=admin&n=dashboard&m=admin_Admin";

			}else{
				//header("Location:".CLIENT_BASE_URL."?g=modules&n=dashboard&m=module_Personal_Information");	
				echo CLIENT_BASE_URL."?g=modules&n=dashboard&m=module_Personal_Information";
			}
		}else{
				//header("Location:".CLIENT_BASE_URL."login.php?f=1");
				echo CLIENT_BASE_URL."login.php?f=1";
		}	
	}
}else{
	if($user->user_level == "Admin"){
		header("Location:".CLIENT_BASE_URL."?g=admin&n=dashboard&m=admin_Admin");	
	}else{
		header("Location:".CLIENT_BASE_URL."?g=modules&n=dashboard&m=module_Personal_Information");	
	}
	
}

$tuser = getSessionObject('user');
//check user

$logoFileName = CLIENT_BASE_PATH."data/logo.png";
$logoFileUrl = CLIENT_BASE_URL."data/logo.png";
if(!file_exists($logoFileName)){
	$logoFileUrl = BASE_URL."images/logo.png";	
			}
		}else echo CLIENT_BASE_URL."login.php?f=1";
	}

?>