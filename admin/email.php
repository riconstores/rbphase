  <?php //echo $admin_email = get_option( 'admin_email' ); ?> 
<?php

if($_GET['ajax'])
{
	if(isset($_POST['demos']))
	{	$Demos=array();
		if(count($_POST['demos'])>0):
			foreach($_POST['demos'] as $key=>$value)
			{
				$Demos[]=$key.'='.$value;
			}
		endif;
		$Demos[]='Comments='.$_POST['Comments'];
		
	}
	else
	{
		$Demos=array();
		$Demos[]='Comments='.$_POST['Comments'];
	}
	
 $guid  = "4CABF9DF-EB03-4CFE-B094-5F99DA9E2F10";//$_POST['guid'];
 $eleaduri="http://www.salessimplicity.net/ssnet/svceleads/eleads.asmx?WSDL";//$_POST['eleaduri'];
 $comments='Comments='.$_POST['Comments'];
	//TODO: Verify this URL
	$client = new SoapClient($eleaduri);	
	$comments='Comments='.$_POST['Comments'];
	$lead=array(
				//TODO: put your 
				'BuilderName'=>$_POST["buildername"],
				'IPAddress'=>$_POST["ipaddress"],
				'Email'	=> $_POST["Email"],
				'FirstName' =>$_POST["FirstName"],
				'LastName' =>$_POST["LastName"],
				'CommunityName'=>$_POST["communityname"],
				'CommunityNumber'=>$_POST["communitynumber"],
				'Demos'	=>$Demos//TODO Put in your demographic values
			);
	$lead=(object)$lead;

	$result = $client->SubmitLead(array('sGUID' => $guid,'Contact' => $lead));
	//print_r($result);
	 $result->SubmitLeadResult;//	
	if($result!='')
	{
		$response['status']='sent';
	}
	else
	{
		$response['status']='nosent';
	}
		//
	 echo  $response=json_encode($response);
}

?>