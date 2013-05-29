<?PHP

require_once("./DatabaseResult.php");
require_once("./blog.php");

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

	//TODO: add a transaction around these 2 interactions
	function RegisterUserAndBlog($username, $password, $blog_name)
	{
		$insert_user_result = $this->RegisterUser($username, $password);
		
		if($insert_user_result->GetSuccessFlag() === true)
		{
			return $this->RegisterBlog($username, $blog_name);
		}
		else
		{
			return $insert_user_result;
		}
	}

	function RegisterUser($user_name, $password)
	{
		$result = new DatabaseResult(false, 'Just initialized');

		//INSERT USER INFORMATION
		$con = $this->OpenConnection();
		$query = 'insert into user (`Username`, `Password`, `DateCreated`) values (?, ?, NOW())';
		
		//Prepare a statement
		$stmt = mysqli_stmt_init($con);
		
		if(mysqli_stmt_prepare($stmt, $query))
		{
			$stmt->bind_param('ss', $user_name, $password);

			if(!$stmt->execute())
			{
				$result->SetSuccessFlag(false);
				$result->SetReason('Execution failed: (' . $stmt->errno . ')' . $stmt->error);
			}
			else
			{
				$result->SetSuccessFlag(true);
			}
			mysqli_stmt_close($stmt);
		}
		else
		{
			$result->SetSuccessFlag(false);
			$result->SetReason('Error setting up database statement: ' . $query);
		}

		$this->CloseConnection($con);

		return $result;
	}

	function RegisterBlog($user_name, $blog_name)
	{
		$result = new DatabaseResult(false, 'Just initialized');

		$con = $this->OpenConnection();
		$query = 'insert into blog (`BlogName`, `BlogOwner`, `BlogCreationDate`) values (?, (select `iduser` from user where `username` = ?), NOW())';
		$stmt = mysqli_stmt_init($con);
		
		if(mysqli_stmt_prepare($stmt, $query))
		{
			$stmt->bind_param('ss', $blog_name, $user_name);

			if($stmt->execute())
			{
				$result->SetSuccessFlag(true);
			}
			else
			{
				$result->SetSuccessFlag(false);
				$result->SetReason('Execution failed: (' . $stmt->errno . ')' . $stmt->error);
			}

			mysqli_stmt_close($stmt);
		}
		else
		{
			$result->SetSuccessFlag(false);
			$result->SetReason('Error setting up database statement: ' . $query);
		}

		$this->CloseConnection($con);

		return $result;
	}

	function GetBlogAssociatedWithUser($username)
	{
		$result = new DatabaseResult(false, 'Just initalized');

		$con = $this->OpenConnection();
		$query = "select blogs.blogname, blogs.blogowner, blogs.blogcreationdate, blogs.idblog, users.username
				from blog blogs join user users on blogs.blogowner = users.iduser
				where users.username = ?";
		$stmt = mysqli_stmt_init($con);

		if(mysqli_stmt_prepare($stmt, $query))
		{
			$stmt->bind_param('s', $username);

			if($stmt->execute())
			{
				if(!$db_results = $stmt->get_result())
				{
					$result->SetSuccessFlag(false);
					$result->SetReason("Trouble retrieving results");
				}
				else
				{
					//var_dump($details = $db_results->fetch_all()[0]);
					$details = $db_results->fetch_all()[0];
					$blog = new Blog();
					$blog->SetBlogName($details[0]);
					$blog->SetUserId($details[1]);
					$blog->SetBlogCreationDate($details[2]);
					$blog->SetBlogId($details[3]);
					$blog->SetUserName($details[4]);

					var_dump($blog);

					$result->SetSuccessFlag(true);
					$result->SetPayload($blog);
				}
			}
			else
			{
				$result->SetSuccessFlag(false);
				$result->SetReason("Trouble executing this query: $query");
			}
		}
		else
		{
			$result->SetSuccessFlag(false);
			$result->SetReason("Trouble setting up this query: $query");
		}

		$this->CloseConnection($con);

		return $result;
	}
}

?>