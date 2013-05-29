<?PHP
//--- Site manager to handle logic
require_once("./databasemanager.php");
require_once("./databaseresult.php");
require_once("./blog.php");

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
			$this->GetDatabaseManager()->CheckUserAndPasswordExistence($username, $password);

		//var_dump($login_result);

		if($login_result->GetSuccessFlag() == TRUE)
		{
			//call UpdateLastLoginTimestamp here

			return true;
		}
		else
		{
			$this->SetError($login_result->GetReason());
			return false;
		}
	}

	function Register()
	{
		$proposed_username = $_POST["newUsernameTxt"];
		$proposed_password1 = $_POST["newPasswordTxt1"];
		$proposed_password2 = $_POST["newPasswordTxt2"];
		$blog_name = $_POST["blogTitle"];

		//check passwords to see that they are equal
		if($proposed_password1 !== $proposed_password2)
		{
			$this->SetError("Passwords do not match");
			return false;
		}

		//check blog name
		if(is_null($blog_name) || strlen($blog_name) == 0)
		{
			$this->SetError("Need a blog name");
			return false;
		}

		//check if the username is already in the database
		$existence_result = 
			$this->GetDatabaseManager()->CheckUserExistence($proposed_username);

		if($existence_result->GetSuccessFlag() == TRUE)
		{
			$this->SetError("Username already exists");
			return false;
		}
		else
		{
			$registration_result =
				$this->GetDatabaseManager()->RegisterUserAndBlog($proposed_username, $proposed_password1, $blog_name);
			
			$this->SetError($registration_result->GetReason());

			return $registration_result->GetSuccessFlag();
		}
	}

	function GetPosts()
	{
		return "NO POSTS";
	}

	function GetBlogAssociatedWithUser($username)
	{
		if(!is_null($username))
		{
			$result = $this->GetDatabaseManager()->GetBlogAssociatedWithUser($username);

			if($result->GetSuccessFlag() === true)
			{
				return $result->GetPayload();
			}
			else
			{
				$this->SetError($result->GetReason());
				return null;
			}
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