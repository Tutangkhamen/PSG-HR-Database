<?php
include ("include.common.php"); 
include("server.includes.inc.php");

if(empty($user)){
	if(!empty($_REQUEST['username']) && !empty($_REQUEST['password'])){
		$suser = null;
		$ssoUserLoaded = false;
		
		include 'login.com.inc.php';
		
		if(empty($suser)){
			$suser = new User();
			$suser->Load("(username = ? or email = ?) and password = ?",array($_REQUEST['username'],$_REQUEST['username'],md5($_REQUEST['password'])));
		}
		
		echo $user ."-". $_REQUEST['password'];

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
	$logoFileUrl = BASE_URL."images/home-logo.png";	
}

?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>PSG | Straps</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <!--<meta name="google-signin-client_id" content="870054181676-vhlg23uogu0v6q08k4702r5k28e87n5u.apps.googleusercontent.com"> -->

    <!-- Le styles -->
    <link href="<?=BASE_URL?>bootstrap/css/bootstrap.css" rel="stylesheet">
     <link rel="shortcut icon" href="../images/favicon.ico" />
	
	<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.js"></script> -->
	<script src="https://apis.google.com/js/client:platform.js" async defer></script>
    <script src="<?=BASE_URL?>bootstrap/js/bootstrap.js"></script>
	<script src="<?=BASE_URL?>js/jquery.placeholder.js"></script>
	<script src="<?=BASE_URL?>js/jquery.dataTables.js"></script>
	<script src="<?=BASE_URL?>js/bootstrap-datepicker.js"></script>
    <link href="<?=BASE_URL?>bootstrap/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="<?=BASE_URL?>css/DT_bootstrap.css?v=0.4" rel="stylesheet">
    <link href="<?=BASE_URL?>css/datepicker.css" rel="stylesheet">
    <link href="<?=BASE_URL?>css/style.css?v=<?=$cssVersion?>" rel="stylesheet">
    <!-- <script src="https://apis.google.com/js/platform.js" async defer></script> -->

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
	
	<style type="text/css">
      /* Override some defaults */
      html, body {
        background-color: rgb(70, 70, 70);
      }
      body {
        padding-top: 40px; 
      }
      .container {
        width: 300px;
      }

      /* The white background content wrapper */
      .container > .content {
        background-color: rgb(113, 113, 113);
        padding: 20px;
        margin: 0 -20px; 
        -webkit-border-radius: 10px 10px 10px 10px;
           -moz-border-radius: 10px 10px 10px 10px;
                border-radius: 10px 10px 10px 10px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
                box-shadow: 0 1px 2px rgba(0,0,0,.15);
      }

	  .login-form {
		margin-left: 65px;
	  }
	
	  legend {
		margin-right: -50px;
		font-weight: bold;
	  	color: #404040;
	  }
				    #customBtn {
				      width: 102px;
				      height: 102px;
				      position: relative;
				      left: 62px;
				    }
				    #customBtn:hover{
				    	cursor: pointer;
				    	opacity: 0.8;
				    }

				  </style>
	  }

    </style>
	
	
  </head>

  <body>
  
  <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','','ga');

  ga('create', '<?=$baseService->getGAKey()?>', 'icehrm.com');
  ga('send', 'pageview');

  </script>
  
  <script type="text/javascript">
	var key = "";
  <?php if(isset($_REQUEST['key'])){?>
  	key = '<?=$_REQUEST['key']?>';
  	key = key.replace(/ /g,"+");
  <?php }?>

  $(document).ready(function() {
	  $(window).keydown(function(event){
	    if(event.keyCode == 13) {
	      event.preventDefault();
	      return false;
	    }
	  });

	  $("#password").keydown(function(event){
		    if(event.keyCode == 13) {
		      submitLogin();
		      return false;
		    }
		  });
	});

  function showForgotPassword(){
	  $("#loginForm").hide();
	  $("#requestPasswordChangeForm").show();
  }

  function requestPasswordChange(){
	  $("#requestPasswordChangeFormAlert").hide();
	  var id = $("#usernameChange").val();
	  $.post("service.php", {'a':'rpc','id':id}, function(data) {
			if(data.status == "SUCCESS"){
				$("#requestPasswordChangeFormAlert").show();	
				$("#requestPasswordChangeFormAlert").html(data.message);
			}else{
				$("#requestPasswordChangeFormAlert").show();	
				$("#requestPasswordChangeFormAlert").html(data.message);
			}
	},"json");
  }

  /*function changePassword(){
	  $("#newPasswordFormAlert").hide();
	  var password = $("#password").val();

	  	var passwordValidation =  function (str) {  
			var val = /^[a-zA-Z0-9]\w{6,}$/;  
			return str != null && val.test(str);  
		};
	
		
		if(!passwordValidation(password)){
			$("#newPasswordFormAlert").show();	
			$("#newPasswordFormAlert").html("Password may contain only letters, numbers and should be longer than 6 characters");
			return;
		}

	  $.post("service.php", {'a':'rsp','key':key,'pwd':password,"now":"1"}, function(data) {
		  if(data.status == "SUCCESS"){
			  top.location.href = "login.php?c=1";
			}else{
				$("#newPasswordFormAlert").show();	
				$("#newPasswordFormAlert").html(data.message);
			}
	},"json");
  }*/

  function submitLogin(){
	$("#loginForm").submit();  
  }
  
  var callBack;

  function relayCallback(authResult)
  {
  	callBack = setTimeout(signinCallback(authResult), 100000);
  }

  function signinCallback(authResult) {
  if (authResult['status']['signed_in']) {
    // Update the app to reflect a signed in user
    // Hide the sign-in button now that the user is authorized, for example:
    document.getElementById('signinButton').setAttribute('style', 'display: none');
    gapi.client.load('plus', 'v1', apiClientLoaded);
  } else {
    // Update the app to reflect a signed out user
    // Possible error values:
    //   "user_signed_out" - User is signed-out
    //   "access_denied" - User denied access to your app
    //   "immediate_failed" - Could not automatically log in the user
     var xmlhttp = new XMLHttpRequest();
	        	xmlhttp.onreadystatechange = function(){
	            	if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
	            		//document.getElementById("errlog").value = xmlhttp.responseText;
	            		//alert(xmlhttp.respon)
	            		window.location.assign(xmlhttp.responseText);

	            	}
       			}
        		xmlhttp.open("POST","../logout.php",true);
				xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
				xmlhttp.send();

    console.log('Sign-in state: ' + authResult['error']);
  }
}

  /**
   * Sets up an API call after the Google API client loads.
   */
  function apiClientLoaded() {
    gapi.client.plus.people.get({userId: 'me'}).execute(loginv2);
  }

  /**
   * Response callback for when the API client receives a response.
   *
   * @param resp The API response object with the user email and profile information.
   */
  function loginv2(resp) {
    var xmlhttp = new XMLHttpRequest();
				var json = JSON.stringify(resp);
				//console.log(resp);
				console.log(json);
	        	xmlhttp.onreadystatechange = function(){
	            	if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
	            		//document.getElementById("errlog").value = xmlhttp.responseText;
	            		//alert(xmlhttp.respon)
	            		gapi.auth.signOut();
	            		window.location.assign(xmlhttp.responseText);

	            	}
       			}
        		xmlhttp.open("POST","../google_login2.php",true);
				xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
				xmlhttp.send("json="+json);

  }

  function onSignIn(googleUser) {
  var profile = googleUser.getBasicProfile();

 /*gapi.client.load('plus','v1', function(){
 var request = gapi.client.plus.people.get({
   'userId': 'me'
 });
 request.execute(function(resp) {
   console.log('Retrieved profile for:' + resp.displayName);
    
   var json = JSON.stringify(resp);
   createUser(json);

 });
});*/

  console.log('ID: ' + profile.getId());
  console.log('Name: ' + profile.getName());
  console.log('Image URL: ' + profile.getImageUrl());
  console.log('Email: ' + profile.getEmail());
  console.log(profile);

  login(profile.getEmail(), "default", profile);
}

function login(user, pass, profile){
				//alert(user + " " + pass);
				var xmlhttp = new XMLHttpRequest();
				var json = JSON.stringify(profile);
	        	xmlhttp.onreadystatechange = function(){
	            	if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
	            		//document.getElementById("errlog").value = xmlhttp.responseText;
	            		//alert(xmlhttp.respon)
	            		window.location.assign(xmlhttp.responseText);

	            	}
       			}
        		xmlhttp.open("GET", "../google_login.php?u=" + user +"&p="+pass+"&json="+json, true);
        		xmlhttp.send();
			}

  </script>	

  	<script src="https://apis.google.com/js/client:platform.js?onload=render" async defer></script>

  	<script>
				  function render() {
				    gapi.signin.render('customBtn', {
				      'callback': 'relayCallback',
				      'clientid': '870054181676-vhlg23uogu0v6q08k4702r5k28e87n5u.apps.googleusercontent.com',
				      'cookiepolicy': 'single_host_origin',
				      'requestvisibleactions': 'http://schema.org/AddAction',
				      'scope': 'https://www.googleapis.com/auth/plus.login'
				    });
				  }
	</script>

	<div class="container">
		<div class="content" style="margin-top:100px;">
			<div class="row">
				<div class="login-form">
					<h2><img src="<?=$logoFileUrl?>"/></h2>
					<?php if(!isset($_REQUEST['cp'])){?>
					<form id="loginForm" action="login.php" method="POST">
						<!--<fieldset>
							<div class="clearfix">
								<div class="input-prepend">
								  	<span class="add-on"><i class="icon-user"></i></span>
								  	<input class="span2" type="text" id="username" name="username" placeholder="Username">
								</div>
							</div>
							<div class="clearfix">
								<div class="input-prepend">
								  	<span class="add-on"><i class="icon-lock"></i></span>
								  	<input class="span2" type="password" id="password" name="password" placeholder="Password">
								</div>
							</div>
							
							<button class="btn" style="margin-top: 5px;" type="button" onclick="submitLogin();return false;">Sign in&nbsp;&nbsp;<span class="icon-arrow-right"></span></button>
							-->
							<?php if(isset($_REQUEST['f'])){?>
							<div class="clearfix alert alert-error" style="padding-left: 50px;font-size:15px;width:147px;margin-bottom: 5px;">
								Invalid PSG Global Solutions Account
								<?php if(isset($_REQUEST['fm'])){
									echo $_REQUEST['fm'];	
								}?>
							</div>
							<?php } ?>
							<!--<button style="background: transparent; border: none;
    outline:none; background-repeat: no-repeat; padding-left: 48px;"><div class="g-signin2" style="margin-top: 5px;" data-onsuccess="onSignIn"></div></button> -->

				 <div id="gSignInWrapper">
				   <div id="customBtn"  class="g-signin"
    					data-callback="relayCallback"
    					data-clientid="870054181676-vhlg23uogu0v6q08k4702r5k28e87n5u.apps.googleusercontent.com"
    					data-cookiepolicy="single_host_origin"
    					data-requestvisibleactions="http://schema.org/AddAction"
    					data-scope="https://www.googleapis.com/auth/plus.login">
				      <center><img src="<?=BASE_URL?>images/png-logo-grey.png"></center>
				    </div>
				  </div>
						</fieldset>
		
					</form>
								<p> &copy; 2015 - <?php echo date("Y");?> <a href="http://www.psgglobalsolutions.com">PSG Global Solutions</a> </p>
					<p> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Powered by <a href="http://www.icehrm.com"> &copy; ICEHrm </a> </p>
					<?php }?>
				</div>
			</div>
		</div>
	</div> <!-- /container -->
</body>
</html>
