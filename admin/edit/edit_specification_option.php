<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb;


$sql=$wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_specification_options WHERE ID=%d",$_GET['specoptionid']);
 $planspec=$wpdb->get_results($sql);
$planspecoption=$planspec[0];

 $currenturl=$_SERVER["REQUEST_URI"];
$rurl=explode('?',$currenturl);

//print_r($planspecoption);
if(isset($_POST['submit']))
{		
	if($_POST['submit']=='DELETE')
	{
		$res=$wpdb->query( 
				$wpdb->prepare( 
					"DELETE FROM {$wpdb->prefix}bd_specification_options
					 WHERE ID = %d
					 ",
						$_GET['specoptionid'] 
					)
			);
		 $redirect='?page=builder_design/admin/edit/specification_option_list.php&specid='.$_POST['specificationid'];
			if($res):?>
			<script>	
					alert('Specification option deleted successfully..!!!');
					window.location.assign("<?php echo $redirect;?>");
						</script>
	<?php endif;
	} 
	else
	{
		$sql=$wpdb->prepare( "UPDATE  {$wpdb->prefix}bd_specification_options SET OptionCode=%s, OptionDesc=%s, OptionGroupName=%s, Qty=%d, Price=%d, BuilderApproved=%d,CustomerApproved=%d,
	  CustomerDesc=%d,Update_From_Xml=%d WHERE ID=%d ",
	 array(
		$_POST['OptionCode'],
		$_POST['OptionDesc'],
		$_POST['OptionGroupName'],
		$_POST['Qty'],
		$_POST['Price'],
		$_POST['BuilderApproved'],
		$_POST['CustomerApproved'],
		$_POST['CustomerDesc'],
		$_POST['xmlupdate'],
		$_POST['planspecid']
		
		));
	 $res=$wpdb->query($sql); 
	 
	 if($res)
	 {
		$message='<div class="updated below-h2" id="message"><p> Plan Specification has been updated.!!! </p></div>';
		
		$sql=$wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_specification_options WHERE ID=%d",$_GET['specoptionid']);
		 $planspec=$wpdb->get_results($sql);
		$planspecoption=$planspec[0];

		
	 }
	}
	 
	 
	
	
}


?>

<div class="wrap">
<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
<h2>Edit Specification Option</h2>

<?php if(isset($message)){echo $message;}?>

	<form method="post" id="post">
		<div id="poststuff">
		<input type="hidden" name="planspecid" value="<?php echo $planspecoption->ID; ?>"/>
		<input type="hidden" name="specificationid" value="<?php echo $planspecoption->Specification_id; ?>"/>
		<ul>
			<li>
				<div class="label">Do Not Update By Xml </div>
				<div class="input-box">
				<input style="width:10px;" <?php if($planspecoption->Update_From_Xml==1){echo 'checked="checked"';}?> type="checkbox" value="1" name="xmlupdate"/>
				
				</div><div class="clear"></div>
			</li>
		
			<li>
				<div class="label">OptionCode</div>
				<div class="input-box">
				<input type="text" name="OptionCode" value="<?php echo $planspecoption->OptionCode; ?>"/>
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">OptionDesc</div>
				<div class="input-box">
				<input type="text" name="OptionDesc" value="<?php echo $planspecoption->OptionDesc; ?>"/>
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">OptionGroupName</div>
				<div class="input-box">
				<input type="text" name="OptionGroupName" value="<?php echo $planspecoption->OptionGroupName; ?>"/>
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">Qty</div>
				<div class="input-box">
				<input type="text" name="Qty" value="<?php echo $planspecoption->Qty; ?>"/>
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">Price</div>
				<div class="input-box">
				<input type="text" name="Price" value="<?php echo $planspecoption->Price; ?>"/>
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">BuilderApproved</div>
				<div class="input-box">
					<select name="BuilderApproved">
						<option value="1">Yes</option>
						<option value="0" <?php if(!$planspecoption->BuilderApproved){echo 'selected="selected"';}?>>No</option>
					</select>
				
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">CustomerApproved</div>
				<div class="input-box">
				<select name="CustomerApproved">
						<option value="1">Yes</option>
						<option value="0" <?php if(!$planspecoption->CustomerApproved){echo 'selected="selected"';}?>>No</option>
					</select>
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">CustomerDesc</div>
				<div class="input-box">
				<textarea name="CustomerDesc"><?php echo $planspecoption->CustomerDesc; ?></textarea>
				</div><div class="clear"></div>
			</li>
			
			
			
			<li>
				
				<div class="input-box">
				<input class="button" type="submit" value="SAVE CHANGES" name="submit"/>
				<input class="button" onclick="if(!confirm('Are you sure.....'))return false;" type="submit" value="DELETE" name="submit"/>
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