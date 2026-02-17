<?php

class loginDB
{
private $logindb;

public function __construct()
{
	$this->logindb = new mysqli("127.0.0.1","test_user","12345","testExample3");

	if ($this->logindb->connect_errno != 0)
	{
		echo "Error connecting to database: ".$this->logindb->connect_error.PHP_EOL;
		exit(1);
	}
	echo "correctly connected to database".PHP_EOL;
}

public function validateLogin($username,$password)
{
	$un = $this->logindb->real_escape_string($username);
	$pw = $this->logindb->real_escape_string($password);

        echo "Trying to validateLogin".PHP_EOL;
	$statement = "select * from users where username = '$un'";
	$response = $this->logindb->query($statement);
        
        echo "Post statement, trying to see where it goes wrong -matt".PHP_EOL;
  
       if ($response->num_rows > 0)
	 {
	    echo "There are results!";
	 }
	 else
	 {
    		echo "No results found.";
	 }


	while ($row = $response->fetch_assoc())
	{
		echo "checking password for $username".PHP_EOL;
		if ($row["password_hash"] == $pw)
		{
			echo "passwords match for $username".PHP_EOL;
			return 1;// password match
		}
		echo "passwords did not match for $username".PHP_EOL;
	}

	return 0;//no users matched username
}
}
?>
