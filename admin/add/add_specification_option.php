<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb;
if(isset($_POST['submit']))
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
			$_POST['planspecid'],
			$_POST['OptionCode'],
			$_POST['OptionDesc'],
			$_POST['OptionGroupName'],
			$_POST['Qty'],
			$_POST['Price'],
			$_POST['BuilderApproved'],
			$_POST['CustomerApproved'],
			$_POST['CustomerDesc']
			));
	 $res=$wpdb->query($sql); 
	 
	 if($res)
	 {
		$message='<div class="updated below-h2" id="message"><p> Plan Specification Option has been added..</p></div>';
	

		
	 }
	
	
}
$sql=$wpdb->prepare("SELECT {$wpdb->prefix}bd_plan.plan_name, {$wpdb->prefix}bd_specification. SpecNumber  
FROM {$wpdb->prefix}bd_plan 
INNER JOIN {$wpdb->prefix}bd_specification
ON {$wpdb->prefix}bd_plan.ID = {$wpdb->prefix}bd_specification.plan_id WHERE {$wpdb->prefix}bd_specification.ID =%d",$_GET['specid']);
$planspec=$wpdb->get_results($sql);


?>

<div class="wrap">
<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
<h2>Add Specification Option</h2>
<div style="background:#ccc;color:#000;padding:10px;"><b>Plan Name:  <?php echo $planspec[0]->plan_name;?>   Specification Number:-  <?php echo $planspec[0]->SpecNumber;?> </b></div>

<?php if(isset($message)){echo $message;}?>

	<form method="post" id="myform">
		<div id="poststuff">
		<input type="hidden" name="planspecid" value="<?php echo $_GET['specid']; ?>"/>
		
		<ul>
				
			<li>
				<div class="label">OptionCode</div>
				<div class="input-box">
				<input type="text" name="OptionCode" value=""/>
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">OptionDesc</div>
				<div class="input-box">
				<input type="text" name="OptionDesc" value=""/>
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">OptionGroupName</div>
				<div class="input-box">
				<input type="text" name="OptionGroupName" value=""/>
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">Qty</div>
				<div class="input-box">
				<input type="text" name="Qty" value=""/>
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">Price</div>
				<div class="input-box">
				<input type="text" name="Price" value=""/>
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">BuilderApproved</div>
				<div class="input-box">
					<select name="BuilderApproved">
						<option selected="selected" value="1">Yes</option>
						<option value="0">No</option>
					</select>
				
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">CustomerApproved</div>
				<div class="input-box">
				<select name="CustomerApproved">
						<option selected="selected" value="1">Yes</option>
						<option value="0" >No</option>
					</select>
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">CustomerDesc</div>
				<div class="input-box">
				<textarea name="CustomerDesc"></textarea>
				</div><div class="clear"></div>
			</li>
			
			
			
			<li>
				
				<div class="input-box">
				<input class="button" type="submit" value="Add Option" name="submit"/>
				
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
			OptionCode: "required",
            OptionGroupName: "required",
            Qty: "required",
			Price:"required",
			OptionDesc:"required",
           
		   
        },       
        submitHandler: function(form) {
            form.submit();
        }
    });

  });
  
</script>	