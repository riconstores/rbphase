<?php
error_reporting(0);
// Stop direct call


if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
global $wpdb;
?>
<?php 
 global $wpdb;
$riconllc=$wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_riconllc");
$riconllc=$riconllc[0];
$corp=$wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_corporations");
$corp=$corp[0];


//if (class_exists('SoapClient')&& $riconllc->Elead_Certificate_ID!='') :
//TODO: Fill in your GUID
if(isset($riconllc->Elead_Certificate_ID))
{
$guid = $riconllc->Elead_Certificate_ID;

}
else{
$guid ="4CABF9DF-EB03-4CFE-B094-5F99DA9E2F10";

}

//TODO: Verify this URL
if(isset($riconllc->Elead_Service_URI))
{
	try {
		  $client = new SoapClient($riconllc->Elead_Service_URI,array('trace' => true,'exceptions' => true));
		  $result = $client->GetDemosXML(array('SubscriptionGUID' => $guid));
		$xml = simplexml_load_string($result->GetDemosXMLResult, 'SimpleXMLElement', LIBXML_NOCDATA);// or die ("Unable to load XML file!");
		} catch (Exception $e) {
		   $errormessage=true;
		}
}
else{
$client=new SoapClient("http://www.salessimplicity.net/ssnet/svceleads/eleads.asmx?WSDL");

// This pulls a list of your Demographics
// We would really like you to store these demos Locally And grab a new set daily....
$result = $client->GetDemosXML(array('SubscriptionGUID' => $guid));

$xml = simplexml_load_string($result->GetDemosXMLResult, 'SimpleXMLElement', LIBXML_NOCDATA) or die ("Unable to load XML file!");
}


?>

<script src="<?php echo plugins_url();?>/builder_design/frontend/js/jquery-1.9.0.min.js"></script>
<style>
.updateDatabase{padding:10px; border:1px solid #ccc;}
.corporations{color: #008000;font-size: medium;	}
.builders{line-height: 2; padding-left: 25px;}
.divisions{line-height: 2;  padding-left: 50px;}
.plans{line-height: 2;  padding-left: 75px;}	
.specifications{line-height: 2; padding-left: 100px;}
.options{line-height: 2; padding-left: 125px;}
.green{color:green;}
.ricollc{border: 1px solid #CCCCCC;    margin: 6px;    padding: 10px;    width: 97%;}
.label{float:left;}
.input-text{float:right;width:70%;}
.input-text input{width:70%;}
.clear{float:none;}
.ricollc span {
    border-bottom: 1px solid #E4E4E4;
    color: #747474;
    float: left;
    margin-bottom: 8px;
    padding-bottom: 3px;
    text-transform: uppercase;
    width: 100%;
}
</style>
<div class="wrap">
<h2>BuilderPhase</h2>
<?php
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	
	$url=BP.DS.'xmlimport'.DS;
	if(isset($_POST['import']))
	{
			$temp = explode(".", $_FILES["file"]["name"]);
			$extension = end($temp);
			if (($_FILES["file"]["type"] == "text/xml"))
			  {
			  if ($_FILES["file"]["error"] > 0)
				{
				echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
				}
			  else
				{
				if (file_exists("$url" . $_FILES["file"]["name"]))
				  {
					echo'<div class="updated settings-error" id="setting-error-settings_updated"> 
						<p><strong>'. $_FILES["file"]["name"] . " already exists. ".'</strong></p></div>';
				  }
				else
				  {
					$res=move_uploaded_file($_FILES["file"]["tmp_name"], $url.'/'. $_FILES["file"]["name"]);
					if($res)
					{
						//updateDatabase($_FILES["file"]["name"]);
						echo 'File Uploaded successfully..!!!';
					}
					
					
					
				  }
				}
			  }
			else
			  {
			  echo "Invalid file";
			  }
	}
	if(isset($_POST['savericonllc']))
	{
			//print_r($_POST);
		$demofield=implode(",",$_POST[demo]);
		// echo "$demofield";
		
		 $res=$wpdb->get_var("SELECT count(*) FROM {$wpdb->prefix}bd_riconllc");
		
		 if($res==0)
		 {	
			
			 $sql="INSERT INTO {$wpdb->prefix}bd_riconllc (ID,Server_URL, Topo_Certificate_ID,Elead_Service_URI, Elead_Certificate_ID, ShowLotStatus, ShowLot, ShowPhasePlan,ShowLotSize,ShowTotalPrice,ShowStage,ShowPremium,ShowGarageOrient,demos,Zoom,DemoDivisionNumber)
VALUES ('1','$_POST[Server_URL]','$_POST[Topo_Certificate_ID]','$_POST[Elead_Service_URI]','$_POST[Elead_Certificate_ID]','$_POST[ShowLotStatus]','$_POST[ShowLot]','$_POST[ShowPhasePlan]','$_POST[ShowLotSize]','$_POST[ShowTotalPrice]','$_POST[ShowStage]','$_POST[ShowPremium]','$_POST[ShowGarageOrient]','$demofield','$_POST[Zoom]','$_POST[DemoDivisionNumber]')"; 
			
			$result=$wpdb->query($sql);
			if($result){echo "Data Saved successfully ";}
		 }
		 else
		 {  
			  $sql="UPDATE {$wpdb->prefix}bd_riconllc
				SET Server_URL='$_POST[Server_URL]', Topo_Certificate_ID='$_POST[Topo_Certificate_ID]',Elead_Service_URI='$_POST[Elead_Service_URI]',Elead_Certificate_ID='$_POST[Elead_Certificate_ID]',Zoom='$_POST[Zoom]',ShowLotStatus='$_POST[ShowLotStatus]',ShowLot='$_POST[ShowLot]',ShowPhasePlan='$_POST[ShowPhasePlan]',ShowLotSize='$_POST[ShowLotSize]',ShowTotalPrice='$_POST[ShowTotalPrice]',ShowStage='$_POST[ShowStage]',ShowPremium='$_POST[ShowPremium]',ShowGarageOrient='$_POST[ShowGarageOrient]',DemoDivisionNumber='$_POST[DemoDivisionNumber]',demos='$demofield'
				WHERE ID='1'; "; 
			
		$result=$wpdb->query($sql);
			if($result){echo "Data Updated successfully ";}
		 }
		
	}
	$riconllc=$wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_riconllc");
	$riconllc=$riconllc[0];

	?>
	
	<div class="ricollc">
	<form method="post">
		<ul>
		<span>topo map</span>
			<li>
				<div class="label">TOPO URL</div>
				<div class="input-text"><input name="Server_URL" value="<?php if(isset($riconllc->Server_URL)){echo $riconllc->Server_URL; };?>"type="text"/></div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">Topo Certificate ID</div>
				<div class="input-text">
				<input name="Topo_Certificate_ID" value="<?php if(isset($riconllc->Topo_Certificate_ID)){echo $riconllc->Topo_Certificate_ID; };?>"  type="text"/>
				
				</div>
				
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">DemoDivisionNumber</div>
				<div class="input-text">
				<input name="DemoDivisionNumber" value="<?php if(isset($riconllc->DemoDivisionNumber)){echo $riconllc->DemoDivisionNumber; };?>" type="text"/>
				<p>Remove the Demo Division Number After Go Live</p>
				</div>
				
				
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">Zoom</div>
				<div class="input-text">
				<input name="Zoom" value="<?php if(isset($riconllc->Zoom)){echo $riconllc->Zoom; };?>" type="text"/>
				<p>Hint:- Fill the value from o to 1 for Example:- 0.75</p>
				</div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">ShowLotStatus</div>
				<div class="input-text"><select name="ShowLotStatus">
					<option <?php if($riconllc->ShowLotStatus=='no'){echo 'selected="selected"'; };?> value="no">No</option>
					<option <?php if($riconllc->ShowLotStatus=='yes'){echo 'selected="selected"'; };?> value="yes">Yes</option>
				</select></div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">ShowLot</div>
				<div class="input-text"><select name="ShowLot">
					<option <?php if($riconllc->ShowLot=='no'){echo 'selected="selected"'; };?> value="no">No</option>
					<option <?php if($riconllc->ShowLot=='yes'){echo 'selected="selected"'; };?> value="yes">Yes</option>
				</select></div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">ShowPhasePlan</div>
				<div class="input-text"><select name="ShowPhasePlan">
					<option <?php if($riconllc->ShowPhasePlan=='no'){echo 'selected="selected"'; };?> value="no">No</option>
					<option <?php if($riconllc->ShowPhasePlan=='yes'){echo 'selected="selected"'; };?> value="yes">Yes</option>
				</select></div>
				<div class="clear"></div>
			</li>

			<li>
				<div class="label">ShowLotSize</div>
				<div class="input-text"><select name="ShowLotSize">
					<option <?php if($riconllc->ShowLotSize=='no'){echo 'selected="selected"'; };?> value="no">No</option>
					<option <?php if($riconllc->ShowLotSize=='yes'){echo 'selected="selected"'; };?> value="yes">Yes</option>
				</select></div>
				<div class="clear"></div>
			</li>
			
			<li>
				<div class="label">ShowTotalPrice</div>
				<div class="input-text"><select name="ShowTotalPrice">
					<option <?php if($riconllc->ShowTotalPrice=='no'){echo 'selected="selected"'; };?> value="no">No</option>
					<option <?php if($riconllc->ShowTotalPrice=='yes'){echo 'selected="selected"'; };?> value="yes">Yes</option>
				</select></div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">ShowStage</div>
				<div class="input-text"><select name="ShowStage">
					<option <?php if($riconllc->ShowStage=='no'){echo 'selected="selected"'; };?> value="no">No</option>
					<option <?php if($riconllc->ShowStage=='yes'){echo 'selected="selected"'; };?> value="yes">Yes</option>
				</select></div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">ShowPremium</div>
				<div class="input-text"><select name="ShowPremium">
					<option <?php if($riconllc->ShowPremium=='no'){echo 'selected="selected"'; };?> value="no">No</option>
					<option <?php if($riconllc->ShowPremium=='yes'){echo 'selected="selected"'; };?> value="yes">Yes</option>
				</select></div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">ShowGarageOrient </div>
				<div class="input-text"><select name="ShowGarageOrient">
					<option <?php if($riconllc->ShowGarageOrient=='no'){echo 'selected="selected"'; };?> value="no">No</option>
					<option <?php if($riconllc->ShowGarageOrient=='yes'){echo 'selected="selected"'; };?> value="yes">Yes</option>
				</select></div>
				<div class="clear"></div>
			</li>
			<span>elead form</span>
			<li>
				<div class="label">Elead Service URI</div>
				<div class="input-text"><input name="Elead_Service_URI" value="<?php if(isset($riconllc->Elead_Service_URI)){echo $riconllc->Elead_Service_URI; };?>" type="text"/></div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">Elead Certificate ID</div>
				<div class="input-text">
				<input name="Elead_Certificate_ID" value="<?php if(isset($riconllc->Elead_Certificate_ID)){echo $riconllc->Elead_Certificate_ID; };?>" type="text"/></div>
				<div class="clear"></div>
			</li>
			<style type="text/css">
						.demi > li {
							float: left;
							width: 100%;
									}
			</style>
			<div class="demi">
                <?php	
				if(!isset($errormessage)){
					$data=json_decode(json_encode($xml->xpath("//Table")));
					$eleaddemos=(explode(",",$riconllc->demos));
					$demos=array();
					foreach($data as $datas)
					{
					if(!in_array($datas->SystemName,$demos))
					{
					//echo "<pre>";
					//echo "<br>";
					echo "<li>";
					echo "<div class=label>";
					echo '<input type="checkbox"';
					if(in_array($datas->SystemName,$eleaddemos)){echo 'checked="checked"';}
					echo 'name="demo['.$datas->SystemName.']" rel='."$datas->SystemName".'  class="checkboxdemo '.$datas->SystemName.'" value="' .$datas->SystemName.'" '.$checked.'/>';
					echo "$datas->Display ";
					echo "&nbsp&nbsp&nbsp";
					echo "</div>"; 
					$conn=$xml->xpath("//Table[SystemName='$datas->SystemName']/Description");
					//print_r ($conn);
					echo '<div id='."$datas->SystemName".' class="showone input-text">';
					
					 echo '<select class="selectd" sid="'."$datas->SystemName".'">';
							foreach($conn as $des)
						{
						echo "<option value='$des'>$des</option>";
						}
						echo "</select>";
						echo "</div>";
						echo "</li>";
						
					//echo $datas->SystemName;
					//echo '<hr>';
				    $demos[]=$datas->SystemName;
					
					}
				
					//echo count(SystemName);
					//echo $datas->SystemName;
					//print_r($datas);
					//echo '<hr>';
					}
					}		
					?>
					<?php //endif;?>
					</div>
			
			<li>
				<div class="label"></div>
				<div class="input-text"><input style="width:80px;" class="button" value="Save" name="savericonllc" type="submit"/></div>
				<div class="clear"></div>
			</li>
		</ul>
			</form>	
	</div>
	<div class="clear"></div>
	
	
	
	
	<h2>Import </h2>
	<div class="ricollc">
		<form method="post" enctype="multipart/form-data"><input name="file" type="file">
			<input name="import" type="submit">
		</form>
	<?php $dirs=scandir($url);
	 $count=count($dirs)-2;?>
	
	<form method="post" enctype="multipart/form-data">
			<select name="filename">';
			
		<?php	for($i=2;$i<=count($dirs)-1;$i++)
			{
				echo '<option>'.$dirs[$i].'</option>';
			}
			if($count<=0)
			{
				echo '<option>There are no xml file uploaded..!!</option>';
			}?>
			
		<select>			
			<input name="updatedatabase" value="Update DataBase From XML" type="submit">
	</form>

	</div>


<?php
if(isset($_POST['updatedatabase']))
	{
		echo '<div class="updateDatabase">	';
		SaveXMLData($_POST['filename']);
		echo '</div>';
	}
/*--Save Data from XML Files--*/
function SaveXMLData123($fname)
{
	global $wpdb;
	$url=BP.DS.'xmlimport'.DS.$fname;
	$data=json_decode(json_encode((array) simplexml_load_file($url)), 1);
	echo '<pre>';
	print_r($data);
}
/*--Save Data from XML Files--*/
function SaveXMLData($fname)
{
	global $wpdb;
	$url=BP.DS.'xmlimport'.DS.$fname;
	$data=simplexml_load_file($url);
	
	$CorporateBuilderNumber=$data->Corporation->CorporateBuilderNumber;
	$CorporateState=$data->Corporation->CorporateState;
	$CorporateName=$data->Corporation->CorporateName;
	$CorporateReportingEmail=$data->Corporation->CorporateReportingEmail;
	$status=0;
	 
	 $update=checkCorporation($data->Corporation->CorporateReportingEmail);
	 if($update!=0)
	 {
		if($update->Update_From_Xml==0)
		{
			$sql=$wpdb->prepare( "UPDATE  {$wpdb->prefix}bd_corporations SET c_number=%s, c_name=%s, c_state=%s, c_email=%s, status=%d  WHERE ID=%d ",
			array(
			$CorporateBuilderNumber,
			$CorporateName,
			$CorporateState,
			$CorporateReportingEmail,
			$status,
			$update->ID
			));
			$res=$wpdb->query( $sql); 
			
				echo '<span class="corporations">Corporation <b>"'.$CorporateName. '"</b> has been updated..</span>'.'</br>';
				
				saveBuilders($data->Corporation->Builder,$update->ID);
		
			
		}
		else
		{	echo '<span class="corporations">Corporation <b>"'.$CorporateName. '"</b> has been skipped..</span>'.'</br>';
				//saveBuilders($data['Corporation']['Builder'],$update->ID);
		}
		
		
	 }
	 else
	 {
		echo '<span class="corporations">Corporation <b>"'.$CorporateName. '"</b> has been Inserted..</span>'.'</br>';
		$sql=$wpdb->prepare( "INSERT INTO {$wpdb->prefix}bd_corporations (c_name, c_number, c_state, c_email,status) VALUES ( %s, %s, %s,%s,%d )", array( $CorporateName,$CorporateBuilderNumber,$CorporateState,$CorporateReportingEmail,$status)  
				);
		$res=$wpdb->query($sql);
		if($res){
		
			$corpid=$wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_corporations ORDER BY ID DESC LIMIT 1");
			
			saveBuilders($data->Corporation->Builder,$corpid[0]->ID);
			}
		else
		{
			$message='Corporation have not been inserted...!!!!';
		}
	 }
	
		
}

function saveBuilders($builders,$corporationid)
{		
	global $wpdb;
	
	foreach($builders as $builder)
	{ 
			
			$update=checkBuilder($builder->BuilderNumber,$corporationid);
			 if($update!=0)
			 {
				if($update->Update_From_Xml==0)
				{
					$res=$wpdb->query( $wpdb->prepare( "UPDATE  {$wpdb->prefix}bd_builders SET builder_number=%s ,brand_name=%s,reporting_name=%s,lead_email=%s WHERE ID=%d ",
	 array($builder->BuilderNumber, $builder->BrandName,$builder->ReportingName,$builder->DefaultLeadsEmail, $update->ID)) ); 
					
					
						 echo '<span class="builders"> Builder Number  "'. $builder->BuilderNumber.'"  has been updated.</span></br>';
						 if(count($builder->Subdivision)>0)
						{
							saveSubdivision($builder->Subdivision,$update->ID,$corporationid);
						}
					
					
					
				}
				else
				{
					echo '<span class="builders">Builder Number "'. $builder['BuilderNumber'].'" has been skipped.</span></br>';
					 if(count($builder->Subdivision)>0)
						{
							//saveSubdivision($builder['Subdivision'],$update->ID,$corporationid);
						}
				}
					
			 }
			 else
			 {
					$sql=$wpdb->prepare( "INSERT INTO {$wpdb->prefix}bd_builders (corporat_id, builder_number, brand_name,reporting_name,lead_email) VALUES ( %d, %s, %s,%s,%s )",
					array( $corporationid,$builder->BuilderNumber,
					$builder->BrandName,
					$builder->ReportingName,
					$builder->DefaultLeadsEmail)  
				);
				
					$res=$wpdb->query($sql);
				if($res)
				{
					echo '<span class="builders">Builder Number  '.$builder['BuilderNumber'].' has been Inserted successfully..!!</span> <br>';
				
					if(count($builder->Subdivision)>0)
					{
						$builderid=$wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}bd_builders WHERE builder_number=%d",$builder->BuilderNumber));
						
						saveSubdivision($builder->Subdivision,$builderid[0]->ID,$corporationid);
					}
				}		
				
			 }
	}
		return true;
}

/***************************Saving Subdivision*********************************************/
function saveSubdivision($Subdivisions,$builderid,$corporationid)
{
	global $wpdb;
	$i=0;
	foreach($Subdivisions as $subdivision1)
	{
		$Subdivision=json_decode(json_encode((array) $subdivision1), 1);
		$update=checkDivision($Subdivision['SubdivisionNumber'],$builderid);//checkBuilder();
		if(is_array($Subdivision['SubImage']) OR ($Subdivision['SubImage']=='NULL'))
		{
		$img=0;
		}
		
		else
		{
		$img=$Subdivision['SubImage'];
		}
			
			 if($update!=0)
			 {
				if($update->Update_From_Xml==0)
				{
					$res=$wpdb->query( $wpdb->prepare( "UPDATE  {$wpdb->prefix}bd_divison SET sqft_high=%d, sqft_low=%d, price_high=%d, price_low=%d,number=%s,
					 name=%s, descr=%s, build_lot=%d, sale_office_address=%s, sub_address=%s, driv_direction=%s, phone=%s, image=%s, status=%s	WHERE ID=%d ",
					 array(
						$Subdivision['@attributes']['SqftHigh'],
						$Subdivision['@attributes']['SqftLow'],
						$Subdivision['@attributes']['PriceHigh'],
						$Subdivision['@attributes']['PriceLow'],
						$Subdivision['SubdivisionNumber'],
						$Subdivision['SubdivisionName'],
						$Subdivision['SubDescription'],
						$Subdivision['BuildOnYourLot'],
						json_encode($Subdivision['SalesOffice']),
						json_encode($Subdivision['SubAddress']),
						$Subdivision['DrivingDirections'],
						json_encode($Subdivision['Phone']),
						$img,
						$Subdivision['@attributes']['Status'],
						$update->ID
						))); 
					
					
					
						 echo '<span class="divisions">Division Id "'. $Subdivision['SubdivisionNumber'].'" has been updated.</span></br>';
						if(count($subdivision1->Plan)>0)
						{
							savePlans($subdivision1->Plan,$builderid,$update->ID);
						}
					
					
					
				}
				else
				{
					 echo '<span class="divisions">Division Number "'. $Subdivision['SubdivisionNumber'].'" has been skipped.</span></br>';
					if(count($Subdivision[Plan])>0)
						{
							//savePlans($Subdivision[Plan],$builderid,$update->ID);
						}
				}
					
			 }
			 else
			 {
				
				 $sql=$wpdb->prepare( "INSERT INTO {$wpdb->prefix}bd_divison (corporat_id, builder_number, sqft_high,sqft_low,price_high,price_low,number,name,descr,build_lot,sale_office_address,sub_address,driv_direction,phone,image,status)
				VALUES ( %d, %s, %d,%d,%d,%d, %s,%s,%s, %d, %s,%s,%s, %s, %s,%s)",
				
				array(
				$corporationid,
				$builderid,
				$Subdivision['@attributes']['SqftHigh'],
				$Subdivision['@attributes']['SqftLow'],
				$Subdivision['@attributes']['PriceHigh'],
				$Subdivision['@attributes']['PriceLow'],
				$Subdivision['SubdivisionNumber'],
				$Subdivision['SubdivisionName'],
				$Subdivision['SubDescription'],
				$Subdivision['BuildOnYourLot'],
				json_encode($Subdivision['SalesOffice']),
				json_encode($Subdivision['SubAddress']),
				$Subdivision['DrivingDirections'],
				json_encode($Subdivision['Phone']),
				$img,
				$Subdivision['@attributes']['Status']
				));  
				$res=$wpdb->query($sql);
				if($res){
				 echo '<span class="divisions">Division Number "'. $Subdivision['SubdivisionNumber'].'" has been inserted.</span></br>';
				}

								
				if(count($subdivision1->Plan)>0)
					{
						$divid=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_divison WHERE number=%s",$Subdivision['SubdivisionNumber']));
						
						savePlans($subdivision1->Plan,$builderid,$divid[0]->ID);
					}
			 }
		$i=$i+1;
	}
	
	return true;

	
	
}
/***************************Saving Plans*********************************************/
function savePlans($Plans,$builderid,$divid)
{
	global $wpdb;
			
	$i=0;
	foreach($Plans as $plan1)
	{
		
	$Plan=json_decode(json_encode((array) $plan1), 1);
	
			$update=checkPlan($Plan['PlanNumber'],$divid);
			 if($update!=0)
			 {
				if($update->Update_From_Xml==0)
				{
					 $res=$wpdb->query( $wpdb->prepare( "UPDATE  {$wpdb->prefix}bd_plan SET builder_id=%d, divison_id=%s, plan_name=%s, plan_type=%s, plan_number=%s, base_price=%d,base_sqft=%d,
					  descr=%s, stories=%d, bath=%d, bedrooms=%d, half_bath=%d, garage=%s, planimages=%s, brochure_url=%s	WHERE ID=%d ",
					 array(
						$builderid,
						$divid,
						$Plan['PlanName'],
						$Plan['@attributes']['Type'],
						$Plan['PlanNumber'],
						$Plan['BasePrice'],
						$Plan['BaseSqft'],
						$Plan['Description'],
						$Plan['Stories'],
						$Plan['Baths'],
						$Plan['Bedrooms'],		
						$Plan['HalfBaths'],
						json_encode($Plan['Garage']),
						json_encode($Plan['PlanImages']),
						$Plan['PlanBrochure'],
						$update->ID
						))); 
					
						 echo '<span class="plans">Plan Number "'. $Plan['PlanNumber'].'" has been updated.</span></br>';
						if(count($plan1->Spec)>0)
						{
							saveSpecification($plan1->Spec,$update->ID,$divid);
						}
										
					
				}
				else
				{
					 echo '<span class="plans">Plan Number "'. $Plan['PlanNumber'].'" has been skipped.</span></br>';
					
						if(count($Plan[Spec])>0)
						{
							//saveSpecification($Plan[Spec],$update->ID,$divid);
						}
				}
					
			 }
			 else
			 {
				$sql=$wpdb->prepare( "INSERT INTO {$wpdb->prefix}bd_plan 
				(
				builder_id,
				divison_id,
				plan_name,
				plan_type,
				plan_number,
				base_price,
				base_sqft,
				descr,
				stories,
				bath,
				bedrooms,
				half_bath,
				garage,
				planimages,
				brochure_url
				)
				VALUES (%d,%d,%s,%s,%s,%d,%d,%s,%d,%d,%d,%d,%s,%s,%s)",
				array(
				$builderid,
				$divid,
				$Plan['PlanName'],
				$Plan['@attributes']['Type'],
				$Plan['PlanNumber'],
				$Plan['BasePrice'],
				$Plan['BaseSqft'],
				$Plan['Description'],
				$Plan['Stories'],
				$Plan['Baths'],
				$Plan['Bedrooms'],		
				$Plan['HalfBaths'],
				json_encode($Plan['Garage']),
				json_encode($Plan['PlanImages']),
				$Plan['PlanBrochure']
				));  
				
				$res=$wpdb->query($sql);
				if($res)
				{
					 echo '<span class="plans">Plan Number "'. $Plan['PlanNumber'].'" has been Inserted.</span></br>';
				}
				if(count($plan1->Spec)>0)
				{
					$planid=$wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_plan  ORDER BY ID DESC LIMIT 1");
					saveSpecification($plan1->Spec,$planid[0]->ID,$divid);
				}
			 }
	}
	return true;
}


function saveSpecification($Sspecifications,$planid,$divid)
{
		global $wpdb;
		$update=checkSpecification($planid);
		
		
		echo 'No of Specification'.count($Sspecifications).'<hr>';
		
		
		foreach($Sspecifications as $specification)
		{
			$specifications=json_decode(json_encode((array) $specification), 1);
		
		if($update!=0)
		 {
			if($update->Update_From_Xml==0)
			{
				$sql=$wpdb->prepare( "UPDATE  {$wpdb->prefix}bd_specification SET Attributes=%s, SpecIsModel=%d,SpecNumber=%s, SpecAddress=%s, SpecPrice=%d, SpecStories=%d, SpecSqft=%d,SpecBaths=%d,
				  SpecHalfBaths=%d, SpecBedrooms=%d, SpecVirtualTour=%s, SpecImages=%s WHERE ID=%d ",
				 array(
					json_encode($specifications['@attributes']),
					$specifications['SpecIsModel'],
					$specifications['SpecNumber'],
					json_encode($specifications['SpecAddress']),
					$specifications['SpecPrice'],
					$specifications['SpecStories'],
					$specifications['SpecSqft'],
					$specifications['SpecBaths'],
					$specifications['SpecHalfBaths'],
					$specifications['SpecBedrooms'],
					json_encode($specifications['SpecVirtualTour']),
					json_encode($specifications['SpecImages']),				
					$update->ID
					));
					$res=$wpdb->query($sql);
					
					
						 echo '<span class="specifications green">Specification Number "'. $specifications['SpecNumber'].'" has been updated.</span></br>';
						if(count($specifications['Options'])>0)
						{
							saveSpecificationOption($specifications['Options'],$update->ID,$divid);
						}
					
			}
			else
			{
				echo '<span class="specifications">Specification Number "'. $specifications['SpecNumber'].'" has been skipped.</span>';
				if(count($specifications['Options'])>0)
				{
					//saveSpecificationOption($specifications['Options'],$update->ID,$divid);
				}
			}
		 }
		 else
		 {
				$sql=$wpdb->prepare( "INSERT INTO {$wpdb->prefix}bd_specification (	plan_id,Attributes,	SpecIsModel,SpecNumber,	SpecAddress,SpecPrice,SpecStories,SpecSqft,
				SpecBaths,	SpecHalfBaths,	SpecBedrooms,SpecVirtualTour,SpecImages,Options	)VALUES (%d,%s,%d,%s,%s,%d,%d,%d,%d,%d,%d,%s,%s,%d)",
				array(
				$planid,
				json_encode($specifications['@attributes']),
				$specifications['SpecIsModel'],
				$specifications['SpecNumber'],
				json_encode($specifications['SpecAddress']),
				$specifications['SpecPrice'],
				$specifications['SpecStories'],
				$specifications['SpecSqft'],
				$specifications['SpecBaths'],
				$specifications['SpecHalfBaths'],
				$specifications['SpecBedrooms'],
				json_encode($specifications['SpecVirtualTour']),
				json_encode($specifications['SpecImages']),
				count($specifications['Options']),
				));  
				$res=$wpdb->query($sql);
				if($res)
				{
					echo '<span class="specifications">Specification Number "'. $specifications['SpecNumber'].'" has been Inserted.</span>';
				}
				
				if(count($specifications['Options'])>0)
				{
					
					$specid=$wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_specification ORDER BY ID DESC LIMIT 1");
					
					saveSpecificationOption($specifications['Options'],$specid[0]->ID,$divid);
				}
		 }
		}
		
		
		
		
		
				
}

function saveSpecificationOption($specificationsOptions,$specid,$divid)
{
	global $wpdb;
	
	
	
	
	
	foreach($specificationsOptions['Option'] as $SpecOp)
	{
		$update=checkOption($SpecOp['OptionCode']);
		 if($update!=0)
		 {
			if($update->Update_From_Xml==0)
			{
				$sql=$wpdb->prepare( "UPDATE  {$wpdb->prefix}bd_specification_options SET OptionCode=%s, OptionDesc=%s, OptionGroupName=%s, Qty=%d, Price=%d, BuilderApproved=%d,CustomerApproved=%d,
				  CustomerDesc=%d WHERE ID=%d ",
				 array(
					$SpecOp['OptionCode'],
					$SpecOp['OptionDesc'],
					$SpecOp['OptionGroupName'],
					$SpecOp['Qty'],
					$SpecOp['Price'],
					$SpecOp['BuilderApproved'],
					$SpecOp['CustomerApproved'],
					$SpecOp['CustomerDesc'],
					$update->ID
					));
				 $res=$wpdb->query($sql);
				 echo '<span class="options">Option Code "'. $SpecOp['OptionCode'].'" has been updated.</span></br>';
			}
			else
			{
				 echo '<span class="options">Option Code "'. $SpecOp['OptionCode'].'" has been skipped.</span></br>';
			}
		}
		else
		{
			$sql=$wpdb->prepare( "INSERT INTO {$wpdb->prefix}bd_specification_options
			(
			Specification_id,
			OptionCode,
			OptionDesc,
			OptionGroupName,
			Qty,
			Price,
			BuilderApproved,
			CustomerApproved,
			CustomerDesc
			)
			VALUES (%d,%s,%s,%s,%d,%d,%d,%d,%s)",
			array(
			$specid,
			$SpecOp['OptionCode'],
			$SpecOp['OptionDesc'],
			$SpecOp['OptionGroupName'],
			$SpecOp['Qty'],
			$SpecOp['Price'],
			$SpecOp['BuilderApproved'],
			$SpecOp['CustomerApproved'],
			$SpecOp['CustomerDesc']
			)); 	
			$res=$wpdb->query($sql);
			if($res){ echo '<span class="options">Option Code "'. $SpecOp['OptionCode'].'" has been Inserted.</span></br>';}
		}
			
	}
	
	
	
}

/****************************Check it into database**********************************/
// 1. Corporation check

function checkCorporation($corporationemailid)
{
	global $wpdb;
	$corporation=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_corporations WHERE c_email =%s",$corporationemailid));
	$corporation=$corporation[0];
	
	if($corporation)
	{
		return $corporation;
	}
	else
	{
		return 0;
	}
}

//2. Builder Check
function checkBuilder($Buildernumber,$corpid)
{
	
	global $wpdb;
	$Builder=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_builders WHERE builder_number =%s  AND corporat_id=%d",$Buildernumber,$corpid));
	$Builder=$Builder[0];
	
	
	if($Builder)
	{
		return $Builder;
	}
	else
	{
		return 0;
	}
}
//3. Division
function checkDivision($Divisionnumber,$builderid)
{
	global $wpdb;
	$Division=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_divison WHERE number=%s AND builder_number=%d",$Divisionnumber,$builderid));
	$Division=$Division[0];
	
	if($Division)
	{
		return $Division;
	}
	else
	{
		return 0;
	}
}
// 4. Plans
function checkPlan($plannumber,$divid)
{
	global $wpdb;
	$Plan=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_plan WHERE plan_number =%s AND divison_id=%d ",$plannumber,$divid));
	$Plan=$Plan[0];
	
	if($Plan)
	{
		return $Plan;
	}
	else
	{
		return 0;
	}
}
//5.Specification
function checkSpecification($planid)
{
	global $wpdb;
	$Specification=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_specification WHERE plan_id =%d",$planid));
	$Specification=$Specification[0];
	
	if($Specification)
	{
		return $Specification;
	}
	else
	{
		return 0;
	}
}
//6.Options
function checkOption($optionnumber)
{
	global $wpdb;
	$Option=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_specification_options WHERE OptionCode =%s",$optionnumber));
	$Option=$Option[0];
	
	if($Option)
	{
		return $Option;
	}
	else
	{
		return 0;
	}
}








	
		
	
?>

</div>	
