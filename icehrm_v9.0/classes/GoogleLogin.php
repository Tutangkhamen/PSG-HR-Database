<?php
include ("../include.common.php");
include("../server.includes.inc.php");

if(isset($_GET['u']) && isset($_GET['p']))
{	
		$_REQUEST['username'] = $_GET['u'];
     	$_REQUEST['password'] = $_GET['p'];
} 
echo $_REQUEST['username'];

if(empty($user)){
	if(!empty($_REQUEST['username']) && !empty($_REQUEST['password'])){
		$suser = null;
		$ssoUserLoaded = false;
		
		include 'login.com.inc.php';
		
		if(empty($suser)){
			$suser = new User();
			$suser->Load("(username = ? or email = ?) and password = ?",array($_REQUEST['username'],$_REQUEST['username'],md5($_REQUEST['password'])));
		}
		
		//echo $user ."-". $_REQUEST['password'];

		if($suser->password == md5($_REQUEST['password']) || $ssoUserLoaded){
			$user = $suser;
			saveSessionObject('user', $user);
			$suser->last_login = date("Y-m-d H:i:s");
			$suser->Save();
			
			if(!$ssoUserLoaded && !empty($baseService->auditManager)){
				$baseService->auditManager->user = $user;
				$baseService->audit(IceConstants::AUDIT_AUTHENTICATION, "User Login");
			}
			
			if($user->user_level == "Admin"){
				header("Location:".CLIENT_BASE_URL."?g=admin&n=dashboard&m=admin_Admin");	

			}else{
				header("Location:".CLIENT_BASE_URL."?g=modules&n=dashboard&m=module_Personal_Information");	
			}
		}else{
				header("Location:".CLIENT_BASE_URL."login.php?f=1");
				echo "Wrong";
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

?>