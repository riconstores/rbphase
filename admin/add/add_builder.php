<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb;
$corporations = $wpdb->get_results("SELECT ID,c_name FROM {$wpdb->prefix}bd_corporations");


if(isset($_POST['submit']))
{
		
		$error=false;
		$errormessage='';
		if($_POST['corporation']==''){$error=true;$errormessage='select the corporation.';}
		
		
		if(!$error)
		{
				$sql=$wpdb->prepare( "INSERT INTO {$wpdb->prefix}bd_builders (corporat_id, builder_number, brand_name,reporting_name,lead_email) VALUES ( %d, %s, %s,%s,%s )",
					array( $_POST['corporation'],$_POST['builder_number'],
					$_POST['brand_name'],
					$_POST['reporting_name'],
					$_POST['lead_email'])  
				);
				$res=$wpdb->query($sql); 
				 
				 if($res)
				 {
					$message='<div class="updated below-h2" id="message"><p> Builder has been added under .!!! </p></div>';
				}
		}
		
		else{$message='<div class="updated below-h2" id="message"><p>'.$errormessage.'.!!! </p></div>';}
		
		
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
			corporation: "required",
            builder_number: "required",
            brand_name: "required",
			lead_email:{
					required: true,
					email: true
					},
           reporting_name:"required",
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
<h2>Add New Division</h2>

<?php if(isset($message)){echo $message;}?>

	<form method="post" id="myform">
		<div id="poststuff">
		
		
		<ul>
			<li>
				<div class="label">Do Not Update By Xml </div>
				<div class="input-box">
				<input style="width:10px;" <?php if($builder->Update_From_Xml==1){echo 'checked="checked"';}?> type="checkbox" value="1" name="xmlupdate"/>
				
				</div><div class="clear"></div>
			</li>
			
			<li>
				<div class="label">Select Corporation</div>
				<div class="input-box">
				<select id="corporation" name="corporation">
				<option value=''>Select Corporation</option>
				<?php foreach($corporations as $corporation):?>
					<option value="<?php echo $corporation->ID;?>"><?php echo $corporation->c_name;?></option>
				<?php endforeach;?>
			</select>
			</div>
			<div class="clear"></div>
			</li>
			
			<li>
				<div class="label">Builder Number</div>
				<div class="input-box"><input type="text" value="" id="builder_number" name="builder_number"/></div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">Brand Name</div>
				<div class="input-box"><input type="text" value="" id="brand_name" name="brand_name"/></div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">Reporting Name</div>
				<div class="input-box"><input type="text" value="" id="reporting_name" name="reporting_name"/></div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">Lead Email</div>
				<div class="input-box"><input type="text" value="" id="lead_email"  name="lead_email"/></div>
				<div class="clear"></div>
			</li>
			<li>
				
				<div class="input-box"><input class="button" type="submit" value="ADD BUILDER" name="submit"/>
				</div>
				<div class="clear"></div>
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