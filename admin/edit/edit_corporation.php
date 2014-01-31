<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb;

$corporationid=$_GET['corporationid'];
$corporation=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_corporations WHERE ID=%d",$corporationid));
$corporation=$corporation[0];



if(isset($_POST['submit']))
{	

	 $sql=$wpdb->prepare( "UPDATE  {$wpdb->prefix}bd_corporations SET c_number=%s, c_name=%s, c_state=%s, c_email=%s, status=%d,Update_From_Xml=%d  WHERE ID=%d ",
	 array(
		$_POST['c_number'],
		$_POST['c_name'],
		$_POST['c_state'],
		$_POST['c_email'],
		$_POST['status'],
		$_POST['xmlupdate'],
		$_POST['corpid']
		));
	$res=$wpdb->query( $sql); 
	 
	 if($res)
	 {
		$message='<div class="updated below-h2" id="message"><p> Corporation has been updated.!!! </p></div>';
		$corporation=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_corporations WHERE ID=%d",$_POST['corpid']));
		$corporation=$corporation[0];
		
	 }
	 else
	 {
		$message='<div class="updated below-h2" id="message"><p> Corporation has not been updated.!!! </p></div>';
	 }
	
	
}



?>

<div class="wrap">
<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
<h2>Edit Corporation</h2>

<?php if(isset($message)){echo $message;}?>

	<form method="post" id="post">
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
				
				<input type="text" value="<?php echo $corporation->c_number;?>" name="c_number"/>
				</div><div class="clear"></div>
			</li>
			
			<!--Attribute-->
			<li>
				<div class="label">Corporation Name</div>
				<div class="input-box"><input type="text" value="<?php echo $corporation->c_name;?>" name="c_name"/></div><div class="clear"></div>
			</li>
					<li>
				<div class="label">Corporation State</div>
				<div class="input-box"><input type="text" value="<?php echo $corporation->c_state;?>" name="c_state"/></div><div class="clear"></div>
			</li>
					<li>
				<div class="label">Lead Email</div>
				<div class="input-box"><input type="text" value="<?php echo $corporation->c_email;?>" name="c_email"/></div><div class="clear"></div>
			</li>
			<li>
				<div class="label">Status</div>
				<div class="input-box">
					<select name="status">
						<option value="0">Deactivate</option>
						<option value="1">Activate</option>
					</select>
				</div>
				<div class="clear"></div>
			</li>	
			
			
		
			
			
			<li>
				
				<div class="input-box">
				<input class="button" type="submit" value="Save Changes" name="submit"/>
				<a class="button" href="?page=edit_builders&corporationid=<?php echo $corporation->ID; ?>">View Builders</a></div>
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