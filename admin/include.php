<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

?>

<?php


/*--Save Data from XML Files--*/
function SaveXMLData($fname)
{
	global $wpdb;
	$url=BP.DS.'xmlimport'.DS.$fname;
	$data=json_decode(json_encode((array) simplexml_load_file($url)), 1);
	//echo '<pre>';
	//print_r($data['Corporation']);
	$CorporateBuilderNumber=$data['Corporation']['CorporateBuilderNumber'];
	$CorporateState=$data['Corporation']['CorporateState'];
	$CorporateName=$data['Corporation']['CorporateName'];
	$CorporateReportingEmail=$data['Corporation']['CorporateReportingEmail'];
	$status=0;
	 
	 $update=checkCorporation($data['Corporation']['CorporateReportingEmail']);
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
				saveBuilders($data['Corporation']['Builder'],$update->corporat_id);
		
			
		}
		else
		{	echo '<span class="corporations">Corporation <b>"'.$CorporateName. '"</b> has been skipped..</span>'.'</br>';
				saveBuilders($data['Corporation']['Builder'],$update->corporat_id);
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
			
			saveBuilders($data['Corporation']['Builder'],$corpid[0]->ID);
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
			
			$update=checkBuilder($builder['BuilderNumber'],$corporationid);
			 if($update!=0)
			 {
				if($update->Update_From_Xml==0)
				{
					$res=$wpdb->query( $wpdb->prepare( "UPDATE  {$wpdb->prefix}bd_builders SET builder_number=%s ,brand_name=%s,reporting_name=%s,lead_email=%s WHERE ID=%d ",
	 array($builder['BuilderNumber'], $builder['BrandName'],$builder['ReportingName'],$builder['DefaultLeadsEmail'], $update->ID)) ); 
					
					
						 echo '<span class="builders"> Builder Number  "'. $builder['BuilderNumber'].'"  has been updated.</span></br>';
						 if(count($builder['Subdivision'])>0)
						{
							saveSubdivision($builder['Subdivision'],$update->ID,$corporationid);
						}
					
					
					
				}
				else
				{
					echo '<span class="builders">Builder Number "'. $builder['BuilderNumber'].'" has been skipped.</span></br>';
					 if(count($builder['Subdivision'])>0)
						{
							saveSubdivision($builder['Subdivision'],$update->ID,$corporationid);
						}
				}
					
			 }
			 else
			 {
					$sql=$wpdb->prepare( "INSERT INTO {$wpdb->prefix}bd_builders (corporat_id, builder_number, brand_name,reporting_name,lead_email) VALUES ( %d, %s, %s,%s,%s )",
					array( $corporationid,$builder['BuilderNumber'],
					$builder['BrandName'],
					$builder['ReportingName'],
					$builder['DefaultLeadsEmail'])  
				);
				
					$res=$wpdb->query($sql);
				if($res)
				{
					echo '<span class="builders">Builder Number  '.$builder['BuilderNumber'].' has been Inserted successfully..!!</span> <br>';
				
					if(count($builder['Subdivision'])>0)
					{
						$builderid=$wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}bd_builders WHERE builder_number=%d",$builder['BuilderNumber']));
						
						saveSubdivision($builder['Subdivision'],$builderid[0]->ID,$corporationid);
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
	foreach($Subdivisions as $Subdivision)
	{
		$update=checkDivision($Subdivision['SubdivisionNumber'],$builderid);//checkBuilder();
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
						$Subdivision['SubImage'],
						$Subdivision['@attributes']['Status'],
						$update->ID
						))); 
					
					
						 echo '<span class="divisions">Division Id "'. $Subdivision['SubdivisionNumber'].'" has been updated.</span></br>';
						if(count($Subdivision[Plan])>0)
						{
							savePlans($Subdivision[Plan],$builderid,$update->ID);
						}
					
					
					
				}
				else
				{
					 echo '<span class="divisions">Division Number "'. $Subdivision['SubdivisionNumber'].'" has been skipped.</span></br>';
					if(count($Subdivision[Plan])>0)
						{
							savePlans($Subdivision[Plan],$builderid,$update->ID);
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
				$Subdivision['SubImage'],
				$Subdivision['@attributes']['Status']
				));  
				$res=$wpdb->query($sql);
				if($res){
				 echo '<span class="divisions">Division Number "'. $Subdivision['SubdivisionNumber'].'" has been inserted.</span></br>';
				}
				
				
				if(count($Subdivision[Plan])>0)
					{
						$divid=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_divison WHERE number=%s",$Subdivision['SubdivisionNumber']));
						
						savePlans($Subdivision[Plan],$builderid,$divid[0]->ID);
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
	foreach($Plans as $Plan)
	{
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
						if(count($Plan[Spec])>0)
						{
							saveSpecification($Plan[Spec],$update->ID,$divid);
						}
										
					
				}
				else
				{
					 echo '<span class="plans">Plan Number "'. $Plan['PlanNumber'].'" has been skipped.</span></br>';
					
						if(count($Plan[Spec])>0)
						{
							saveSpecification($Plan[Spec],$update->ID,$divid);
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
				if(count($Plan[Spec])>0)
				{
					$planid=$wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_plan  ORDER BY ID DESC LIMIT 1");
					saveSpecification($Plan[Spec],$planid[0]->ID,$divid);
				}
			 }
	}
	return true;
}


function saveSpecification($specifications,$planid,$divid)
{
		global $wpdb;
		$update=checkSpecification($planid);
		 if($update!=0)
		 {
			if($update->Update_From_Xml==0)
			{
				$sql=$wpdb->prepare( "UPDATE  {$wpdb->prefix}bd_specification SET Attributes=%s, SpecNumber=%s, SpecAddress=%s, SpecPrice=%d, SpecStories=%d, SpecSqft=%d,SpecBaths=%d,
				  SpecHalfBaths=%d, SpecBedrooms=%d, SpecVirtualTour=%s, SpecImages=%s WHERE ID=%d ",
				 array(
					json_encode($specifications['@attributes']),
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
					saveSpecificationOption($specifications['Options'],$update->ID,$divid);
				}
			}
		 }
		 else
		 {
				$sql=$wpdb->prepare( "INSERT INTO {$wpdb->prefix}bd_specification (	plan_id,Attributes,	SpecNumber,	SpecAddress,SpecPrice,SpecStories,SpecSqft,
				SpecBaths,	SpecHalfBaths,	SpecBedrooms,SpecVirtualTour,SpecImages,Options	)VALUES (%d,%s,%s,%s,%d,%d,%d,%d,%d,%d,%s,%s,%d)",
				array(
				$planid,
				json_encode($specifications['@attributes']),
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

	