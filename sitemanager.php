<?PHP
//--- Site manager to handle logic
require_once("./databasemanager.php");
require_once("./databaseresult.php");

class SiteManager
{
	var $current_error;

	//Database stuff//
	var $db_mngr;
	var $database_server;
	var $database_name;
	var $database_username;
	var $database_password;

	function SiteManager()
	{
	}

	function Login()
	{
		$username = $_POST["username"];
		$password = $_POST["password"];

		$login_result = 
			$this->GetDatabaseManager()->CheckUserExistence($username, $password);

		var_dump($login_result);

		if($login_result->GetSuccessFlag() == TRUE)
		{
			return true;
		}
		else
		{
			$this->SetError($login_result->GetReason());
			return false;
		}
	}

	function GetDatabaseManager()
	{
		if(is_null($this->db_mngr))
		{
			$this->db_mngr = 
				new DatabaseManager(
						$this->database_server, 
						$this->database_name, 
						$this->database_username, 
						$this->database_password);
		}

		return $this->db_mngr;
	}

	function SetError($error)
	{
		$this->current_error = $error;
	}

	function GetErrors()
	{
		return $this->current_error;
	}

	function RedirectToURL($destination)
	{
		header("Location: $destination");
        exit;
	}

	function SetDatabaseServer($server)
	{
		$this->database_server = $server;
	}

	function SetDatabaseName($name)
	{
		$this->database_name = $name;
	}

	function SetDatabaseUsername($username)
	{
		$this->database_username = $username;	
	}

	function SetDatabasePassword($pw)
	{
		$this->database_password = $pw;
	}
}

//purposfully ommitting the closing php tag