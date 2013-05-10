<?php

class DatabaseResult
{
	private $successFlag;
	private $reason;

	function DatabaseResult($successFlag, $reason)
	{
		$this->successFlag = $successFlag;
		$this->reason = $reason;
	}

	function GetSuccessFlag()
	{
		return $this->successFlag;
	}

	function GetReason()
	{
		return $this->reason;
	}

	function SetSuccessFlag($flag)
	{
		$this->successFlag = $flag;
	}

	function SetReason($reason)
	{
		$this->reason = $reason;
	}
}


?>