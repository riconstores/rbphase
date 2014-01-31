<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb;

$plan=$wpdb->get_results($wpdb->prepare("SELECT ID,plan_name FROM {$wpdb->prefix}bd_plan WHERE ID=%d",$_GET['planid']));
$plan=$plan[0];

if(isset($_POST['submit']))
{		
	 $sql=$wpdb->prepare( "INSERT INTO {$wpdb->prefix}bd_specification (	plan_id,Attributes,	SpecIsModel,SpecNumber,	SpecAddress,SpecPrice,SpecStories,SpecSqft,
				SpecBaths,	SpecHalfBaths,	SpecBedrooms,SpecVirtualTour,SpecImages,Options	)VALUES (%d,%s,%d,%s,%s,%d,%d,%d,%d,%d,%d,%s,%s,%d)",
				array(
				$_POST['planspecid'],
				json_encode($_POST['Attributes']),
				$specifications['SpecIsModel'],
				$_POST['SpecNumber'],
				json_encode($_POST['address']),
				$_POST['SpecPrice'],
				$_POST['SpecStories'],
				$_POST['SpecSqft'],
				$_POST['SpecBaths'],
				$_POST['SpecHalfBaths'],
				$_POST['SpecBedrooms'],
				json_encode($_POST['SpecVirtualTour']),
				json_encode($_POST['Planimages']),
				0,
				));  
				$res=$wpdb->query($sql);
	  
	 if($res)
	 {
		$message='<div class="updated below-h2" id="message"><p> Plan Specification has been inserted.!!! </p></div>';
			
	 }
	
	
}

?>

<div class="wrap">
<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
<h2>Add Specification</h2>

<?php if(isset($message)){echo $message;}?>

	<form method="post" id="myform">
		<div id="poststuff">
		<input type="hidden" name="planspecid" value="<?php echo $plan->ID; ?>"/>
		<div style="background:#ccc;color:#000;padding:10px;"><b>Plan : <?php echo $plan->plan_name; ?></b></div>
		<ul>
			<li>
				<div class="label">Do Not Update By Xml </div>
				<div class="input-box">
				<input style="width:10px;" <?php if($plan->Update_From_Xml==1){echo 'checked="checked"';}?> type="checkbox" value="1" name="xmlupdate"/>
				
				</div><div class="clear"></div>
			</li>
			
			
			
			
			<!--Attribute-->
			<li>
				<div class="label">Attributes</div>
				<div class="input-box">
								<table>
											<tbody><tr>
							<td>Type</td>
							<td><input type="text" name="Attributes[Type]" value="SingleFamily"></td>
						</tr>
									</tbody></table>
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">SpecNumber</div>
				<div class="input-box"><input type="text" value="" name="SpecNumber"/></div><div class="clear"></div>
			</li>
			<li>
				<div class="label">SpecAddress</div>
				<div class="input-box">
				<table>
												
														<tbody><tr>
									<td> SpecStreet1</td>
									<td> <input type="text" value="123 Training Lane" name="address[SpecStreet1]"></td>
								</tr>
												
												
														<tr>
									<td> SpecCity</td>
									<td> <input type="text" value="Leland" name="address[SpecCity]"></td>
								</tr>
												
												
														<tr>
									<td> SpecState</td>
									<td> <input type="text" value="NC" name="address[SpecState]"></td>
								</tr>
												
												
														<tr>
									<td> SpecZIP</td>
									<td> <input type="text" value="28451" name="address[SpecZIP]"></td>
								</tr>
												
												
																				<tr>
									<td> SpecLatitude</td>
									<td> <input type="text" value="34.267417" name="address[SpecGeocode][SpecLatitude]"></td>
								</tr>
														<tr>
									<td> SpecLongitude</td>
									<td> <input type="text" value="-78.086131" name="address[SpecGeocode][SpecLongitude]"></td>
								</tr>
												
												
					</tbody></table>
				
				
				
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">SpecPrice</div>
				<div class="input-box"><input type="text" value="" name="SpecPrice"/></div><div class="clear"></div>
			</li>
			<li>
				<div class="label">SpecIsModel</div>
				<div class="input-box">
				<select name="SpecIsModel">
					<option selected="selected" value="1">Yes</option>
					<option value="0">No</option>
				</select>
				</div><div class="clear"></div>
			</li>
			
			<li>
				<div class="label">SpecStories</div>
				<div class="input-box"><input type="text" value="" name="SpecStories"/></div><div class="clear"></div>
			</li>
			
			<li>
				<div class="label">SpecSqft</div>
				<div class="input-box"><input type="text" value="" name="SpecSqft"/></div><div class="clear"></div>
			</li>
			
			<li>
				<div class="label">SpecBaths</div>
				<div class="input-box"><input type="text" value="" name="SpecBaths"/></div><div class="clear"></div>
			</li>
			<li>
				<div class="label">SpecHalfBaths</div>
				<div class="input-box"><input type="text" value="" name="SpecHalfBaths"/></div><div class="clear"></div>
			</li>
			<li>
				<div class="label">SpecBedrooms</div>
				<div class="input-box"><input type="text" value="" name="SpecBedrooms"/></div><div class="clear"></div>
			</li>
			
			<li>
				<div class="label">SpecVirtualTour</div>
				<div class="input-box">
				<input type="text" value="" name="SpecVirtualTour"/>
				</div><div class="clear"></div>
			</li>
			
			<li>
				<div class="label">Spec Images</div>
				<div class="input-box">
					<div class="imgadd">
						<h4>ElevationImage</h4>
						<input type="text" name="Planimages[ElevationImage]"/>
					</div>
					<div class="imgadd">
						<h4>InteriorImage</h4>
						<input type="text" name="Planimages[InteriorImage]"/>
					</div>
					<div class="imgadd">
						<h4>FloorPlanImage</h4>
						<input type="text" name="Planimages[FloorPlanImage]"/>
					</div>
								
				</div>
				<div class="clear"></div>
			</li>
			
			<li>
				
				<div class="input-box">
				<input class="button" type="submit" value="ADD Specification" name="submit"/>
				
				<?php 
				$oplist=$wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}bd_specification_options HAVING Specification_id=%d",$planspec->ID));
				if($oplist):?>
					<a class="button" href="?page=builder_design/admin/edit/specification_option_list.php&specid=<?php echo $planspec->ID;?>"> View Specification Option List</a>
				<?php endif;?>
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
  .label {float:left;font-weight:bold;}
   label.error, .output {color:#FB3A3A;font-weight:bold; padding-top: 5px;padding-left: 9px;
   background:url('<?php echo plugins_url();?>/builder_design/frontend/css/i_asc_arrow.gif')no-repeat;
    width: 100%;
    float: left;}
   select.error,input.error {border:1px solid #FB3A3A!important;}
</style>	
<script src="<?php echo plugins_url();?>/builder_design/frontend/js/jquery-1.9.0.min.js"></script>
<script src="<?php echo plugins_url();?>/builder_design/frontend/js/jquery.validate.min.js"></script>
<script>
  
  // When the browser is ready...
  $(function() {
  
    // Setup form validation on the #register-form element
    $("#myform").validate({
    
        // Specify the validation rules
        rules: {
			SpecNumber: "required",
            SpecPrice: "required",
            SpecStories: "required",
			SpecSqft:"required",
			SpecBaths:"required",
           SpecHalfBaths:"required",
		   SpecBedrooms:"required",
		   
        },       
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
</script>	