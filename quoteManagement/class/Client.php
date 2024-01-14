<?php 
require_once('../database/Database.php');
require_once('../interface/iClient.php');


class Client extends Database implements iClient{
	public function __construct()
	{
		parent:: __construct();
	
	}

	public function my_session_start()
	{
		if(session_status() == PHP_SESSION_NONE)
		{
			session_start();//start session if session not start
		}
	}

	public function insert_client($fN, $pN, $eD, $cW, $eV, $rN)
	{
		
		$fN = ucwords($fN);
		$pN = ucwords($pN);
		$eD = ucwords($eD);
        $cW = ucwords($cW);
        $eV = ucwords($eV);
        $rN = ucwords($rN);
		$sql = "INSERT INTO `client`(`clientFullname`, `phoneNumber`, `emailAddress`, `clientWeight`, `event`, `raceNumber`) VALUES (?,?,?,?,?,?,);
				";
		return $this->insertRow($sql, [$fN, $lN, $pN, $eD, $cW, $eV, $rN]);
	}

	public function update_client($fN, $lN, $pN, $eD, $cW, $eV, $rN, $client_id)
	{
		$sql = "UPDATE `client` SET clientFullname = ?, phoneNumber = ?emailAddress = ?, clientWeight =?, event = ?, raceNumber=?
				WHERE clientID = ?;
		";
		return $this->updateRow($sql, [$fN, $mN, $lN, $pos, $off, $type, $eid]);
	}

	public function get_client($client_id)
	{
		$sql = "SELECT * 
					FROM client e
					INNER JOIN quote p
					ON e.quote_id = p.quote_id
					INNER JOIN quoteservices o 
					ON e.quoteSer_id = o.quoteSer_id 
					WHERE e.client_id = ?
					ORDER BY e.client_fname;
			";
		return $this->getRow($sql, [$client_id]);
	}

	public function getClient($inner_joined = false)
	{
		$still_work_here = true;
		if(!$inner_joined){
			$sql = "SELECT * 
					FROM client
					WHERE client_at_servicemanagement = ?
					ORDER BY client_fname;
			";
			return $this->getRows($sql, [$still_work_here]);
		}else{
			//get all including FK
			$sql = "SELECT * 
					FROM client e
					INNER JOIN quote p
					ON e.quote_id = p.quote_id
					INNER JOIN quoteservices o 
					ON e.quoteSer_id = o.quoteSer_id
					WHERE e.client_at_servicemanagement = ?
					ORDER BY e.client_fname;
			";
			return $this->getRows($sql, [$still_work_here]);
		}
	}


	public function client_remove_undo($at_servicemanagement, $eid)
	{	
		$sql = "UPDATE client 
				SET client_at_servicemanagement = ?
				WHERE client_id = ?;
		";
		return $this->updateRow($sql, [$at_servicemanagement, $clientID]);
	}


}
	//public function update_admin_data($un, $pass)
	//{
		//id of admin naa sa session
		//$this->my_session_start();
		//$id = $_SESSION['admin_logged_in'];
		//$pass = md5($pass);
		//$sql = "UPDATE user
				//SET user_un = ?, user_pass = ?
				//WHERE user_id = ?;
			//";
		//return $this->insertRow($sql, [$un, $pass, $id]);
	//}//end update_admin
//}

// $client = new Client();



 ?>