<?php
require_once('../database/Database.php');
require_once('../interface/iQuote.php');

class Quote extends Database implements iQuote{
	public function __construct()
	{
		parent:: __construct();
	}

	public function insert_quote($qS, $eP, $qID, $quoteSerID)
	{
		$sql = "INSERT INTO quoteservives(quoteServiceID,serviceDescription, estimatedPrice, quoteID)
				VALUES(?,?,?,?);
		";
		// $result = $this->insertRow($sql, [$qS, $eP, $qID, $quoteSevID 1]);
		return $result;
	}

	public function update_quote($qS, $eP, $qID, $quoteSerID)
	{	
		$sql="UPDATE quoteservices
			  SET 
			  quoteServiceID = ?, 
			  serviceDescription = ?, 
			  estimatedPrice = ?, 
			  quoteID = ?, 	
			  WHERE quoteServiveID = ?
		";
		$result = $this->updateRow($sql, [$qS, $eP, $qID, $quoteSerID]);
		return $result;
	}

	public function get_quote($id)
	{
		$sql="SELECT *
			  FROM quoteservices i
			  INNER JOIN client e
			  ON i.client_id = e.client_id
			  INNER JOIN tbl_quote o
			  ON e.quote_id = o.quote_id
			  WHERE i.quoteservices_id = ?
		";
		$result = $this->getRow($sql, [$id]);
		return $result;
	}

	public function get_all_quotes()
	{
		/*get all items with the office nga naa sa emp*/
		$sql = "SELECT *
				FROM quoteservices i
				INNER JOIN client e
				ON i.client_id = e.client_id
				INNER JOIN quote o
				ON e.quote_id = o.quote_id
				ORDER by i.quoteservices
		";
		$result = $this->getRows($sql);
		return $result;
	}

	

}

$item = new Item();


