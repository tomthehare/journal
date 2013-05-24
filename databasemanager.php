<?PHP

require_once("./DatabaseResult.php");

class DatabaseManager
{
	var $database;
	var $server;
	var $username;
	var $password;

	function DatabaseManager($server, $database, $username, $password)
	{
		$this->server = $server;
		$this->database = $database;
		$this->username = $username;
		$this->password = $password;
	}

	private function OpenConnection()
	{
		$con = mysqli_connect($this->server, $this->username, $this->password, $this->database);

		if(mysqli_connect_errno())
		{
			echo "FAILED TO CONNECT TO MYSQL " . mysqli_connect_error();
		}
		else
		{
			return $con;
		}
	}

	private function CloseConnection($con)
	{
		mysqli_close($con);
	}

	function CheckUserAndPasswordExistence($username, $password)
	{
		$legal_user = new DatabaseResult(false, "Have not checked the database yet");
		$con = $this->OpenConnection();

		//Prepare a statement
		$stmt = $con->prepare('select * from user where username = ?');
		$stmt->bind_param('s', $username);

		$stmt->execute();

		$result = $stmt->get_result();

		//Should be only one row.
		$row = $result->fetch_assoc();

		$authenticated = FALSE;

		if($row != null)
		{
			if($password == $row["Password"])
			{
				$authenticated = TRUE;
			}
		}

		//set appropriate return object based on authentication result
		if($authenticated)
		{
			$legal_user->SetSuccessFlag(TRUE);
			$legal_user->SetReason("Successfully authenticated");
		}
		else
		{
			$legal_user->SetSuccessFlag(FALSE);
			$legal_user->SetReason("Credentials were not found");
		}

		mysqli_stmt_close($stmt);

		$this->CloseConnection($con);

		return $legal_user;
	}

	function CheckUserExistence($username)
	{
		$user_exists = new DatabaseResult(false, "Have not checked the database yet");
		$con = $this->OpenConnection();

		//Prepare a statement
		$stmt = $con->prepare('select * from user where username = ?');
		$stmt->bind_param('s', $username); //'s' is for string

		$stmt->execute();

		$result = $stmt->get_result();

		//Should be only one row.
		$row = $result->fetch_assoc();

		$exists = FALSE;

		if($row != null)
		{
			$exists = TRUE;
		}

		//set appropriate return object based on authentication result
		if($exists)
		{
			$user_exists->SetSuccessFlag(TRUE);
			$user_exists->SetReason("User exists");
		}
		else
		{
			$user_exists->SetSuccessFlag(FALSE);
			$user_exists->SetReason("User does not exist");
		}

		mysqli_stmt_close($stmt);

		$this->CloseConnection($con);

		return $user_exists;
	}

	function RegisterUser($username, $password)
	{
		$result = new DatabaseResult(false, 'Just initialized');

		//insert a new record for new user into database
		$con = $this->OpenConnection();

		//Prepare a statement
		$stmt = $con->prepare('insert into `user` (`Username`, `Password`, `DateCreated`) values (?, ?, NOW())');
		//$current_date = date('o-m-d g:i:s A');
		$stmt->bind_param('ss', $username, $password);

		if(!$stmt->execute())
		{
			$result->SetSuccessFlag(false);
			$result->SetReason('Execution failed: (' . $stmt->errno . ')' . $stmt->error);
		}
		else
		{
			$result->SetSuccessFlag(true);
			$result->SetReason('Execution succeeded.');
		}

		return $result;

	}
}

?>