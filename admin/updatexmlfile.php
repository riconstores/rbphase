<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

class updatexml
{
	function updatexmlbyDB()
	{
		$corporation=$this->getCorporationArray();
		return $corporation;
	}
	
	function getCorporationArray()
	{
		global $wpdb;
		$corporation=array();
		
		$corporations=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}bd_corporations");
		
		foreach($corporations as $corp)
		{
			$corporation['Corporation']['CorporateBuilderNumber']=$corp->c_number;
			$corporation['Corporation']['CorporateState']=$corp->c_state;
			$corporation['Corporation']['CorporateName']=$corp->c_name;
			$corporation['Corporation']['CorporateReportingEmail']=$corp->c_email;
			$corporation['Corporation']['Builder']=$this->getBuilders();
						
		}
		
		
		
		
		return $corporation;
	}
	function getBuilders()
	{
		global $wpdb;
		$builder=array();
		
		$builders=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}bd_builders");
		$i=0;
		foreach($builders as $build)
		{
			$builder[$i][BuilderNumber]=$build->builder_number;
			$builder[$i][BrandName]=$build->brand_name;
			$builder[$i][ReportingName]=$build->reporting_name;
			$builder[$i][DefaultLeadsEmail]=$build->lead_email;
			if($this->getSubdivisions($build->ID)){
             $builder[$i][Subdivision]=$this->getSubdivisions($build->ID);
			 }
			 
			$i++;
		}
		
		return $builder;
	}
	function getSubdivisions($builderid)
	{
		global $wpdb;
		$Subdivision=array();
		
		$divisions=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}bd_divison where builder_number=$builderid ");
		$i=0;
		foreach($divisions as $divi)
		{
			$Subdivision[$i]['@attributes'][Status]=$divi->status;
			$Subdivision[$i]['@attributes'][PriceLow]=$divi->price_low;
			$Subdivision[$i]['@attributes'][PriceHigh]=$divi->price_high;
			$Subdivision[$i]['@attributes'][SqftLow]=$divi->sqft_low;
			$Subdivision[$i]['@attributes'][SqftHigh]=$divi->sqft_high;
			$Subdivision[$i][SubdivisionNumber] = $divi->number;
			$Subdivision[$i][SubdivisionName] =$divi->name;
			$Subdivision[$i][SubDescription] = $divi->descr;
			$Subdivision[$i][BuildOnYourLot] =$divi->build_lot;
			$Subdivision[$i][SalesOffice]=(array)json_decode($divi->sale_office_address);
			$Subdivision[$i][SubAddress]=(array)json_decode($divi->sub_address);
            $Subdivision[$i] [DrivingDirections]=$divi->driv_direction;
			$Subdivision[$i][Phone]=(array)json_decode($divi->phone);
			$Subdivision[$i][SubImage]=$divi->image;
			$Subdivision[$i][Plan]=$this->getPlans($divi->ID);
			
			$i++;
		}
		
		return $Subdivision;
	}
	function getPlans($divid)
	{
		global $wpdb;
		$Plan=array();
		
		$plans=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}bd_plan where divison_id=$divid ");
		$i=0;
		foreach($plans as $plan)
		{
			$Plan[$i]['@attributes'][Type]=$plan->plan_type;
			$Plan[$i][PlanNumber]=$plan->plan_number;
            $Plan[$i][PlanName]=$plan->plan_name;
			$Plan[$i][BasePrice]=$plan->base_price;
            $Plan[$i][BaseSqft]=$plan->base_sqft;
			$Plan[$i][Description]=$plan->descr;
            $Plan[$i][Stories]=$plan->stories;
			$Plan[$i][Baths]=$plan->bath;
            $Plan[$i][Bedrooms]=$plan->bedrooms;
			$Plan[$i][HalfBaths]=$plan->half_bath;
            $Plan[$i][Garage]=$plan->garage;
            $Plan[$i] [PlanImages] =(array)json_decode($plan->planimages);  
			$Plan[$i][Spec]=$this->getSpecification($plan->ID);			   
               $Plan[$i]  [PlanBrochure] = $plan->brochure_url;                                
			
			$i++;
		}
		
		return $Plan;
	}
	function getSpecification($planid)
	{
		global $wpdb;
		$Spec=array();
		
		$Specifications=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}bd_specification where plan_id=$planid ");
		$i=0;
		foreach($Specifications as $Specification)
		{
			$Spec[$i]['@attributes']=(array)json_decode($Specification->Attributes);
		   $Spec[$i]['SpecIsModel']=$Specification->SpecIsModel;
			$Spec[$i]['SpecNumber']=$Specification->SpecNumber;
			$Spec[$i]['SpecAddress']=(array)json_decode($Specification->SpecAddress);
			$Spec[$i]['SpecPrice']=$Specification->SpecPrice;			   
			$Spec[$i]['SpecStories']=$Specification->SpecStories;
			$Spec[$i]['SpecSqft']=$Specification->SpecSqft;
			$Spec[$i]['SpecBaths']=$Specification->SpecBaths;
			$Spec[$i]['SpecHalfBaths']=$Specification->SpecHalfBaths;
			$Spec[$i]['SpecBedrooms']=$Specification->SpecBedrooms;
			$Spec[$i]['SpecVirtualTour']=json_decode($Specification->SpecVirtualTour);
             $Spec[$i]['SpecImages']=json_decode($Specification->SpecImages);                    
			$Spec[$i]['Options']['Option']=$this->getOptions($Specification->ID);
			$i++;
		}
		
		return $Spec;
	}
	function getOptions($spid)
	{
		global $wpdb;
		$SpecOP=array();
		
		$SpecOptions=$wpdb->get_results("SELECT * FROM {$wpdb->prefix}bd_specification_options where Specification_id=$spid ");
		$i=0;
		foreach($SpecOptions as $OP)
		{
			
			$SpecOP[$i][OptionCode] =$OP->OptionCode;
			$SpecOP[$i][OptionDesc] =$OP->OptionDesc;
			$SpecOP[$i][OptionGroupName] =$OP->OptionGroupName;
			$SpecOP[$i][Qty] =$OP->Qty;
			$SpecOP[$i][Price] =$OP->Price;
			$SpecOP[$i][BuilderApproved] =$OP->BuilderApproved;
			$SpecOP[$i][CustomerApproved] =$OP->CustomerApproved;
			$SpecOP[$i][CustomerDesc] =$OP->CustomerDesc;
			$i++;
		}
		
		return $SpecOP;
	}
	
}
/**Class to update database*/
class updateDBfromXML
{
	function SaveXMLData($data)
	{
		global $wpdb;
		$CorporateBuilderNumber=$data['Corporation']['CorporateBuilderNumber'];
		$CorporateState=$data['Corporation']['CorporateState'];
		$CorporateName=$data['Corporation']['CorporateName'];
		$CorporateReportingEmail=$data['Corporation']['CorporateReportingEmail'];
		$status=0;
		 
		 $update=$this->checkCorporation($data['Corporation']['CorporateReportingEmail']);
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
					$this->saveBuilders($data['Corporation']['Builder'],$update->ID);
			
				
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
				
				$this->saveBuilders($data['Corporation']['Builder'],$corpid[0]->ID);
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
				
				$update=$this->checkBuilder($builder['BuilderNumber'],$corporationid);
				 if($update!=0)
				 {
					if($update->Update_From_Xml==0)
					{
						$res=$wpdb->query( $wpdb->prepare( "UPDATE  {$wpdb->prefix}bd_builders SET builder_number=%s ,brand_name=%s,reporting_name=%s,lead_email=%s WHERE ID=%d ",
		 array($builder['BuilderNumber'], $builder['BrandName'],$builder['ReportingName'],$builder['DefaultLeadsEmail'], $update->ID)) ); 
						
						
							 echo '<span class="builders"> Builder Number  "'. $builder['BuilderNumber'].'"  has been updated.</span></br>';
							 if(count($builder['Subdivision'])>0)
							{
								$this->saveSubdivision($builder['Subdivision'],$update->ID,$corporationid);
							}
						
						
						
					}
					else
					{
						echo '<span class="builders">Builder Number "'. $builder['BuilderNumber'].'" has been skipped.</span></br>';
						 if(count($builder['Subdivision'])>0)
							{
								//saveSubdivision($builder['Subdivision'],$update->ID,$corporationid);
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
							
							$this->saveSubdivision($builder['Subdivision'],$builderid[0]->ID,$corporationid);
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
			$update=$this->checkDivision($Subdivision['SubdivisionNumber'],$builderid);//checkBuilder();
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
								$this->savePlans($Subdivision[Plan],$builderid,$update->ID);
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
							
							$this->savePlans($Subdivision[Plan],$builderid,$divid[0]->ID);
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
				$update=$this->checkPlan($Plan['PlanNumber'],$divid);
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
								$this->saveSpecification($Plan[Spec],$update->ID,$divid);
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
					if(count($Plan[Spec])>0)
					{
						$planid=$wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_plan  ORDER BY ID DESC LIMIT 1");
						$this->saveSpecification($Plan[Spec],$planid[0]->ID,$divid);
					}
				 }
		}
		return true;
	}


	function saveSpecification($specifications,$planid,$divid)
	{
			global $wpdb;
			$update=$this->checkSpecification($planid);
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
								$this->saveSpecificationOption($specifications['Options'],$update->ID,$divid);
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
						
						$this->saveSpecificationOption($specifications['Options'],$specid[0]->ID,$divid);
					}
			 }
			
					
	}

	function saveSpecificationOption($specificationsOptions,$specid,$divid)
	{
		global $wpdb;
		
		foreach($specificationsOptions['Option'] as $SpecOp)
		{
			$update=$this->checkOption($SpecOp['OptionCode']);
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
}








/*
function printAllNodes($nodes,$doc) {
	if(is_object($nodes))
	{
		$nodes=(array)$nodes;
	}
  if (!is_array($nodes)) {
		echo '-'.$nodes.'<br>';
     return;
   }
	
   foreach($nodes as $key=> $node) {
		echo $key;
		 $r = $doc->createElement($key); 
		$doc->appendChild( $r ); 
		if(is_array( $node)){echo '<hr>';}
		
		printAllNodes($node,$doc);
  }
 }*/



?>
	