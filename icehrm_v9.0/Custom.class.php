<?php

class DBConnection
{
	private $servername;
	private	$dbuser;
	private	$password;
	private	$dbName;
	private $conn;

	function __construct()
	{
			$this->servername = "localhost";
			$this->dbuser = "root";
			$this->password = "1234";
			$this->dbName = "icehrmdb";
			$this->conn = new mysqli($this->servername, $this->dbuser, $this->password, $this->dbName);
	}

	function getInstance()
	{
					/* Connect */
		if($this->conn->connect_error){
			die("Connection failed: " . $conn->connect_error);
	
		}

		else return $this->conn;
	}

}

function insertProfileImage($employee, $profileimage)
{


	$connect= new DBConnection();
	$connect = $connect->getInstance();

	$sql = "SELECT MAX(id) as result FROM files";
	$result = $connect->query($sql);

	if($result->num_rows > 0)
	{
		$row = $result->fetch_assoc();
		$id = $row['result'] + 1;
	}

	$name = "profile_image_".$employee;
	$filename = $name.".jpg";

	$sql = "INSERT INTO files(id, name, filename, employee, file_group)
	VALUES ('$id', '$name', '$filename', '$employee', 'profile_image')";

	if ($connect->query($sql) !== TRUE) {
    	echo "Error: " . $sql . "<br>" . $connect->error;
    }

    $connect->close();

    $filename = $profileimage; // of course find the exact filename....        
	header('Pragma: public');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	header('Cache-Control: private', false); // required for certain browsers 
	header('Content-Type: application/pdf');

	header('Content-Disposition: attachment; filename="'. basename(CLIENT_BASE_URL."/data/".$filename) . '";');
	header('Content-Transfer-Encoding: binary');
	header('Content-Length: ' . filesize($filename));

	readfile($filename);

	exit;
}	

function insertEmployee($email, $password, $firstname, $lastname, $profileimage)
{
	$connect= new DBConnection();
	$connect = $connect->getInstance();

	$sql = "SELECT MAX(id) as result FROM employees";
	$result = $connect->query($sql);

	if($result->num_rows > 0)
	{
		$row = $result->fetch_assoc();
		$id = $row['result'] + 1;
	}

	$sql = "INSERT INTO employees(id, first_name, last_name, nationality)
	VALUES ('$id', '$firstname', '$lastname', '62')";

	if ($connect->query($sql) !== TRUE) {
    	echo "Error: " . $sql . "<br>" . $connect->error;
    }

    $connect->close();

    insertUser($email, $password, $id, $profileimage);
}

function insertUser($email, $password, $employee, $profileimage)
{
	$connect= new DBConnection();
	$connect = $connect->getInstance();

	$sql = "SELECT MAX(id) as result FROM users";
	$result = $connect->query($sql);

	if($result->num_rows > 0)
	{
		$row = $result->fetch_assoc();
		$id = $row['result'] + 1;
	}

	$sql = "INSERT INTO users(id, username, email, password, employee, user_level)
	VALUES ('$id', '$email', '$email', '$password', '$employee', 'Employee')";

	if ($connect->query($sql) !== TRUE) {
    	echo "Error: " . $sql . "<br>" . $connect->error;
    }

    $connect->close();

    //insertProfileImage($employee, $profileimage);
}

function getPassword($username)
{
	$connect= new DBConnection();
	$connect = $connect->getInstance();
	$password = "";

	$sql = "SELECT * FROM users";
	$result = $connect->query($sql);

	if($result->num_rows > 0)
	{
		while($row = $result->fetch_assoc())
		{

			if($username == $row['username'])
				$password = $row['password'];
			
		}

	}

    $connect->close();

    return $password;
}

function isFromPSG($username)
{
	$full = explode("@", $username);
	$domain = $full[1];

	if($domain == "gmail.com")
		return true;
	else
		return false;
}

function isExisting($username)
{
	$connect= new DBConnection();
	$connect = $connect->getInstance();
	$found = false;

	$sql = "SELECT * FROM users";
	$result = $connect->query($sql);

	if($result->num_rows > 0)
	{
		while($row = $result->fetch_assoc())
		{

			if($username == $row['username'])
				$found = true;

			
		}

	}

	$connect->close();

	return $found;

}

?>