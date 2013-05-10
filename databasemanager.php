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

	function CheckUserExistence($username, $password)
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

		if($row != null)
		{
			if($password == $row["Password"])
			{
				$legal_user->SetSuccessFlag(TRUE);
				$legal_user->SetReason("Successfully authenticated");
			}
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

	function RegisterUser()
	{}
}

?>