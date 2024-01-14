<?php 
require_once('../database/Database.php');
require_once('../interface/iLogin.php');

class Login extends Database implements iLogin {
	
	private $username;
	private $password;

	public function __construct()
	{
		parent:: __construct();
		if(session_status() == PHP_SESSION_NONE)
		{
			session_start();//start session if session not start
		}
	}

	public function set_un_pwd($username, $password)
	{
		$this->username = $username;
		$this->password = $password;
	}	
	
	public function check_user()
	{

		$at_servicemanagement = 1;
		$sql = "SELECT *
				FROM user
				WHERE user_un = ?
				AND user_pass = ?
				AND user_at_servicemanagement = ?
		";
		$result = $this->getRow($sql, [$this->username, $this->password, $at_servicemanagement]);
		return $result;

	}

	public function get_user_id()
	{
		$type = 1;
		$at_servicemanagement = 1;
		$sql = "SELECT userID
				FROM user
				WHERE user_un = ?
				AND user_pass = ?
				AND typeID = ?
				AND user_at_servicemanagement = ?
		";
		$result = $this->getRow($sql, [$this->username, $this->password, $type, $at_servicemanagement]);
		return $result;
	}

	public function user_session()
	{
		if(!isset($_SESSION['user_logged_in'])){
			header('location: ../index.php');
		}
	}

	public function user_logout()
	{
		unset($_SESSION['user_logged_in']);
		header('location: ../index.php');
	}



	public function admin_session()
	{
		if(!isset($_SESSION['admin_logged_in'])){
			header('location: ../index.php');
		}
	}

	public function admin_logout()
	{
		unset($_SESSION['admin_logged_in']);
		header('location: ../index.php');
	}


	public function admin_data()
	{
		/*get admin user and password through session id*/
		$at_deped = 1;
		$id = $_SESSION['admin_logged_in'];
		$sql = "SELECT *
				FROM user
				WHERE userID = ?
				AND user_at_servicemanagement = ?
		";
		return $this->getRow($sql, [$id, $at_servicemanagement]);

	}



}

$login = new Login();



