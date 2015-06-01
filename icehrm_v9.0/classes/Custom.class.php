<?php
include("crypt/Aes.php");
include("crypt/AesCtr.php");

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

function insertUser()
{
	
}

function insertEmployee()
{

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

?>