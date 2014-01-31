<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb;

//$builders = $wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_builders");

$corporations = $wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_corporations");
$divid=$_GET['divid'];
$division=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_divison WHERE ID=%d",$divid));
$division=$division[0];



if(isset($_POST['submit']))
{	
	if($_POST['submit']=='Delete')
	{	
		/*getplans*/
					
		$planres=$wpdb->get_results( 
				$wpdb->prepare("SELECT ID,divison_id FROM {$wpdb->prefix}bd_plan WHERE divison_id = %d",
						$_POST['divid'] 
					)
			);
		/*Delete plan, specificationa,and speoption*/
		foreach($planres as $pID)
		{
						
			$planres=$wpdb->get_results( 
				$wpdb->prepare("SELECT ID,plan_id FROM {$wpdb->prefix}bd_specification WHERE plan_id = %d",
						$pID->ID 
					)
			);
			
			if(count($planres)>0){
				$wpdb->query( 
							$wpdb->prepare("DELETE FROM {$wpdb->prefix}bd_specification	 WHERE ID = %d",
									$planres->ID 
								)
						);
			 $wpdb->query( $wpdb->prepare("DELETE FROM {$wpdb->prefix}bd_specification_options WHERE Specification_id = %d ",
							$planres->ID 
						)
				);
			}
			
		}
			
		
			$wpdb->query( 
						$wpdb->prepare("DELETE FROM {$wpdb->prefix}bd_plan WHERE divison_id = %d",
								$_POST['divid'] 
							)
					);
		
		/*Delete Division*/
		$res=$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}bd_divison WHERE ID = %d",
								$_POST['divid'] 
							)
					);	
			
			
		 $redirect='?page=div_listing';
			if($res):?>
			<script>	
					alert('Division deleted successfully..!!!');
					window.location.assign("<?php echo $redirect;?>");
						</script>
	<?php endif;
	} 
	else
	{

	$Address[Address]=$_POST['Address'];
	$subAddress=$_POST['subaddress'];
	$phone=$_POST['phone'];
		$status=$_POST['Status'];

	$res=$wpdb->query( $wpdb->prepare( "UPDATE  {$wpdb->prefix}bd_divison SET  builder_number=%s, sqft_high=%d, sqft_low=%d, price_high=%d, price_low=%d,number=%s,
	 name=%s, descr=%s, build_lot=%d, sale_office_address=%s, sub_address=%s, driv_direction=%s, phone=%s, image=%s, status=%s,Update_From_Xml=%d,ShowTopo=%d,status=%d	WHERE ID=%d ",
	 array(
		
		$_POST['builder_number'],
		$_POST['@attributes']['SqftHigh'],
		$_POST['@attributes']['SqftLow'],
		$_POST['@attributes']['PriceHigh'],
		$_POST['@attributes']['PriceLow'],
		$_POST['division_number'],
		$_POST['division_name'],
		$_POST['division_descr'],
		$_POST['division_build_lot'],
		json_encode($Address),
		json_encode($subAddress),
		$_POST['driv_direction'],
		json_encode($phone),
		$_POST['image'],
		$_POST['Status'],
		$_POST['xmlupdate'],
		$_POST['ShowTopo'],
		$status,
		$divid
		))); 
	 
	 if($res)
	 {
		$message='<div class="updated below-h2" id="message"><p> Division has been updated.!!! </p></div>';
		$division=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_divison WHERE ID=%d",$divid));
		$division=$division[0];
		
	 }
	}
	
}
?>

<div class="wrap">
<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
<h2>Edit Division</h2>

<?php if(isset($message)){echo $message;}?>

	<form method="post" id="post">
		<div id="poststuff">
		
		<input type="hidden" name="divid" value="<?php echo $division->ID;?>"/>
		<ul>
			<li>
				<div class="label">Do Not Update By Xml </div>
				<div class="input-box">
				<input style="width:10px;" <?php if($division->Update_From_Xml==1){echo 'checked="checked"';}?> type="checkbox" value="1" name="xmlupdate"/>
				
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">Don't Show on the web</div>
				<div class="input-box">
				<input style="width:10px;" <?php if($division->status==1){echo 'checked="checked"';}?> type="checkbox" value="1" name="Status"/>
				
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">Show Topo</div>
				<div class="input-box">
				<input style="width:10px;" <?php if($division->ShowTopo==1){echo 'checked="checked"';}?> type="checkbox" value="1" name="ShowTopo"/>
				
				</div><div class="clear"></div>
			</li>
			
			<li>
				<div class="label">Builder ID</div>
				<div class="input-box"><input type="text" value="<?php echo $division->builder_number;?>" name="builder_number"/></div><div class="clear"></div>
			</li>
			
			<!--Attribute-->
			<li>
				<div class="label">Status</div>
				<div class="input-box"><input type="text" value="<?php echo $division->status;?>" name="Status"/></div><div class="clear"></div>
			</li>
					<li>
				<div class="label">PriceLow</div>
				<div class="input-box"><input type="text" value="<?php echo $division->price_low;?>" name="@attributes[PriceLow]"/></div><div class="clear"></div>
			</li>
					<li>
				<div class="label">PriceHigh</div>
				<div class="input-box"><input type="text" value="<?php echo $division->price_high;?>" name="@attributes[PriceHigh]"/></div><div class="clear"></div>
			</li>
					<li>
				<div class="label">SqftLow</div>
				<div class="input-box"><input type="text" value="<?php echo $division->sqft_low;?>" name="@attributes[SqftLow]"/></div><div class="clear"></div>
			</li>
					<li>
				<div class="label">SqftHigh</div>
				<div class="input-box"><input type="text" value="<?php echo $division->sqft_high;?>" name="@attributes[SqftHigh]"/></div><div class="clear"></div>
			</li>
			</li>
					<li>
				<div class="label">Phone</div>
				<?php $phone=json_decode($division->phone);
					 
				?>
				<div class="input-box">
				<table>
					<tr>
						<td>AreaCode</td><td>Prefix</td><td>Suffix</td>
					</tr>
					<tr>
						<td><input class="phone" type="text" value="<?php echo $phone->AreaCode;?>" name="phone[AreaCode]"/></td>
						<td><input class="phone"type="text" value="<?php echo $phone->Prefix;?>" name="phone[Prefix]"/>
						</td><td><input class="phone" type="text" value="<?php echo $phone->Suffix;?>" name="phone[Suffix]"/></td>
					</tr>
				</table>
				
				</div><div class="clear"></div>
			</li>
			<!--Attribute-->
			<li>
				<div class="label">Subdivision Number</div>
				<div class="input-box"><input type="text" value="<?php echo  $division->number;?>" name="division_number"/></div><div class="clear"></div>
			</li>
			<li>
				<div class="label">Subdivision Name</div>
				<div class="input-box"><input type="text" value="<?php echo  $division->name;?>"name="division_name"/></div><div class="clear"></div>
			</li>
			<li>
				<div class="label">SubDescription</div>
				<div class="input-box"><textarea value="" name="division_descr"><?php echo $division->descr;?></textarea></div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">BuildOnYourLot</div>
				<div class="input-box"><input type="text" value="<?php echo $division->build_lot;?>" name="division_build_lot"/></div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">SalesOffice</div>
				<div class="input-box">
					<?php $address=(array)json_decode($division->sale_office_address);
							$OutOfCommunity=(array)($address['Address']);
					?>
					
					<table>
						<tr>
							<td>City</td>
							<td><input type="text" name="Address[City]" value="<?php echo $address['Address']->City;?>"></td>
						</tr>
						<tr>
							<td>State</td>
							<td><input type="text" name="Address[State]" value="<?php echo $address['Address']->State;?>"></td>
						</tr>
						<tr>
							<td>Zip</td>
							<td><input type="text" name="Address[ZIP]" value="<?php echo $address['Address']->ZIP;?>"></td>
						</tr>
						<tr>
							<td>OutOfCommunity</td>
							<td><input type="text" name="Address[@Attribute][OutOfCommunity]" value="<?php echo $OutOfCommunity['@attributes']->OutOfCommunity;?>"></td>
						</tr>
					</table>
					
					
				</div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">SubAddress</div>
				<div class="input-box">
				
				<?php $subaddress=(array)json_decode($division->sub_address);	
					//print_r($subaddress);				
					
				?>
				
					
					<table>
						<tr>
							<td>Sub City</td>
							<td><input type="text" name="subaddress[SubCity]" value="<?php echo $subaddress['SubCity'];?>"></td>
						</tr>
						<tr>
							<td>Sub State</td>
							<td><input type="text" name="subaddress[SubState]" value="<?php echo $subaddress['SubState'];?>"></td>
						</tr>
						<tr>
							<td>SubZIP</td>
							<td><input type="text" name="subaddress[SubZIP]" value="<?php echo$subaddress['SubZIP'];?>"></td>
						</tr>
						<tr>
							<td>SubGeocode</td>
							<td>
								<table>
									<tr>
										<td>SubLatitude</td>
										<td><input type="text" name="subaddress[SubGeocode][SubLatitude]" value="<?php echo  $subaddress['SubGeocode']->SubLatitude;?>"></td>
									</tr>
									<tr>
										<td>SubLongitude</td>
										<td>
											<input type="text" name="subaddress[SubGeocode][SubLongitude]" value="<?php echo  $subaddress['SubGeocode']->SubLongitude;?>">
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				
				
				</div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">DrivingDirections</div>
				<div class="input-box"><textarea value="" name="driv_direction"><?php echo $division->driv_direction;?></textarea></div><div class="clear"></div>
			</li>
			<li>
				<div class="label">SubImage</div>
				<div class="input-box">
				<img style="width:80%;" src="<?php echo $division->image;?>"/>
				<input type="text" value="<?php echo $division->image;?>" name="image"/></div><div class="clear"></div>
			</li>
			<li>
				
				<div class="input-box"><input class="button" type="submit" value="Save Changes" name="submit"/>
				<a class="button" href="?page=list_plans&divid=<?php echo $division->ID; ?>">View Plans</a>
				<input class="button" onclick="if(!confirm('Are you sure to delete this division.All Information will be deleted related to this plan..'))return false;" type="submit" value="Delete" name="submit"/>
				</div>
			</li>
		<ul>
		
		
		
		</div>
	</form>
</div>	
<style>
.label{float:left;width:20%;}
.input-box{width:80%;float:right;}
.input-box input,textarea{width:500px;}
.input-box .button{width:auto;}
.clear{float:none;}
.phone{width:100px!important;}
</style>	