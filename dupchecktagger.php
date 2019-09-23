<?php
// https://theapiguys.com/20140107-8020-php-course-archive/
// https://developer.infusionsoft.com/docs/xml-rpc/#data-count-a-data-table-s-records

require_once("isdk.php");
$app = new iSDK;
//Test Connection
if( $app->cfgCon("al477"))
{
echo 'You are connected';

	$contactId = $_REQUEST['contactId'];   
	// $contactId = 630; //test ID 
	$returnFieldsHTTP = array('Email', 'Phone1'); // Retrieve data from current contact running the sequence
	$conDatHTTP = $app->loadCon($contactId, $returnFieldsHTTP);
	$queryCountEmail = array('Email' => $conDatHTTP['Email']);
	$contactsEmail = $app->dsCount("Contact",$queryCountEmail);
	
	if ($contactsEmail > 1){ // set to one because it will count since contact is already added on form submit
	$returnFieldsQuery = array('Id', 'LastName'); // retrieve the ID
	$query = array('Email' => $conDatHTTP['Email']); // query by using phone data
	$contacts = $app->dsQuery("Contact",50,0,$query,$returnFieldsQuery); // 50 = maximum return data // 0 maximum return pages 

	foreach ($contacts as $disp){
		// echo $disp['Id'].$disp['LastName'].'</br>';
		$returnFields = array('Email'); // Retrieve data from current contact running the sequence
		$conDatDup = $app->loadCon($disp['Id'], $returnFields);
		if ($conDatDup['Email'] == $conDatHTTP['Email']){
			$tagId = 240; //Potential duplicate tag ID
			$result1 = $app->grpAssign($contactId, $tagId); // assign tag to current Contact
			$result2 = $app->grpAssign($disp['Id'], $tagId); // assign tag to duplicate Contact
		}
	}
	} 
	 
	$queryCountPhone = array('Phone1' => $conDatHTTP['Phone1']);
	$contactsPhone = $app->dsCount("Contact",$queryCountPhone);
	
	if ($contactsPhone > 1){ // set to one because it will count since contact is already added on form submit
	$returnFieldsQuery = array('Id'); // retrieve the ID
	$query = array('Phone1' => $conDatHTTP['Phone1']); // query by using phone data
	$contacts = $app->dsQuery("Contact",50,0,$query,$returnFieldsQuery); // 50 = maximum return data // 0 maximum return pages 
 
	foreach ($contacts as $disp){
		// echo $disp['Id'].$disp['LastName'].'</br>';
		$returnFields = array('Phone1'); // Retrieve data from current contact running the sequence
		$conDatDup = $app->loadCon($disp['Id'], $returnFields);
		
		if (preg_replace("/[^A-Za-z0-9]/", "", $conDatDup['Phone1']) == preg_replace("/[^A-Za-z0-9]/", "", $conDatHTTP['Phone1'])){
			$tagId = 240; //Potential duplicate tag ID
			$result1 = $app->grpAssign($contactId, $tagId); // assign tag to current Contact
			$result2 = $app->grpAssign($disp['Id'], $tagId); // assign tag to duplicate Contact  
		}
	}
	
	}

}  
else{
echo "Not Connected";
}