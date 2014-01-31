<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
global $wpdb;
$corporations = $wpdb->get_results("SELECT ID,c_name FROM {$wpdb->prefix}bd_corporations");
if($_GET['corporation'])
{
	$builders=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_builders HAVING corporat_id=%d",$_GET['corporation']));
}
else{
$builders=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_builders HAVING corporat_id=%d",$corporations[0]->ID));
}
if(isset($_POST['submit']))
{	
	$Address[Address]=$_POST['Address'];
	$subAddress=$_POST['subaddress'];
	$phone=$_POST['phone'];
		
		$sql=$wpdb->prepare( "INSERT INTO {$wpdb->prefix}bd_divison (corporat_id, builder_number, sqft_high,sqft_low,price_high,price_low,number,name,descr,build_lot,sale_office_address,sub_address,driv_direction,phone,image,status)
				VALUES ( %d, %s, %d,%d,%d,%d, %s,%s,%s, %d, %s,%s,%s, %s, %s,%s)",
				array(
				$_POST['corporation'],
				$_POST['builder'],
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
				));  
		
		
	 $res=$wpdb->query( $sql); 
	 if($res)
	 {
		$message='<div class="updated below-h2" id="message"><p> Division has been inserted.!!! </p></div>';
		
		
	 }
	
	
}
$rurl=$_SERVER["REQUEST_URI"];
 $rurl=explode('&',$rurl);
?>
<script src="<?php echo plugins_url();?>/builder_design/frontend/js/jquery-1.9.0.min.js"></script>
<script src="<?php echo plugins_url();?>/builder_design/frontend/js/jquery.validate.min.js"></script>
<script>
  
  // When the browser is ready...
  $(function() {
  
    // Setup form validation on the #register-form element
    $("#myform").validate({
    
        // Specify the validation rules
        rules: {
			builder: "required",
            division_number: "required",
            division_name: "required",
			"Address[City]":"required",
			"Address[State]":"required",
           "Address[ZIP]":"required",
		   "subaddress[SubCity]":"required",
		   "subaddress[SubState]":"required",
		   "subaddress[SubZIP]":"required",
        },       
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
</script>
<style type="text/css">
    .label {width:100px;float:left;font-weight:bold;}
   label.error, .output {color:#FB3A3A;font-weight:bold; padding-top: 5px;padding-left: 9px;
   background:url('<?php echo plugins_url();?>/builder_design/frontend/css/i_asc_arrow.gif')no-repeat;
    width: 100%;
    float: left;}
   select.error,input.error {border:1px solid #FB3A3A!important;}
</style>
<div class="wrap">
<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
<h2>Add Subdivision</h2>

<?php if(isset($message)){echo $message;}?>

	<form method="post" id="myform">
		<div id="poststuff">
		<ul>
			<li>
				<div class="label">Do Not Update By Xml </div>
				<div class="input-box">
				<input style="width:10px;" <?php if($division->Update_From_Xml==1){echo 'checked="checked"';}?> type="checkbox" value="1" name="xmlupdate"/>
				
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">Select Corporation</div>
				<div class="input-box">
				<select name="corporation" onchange="window.location=this.value;">
				
				<?php foreach($corporations as $corporation):?>
				<option <?php if($_GET['corporation']==$corporation->ID):?> selected="selected" <?php endif;?> value="http://<?php echo $_SERVER["HTTP_HOST"].$rurl[0] ;?>&corporation=<?php echo $corporation->ID; ?>"><?php echo $corporation->c_name;?></option>
					
			
				<?php endforeach;?>
				</select>
			</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">Select Builder</div>
				<div class="input-box">
				<select id="builder" name="builder">
				<option value=''>Select Builder</option>
				<?php foreach($builders as $builder):?>
				<option value="<?php echo $builder->ID;?>">
				<?php echo $builder->brand_name;?></option>
					
			
				<?php endforeach;?>
			</select></div><div class="clear"></div>
			</li>
			
			
			<!--Attribute-->
			<li>
				<div class="label">Status</div>
				<div class="input-box">
				<select name="Status" id="Status">
					<option value="active">Active</option>
					<option value="deactive">DeActive</option>
				</select>
				
				</div><div class="clear"></div>
			</li>
					<li>
				<div class="label">PriceLow</div>
				<div class="input-box">
				<input type="text" value="<?php echo $_POST['@attributes']['PriceLow'];?>" name="@attributes[PriceLow]"/></div><div class="clear"></div>
			</li>
					<li>
				<div class="label">PriceHigh</div>
				<div class="input-box"><input type="text" value="<?php echo $_POST['@attributes']['PriceHigh'];?>" name="@attributes[PriceHigh]"/></div><div class="clear"></div>
			</li>
					<li>
				<div class="label">SqftLow</div>
				<div class="input-box"><input type="text" value="<?php echo $_POST['@attributes']['SqftLow'];?>" name="@attributes[SqftLow]"/></div><div class="clear"></div>
			</li>
					<li>
				<div class="label">SqftHigh</div>
				<div class="input-box"><input type="text" value="<?php echo $_POST['@attributes']['SqftHigh'];?>" name="@attributes[SqftHigh]"/></div><div class="clear"></div>
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
						<td><input class="phone" type="text" value="<?php echo $_POST['phone']['AreaCode'];?>" name="phone[AreaCode]"/></td>
						<td><input class="phone"type="text" value="<?php echo $_POST['phone']['Prefix'];?>" name="phone[Prefix]"/>
						</td><td><input class="phone" type="text" value="<?php echo $_POST['phone']['Suffix'];?>" name="phone[Suffix]"/></td>
					</tr>
				</table>
				
				</div><div class="clear"></div>
			</li>
			<!--Attribute-->
			<li>
				<div class="label">Subdivision Number</div>
				<div class="input-box">
				<input type="text" value="" id="division_number" name="division_number"/></div><div class="clear"></div>
			</li>
			<li>
				<div class="label">Subdivision Name</div>
				<div class="input-box"><input type="text" value="" id="division_name" name="division_name"/></div><div class="clear"></div>
			</li>
			<li>
				<div class="label">SubDescription</div>
				<div class="input-box"><textarea value="" name="division_descr"></textarea></div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">BuildOnYourLot</div>
				<div class="input-box"><input type="text" value="" name="division_build_lot"/></div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">SalesOffice</div>
				<div class="input-box">
					
					<table>
						<tr>
							<td>City</td>
							<td><input type="text" id="Address[City]" name="Address[City]" value=""></td>
						</tr>
						<tr>
							<td>State</td>
							<td><input type="text" id="Address[State]" name="Address[State]" value=""></td>
						</tr>
						<tr>
							<td>Zip</td>
							<td><input type="text" id="Address[ZIP]" name="Address[ZIP]" value=""></td>
						</tr>
						<tr>
							<td>OutOfCommunity</td>
							<td><input type="text" name="Address[@Attribute][OutOfCommunity]" value=""></td>
						</tr>
					</table>
					
					
				</div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">SubAddress</div>
				<div class="input-box">
				
					<table>
						<tr>
							<td>Sub City</td>
							<td><input type="text" name="subaddress[SubCity]" value=""></td>
						</tr>
						<tr>
							<td>Sub State</td>
							<td><input type="text" name="subaddress[SubState]" value=""></td>
						</tr>
						<tr>
							<td>SubZIP</td>
							<td><input type="text" name="subaddress[SubZIP]" value=""></td>
						</tr>
						<tr>
							<td>SubGeocode</td>
							<td>
								<table>
									<tr>
										<td>SubLatitude</td>
										<td><input type="text" name="subaddress[SubGeocode][SubLatitude]" value="<?php echo  $_POST['subaddress']['SubGeocode'][SubLatitude];?>"></td>
									</tr>
									<tr>
										<td>SubLongitude</td>
										<td>
											<input type="text" name="subaddress[SubGeocode][SubLongitude]" value="<?php echo  $_POST['subaddress']['SubGeocode']['SubLongitude'];?>">
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
				<div class="input-box"><textarea value="" name="driv_direction"><?php echo $_POST['driv_direction'];?></textarea></div><div class="clear"></div>
			</li>
			<li>
				<div class="label">SubImage Url</div>
				<div class="input-box">
				
				<input type="text" value="<?php echo $_POST['image'];;?>" name="image"/></div><div class="clear"></div>
			</li>
			
			<li>
				
				<div class="input-box"><input class="button" type="submit" value="Add Division" name="submit"/></div>
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