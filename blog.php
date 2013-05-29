<?php

class Blog
{
	private $blog_name;
	private $user_name;
	private $user_id;
	private $blog_creation_date;

	//constructor
	function Blog()
	{
		$this->blog_name = null;
		$this->user_name = null;
		$this->user_id = null;
		$this->blog_creation_date = null;
		$this->blog_id = null;
	}

	//Setters
	function SetBlogName($name)
	{
		$this->blog_name = $name;
	}

	function SetUserName($name)
	{
		$this->user_name = $name;
	}

	function SetUserId($id)
	{
		$this->user_id = $id;
	}

	function SetBlogCreationDate($date)
	{
		$this->blog_creation_date = $date;
	}

	function SetBlogId($blog_id)
	{
		$this->blog_id = $blog_id;
	}

	//Getters
	function GetBlogName()
	{
		return $this->blog_name;
	}

	function GetUserName()
	{
		return $this->user_name;
	}

	function GetUserId()
	{
		return $this->user_id;
	}

	function GetBlogCreationDate()
	{
		return $this->blog_creation_date;
	}

	function GetBlogId()
	{
		return $this->blog_id;
	}
}