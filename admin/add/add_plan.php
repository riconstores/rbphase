<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb;


$division=$wpdb->get_results($wpdb->prepare("SELECT ID,builder_number,name FROM {$wpdb->prefix}bd_divison WHERE ID=%d",$_GET['divid']));
if(isset($_POST['submit']))
{	
	//echo '<pre>';
		//print_r($_POST['Planimages']);
	//echo json_encode($_POST['Planimages']);
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
				$division[0]->builder_number,
				$_POST['divisionid'],
				$_POST['plan_name'],
				$_POST['plan_type'],
				$_POST['plan_number'],
				$_POST['base_price'],
				$_POST['base_sqft'],
				$_POST['descr'],
				$_POST['stories'],
				$_POST['bath'],
				$_POST['bedrooms'],		
				$_POST['half_bath'],
				json_encode($_POST['garage']),
				json_encode($_POST['Planimages']),
				$_POST['brochure_url']
				));  
	
	 $res=$wpdb->query( $sql); 
	 if($res)
	 {
		$message='<div class="updated below-h2" id="message"><p> Plan has been Inserted.!!! </p></div>';
			
	 }
	
	
}$rurl=$_SERVER["REQUEST_URI"];
 $rurl=explode('&',$rurl);
?>

<div class="wrap">
<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
<h2>Add New Plan</h2>
<div style="background:#ccc;color:#000;padding:10px;"><b>Division : <?php echo $division[0]->name; ?></b></div>
<?php if(isset($message)){echo $message;}?>

	<form method="post" id="myform">
		<div id="poststuff">
		
		<input type="hidden" name="divisionid" value="<?php echo $_GET['divid']?>"/>
		<ul>
			
			<li>
				<div class="label">Do Not Update By Xml </div>
				<div class="input-box">
				<input style="width:10px;" <?php if($plan->Update_From_Xml==1){echo 'checked="checked"';}?> type="checkbox" value="1" name="xmlupdate"/>
				
				</div><div class="clear"></div>
			</li>
				
			
			
			
			<!--Attribute-->
			<li>
				<div class="label">Plan Number</div>
				<div class="input-box">
				<input type="text" value="" name="plan_number"/></div><div class="clear"></div>
			</li>
					<li>
				<div class="label">Plan Name</div>
				<div class="input-box"><input type="text" value="" name="plan_name"/></div><div class="clear"></div>
			</li>
					<li>
				<div class="label">Plan Type</div>
				<div class="input-box"><input type="text" value="" name="plan_type"/></div><div class="clear"></div>
			</li>
					<li>
				<div class="label">base_price</div>
				<div class="input-box"><input type="text" value="" name="base_price"/></div><div class="clear"></div>
			</li>
					<li>
				<div class="label">base_sqft</div>
				<div class="input-box"><input type="text" value="" name="base_sqft"/></div><div class="clear"></div>
			</li>
			</li>
					<li>
				<div class="label">Description</div>
				<div class="input-box">
				
				<textarea name="descr"></textarea>
				</div><div class="clear"></div>
			</li>
			</li>
					<li>
				<div class="label">stories</div>
				<div class="input-box"><input type="text" value="" name="stories"/></div><div class="clear"></div>
			</li>
			</li>
					<li>
				<div class="label">bath</div>
				<div class="input-box"><input type="text" value="" name="bath"/></div><div class="clear"></div>
			</li>
			</li>
					<li>
				<div class="label">bedrooms</div>
				<div class="input-box"><input type="text" value="" name="bedrooms"/></div><div class="clear"></div>
			</li>
					<li>
				<div class="label">half_bath</div>
				<div class="input-box"><input type="text" value="" name="half_bath"/></div><div class="clear"></div>
			</li>
			<li>
				<div class="label">garage</div>
				<div class="input-box"><input type="text" value="" name="garage"/></div><div class="clear"></div>
			</li>
			<li>
				<div class="label">Plan Images</div>
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
				<div class="label">brochure_url</div>
				<div class="input-box"><input type="text" value="<?php echo $plan->brochure_url;?>" name="brochure_url"/></div><div class="clear"></div>
			</li>
			<li>
				
				<div class="input-box">
				<input class="button" type="submit" value="Add Plan" name="submit"/>
				
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
			plan_number: "required",
            plan_name: "required",
            base_price: "required",
			base_sqft: "required",
			stories: "required",
			bath: "required",
			bedrooms: "required",
			half_bath: "required",
        },       
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
</script>
