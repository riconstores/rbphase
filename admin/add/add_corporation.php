<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb;
if(isset($_POST['submit']))
{	
	$error=false;
	$emessage='';
	if(!$_POST['c_number']){$error=true;$emessage='Input the Corporation Number';}
	if(!$_POST['c_name']){$error=true;$emessage='Input the Corporation Name';}
	if(!$_POST['c_email']){$error=true;$emessage='Input the Corporation email';}
	
	if(!$error)
	{
	$sql=$wpdb->prepare( "INSERT INTO {$wpdb->prefix}bd_corporations (c_name, c_number, c_state, c_email,status)
	VALUES ( %s, %s, %s,%s,%d )",
	array( $_POST['c_name'],$_POST['c_number'],$_POST['c_state'],$_POST['c_email'],$_POST['status'])  
				);
		$res=$wpdb->query($sql);
	 
	 if($res)
	 {
		$message='<div class="updated below-h2" id="message"><p> Corporation has been updated.!!! </p></div>';
			
	 }
	}
	else
	{
		$message='<div class="updated below-h2" id="message"><p>'.$emessage .'!!! </p></div>';
	}
	
	
}



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
            c_number: "required",
            c_name: "required",
			c_email:{
					required: true,
					email: true
					},
           c_state:"required",
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
   input.error {border:1px solid #FB3A3A!important;}
  </style>
<div class="wrap">
<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
<h2>Add Corporation</h2>

<?php if(isset($message)){echo $message;}?>

	<form method="post" id="myform">
		<div id="poststuff">
		
		
		<ul>
			<input type="hidden" name="corpid" value="<?php echo $corporation->ID;?>"/>
			<li>
				<div class="label">Do Not Update By Xml </div>
				<div class="input-box">
				<input style="width:10px;" <?php if($corporation->Update_From_Xml==1){echo 'checked="checked"';}?> type="checkbox" value="1" name="xmlupdate"/>
				
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">Corporation Number</div>
				<div class="input-box">
				
				<input type="text" value="" id="c_number" name="c_number"/>
				</div><div class="clear"></div>
			</li>
			
			<!--Attribute-->
			<li>
				<div class="label">Corporation Name</div>
				<div class="input-box required"><input type="text" value="" id="c_name" name="c_name"/></div><div class="clear"></div>
			</li>
					<li>
				<div class="label">Corporation State</div>
				<div class="input-box required"><input type="text" value="" id="c_state" name="c_state"/></div><div class="clear"></div>
			</li>
					<li>
				<div class="label">Lead Email</div>
				<div class="input-box"><input type="text" value="" id="c_email" name="c_email"/></div><div class="clear"></div>
			</li>
			<li>
				<div class="label">Status</div>
				<div class="input-box">
					<select name="status">
						<option value="0">Deactivate</option>
						<option selected="selected" value="1">Activate</option>
					</select>
				</div>
				<div class="clear"></div>
			</li>	
			
			
		
			
			
			<li>
				
				<div class="input-box">
				<input class="button" id="sform" type="submit" name="submit" value="ADD Corporation" />
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