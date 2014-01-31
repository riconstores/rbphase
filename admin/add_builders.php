<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb;

$builders = $wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_builders");

$corporations = $wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_corporations");

?>

<div class="wrap">
<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
<h2>Add New Builder</h2>
	<form method="post" id="post">
		<div id="poststuff">
		
		
		<ul>
			<li>
				<div class="label">Select Corporation</div>
				<div class="input-box"><select>
				<option>Select Corporation</option>
				<?php foreach($corporations as $corporation)
				{
					echo '<option value="'.$corporation->ID.'">'.$corporation->c_name.'</option>';	
				}				
				?>
			</select></div>
			</li>
			<li>
				<div class="label">Builder Number</div>
				<div class="input-box"><input type="text" name="builder_number"/></div>
			</li>
			<li>
				<div class="label">Brand Name</div>
				<div class="input-box"><input type="text" name="brand_name"/></div>
			</li>
			<li>
				<div class="label">Reporting Name</div>
				<div class="input-box"><input type="text" name="reporting_name"/></div>
			</li>
			<li>
				<div class="label">Lead Email</div>
				<div class="input-box"><input type="text" name="lead_email"/></div>
			</li>
			<li>
				
				<div class="input-box"><input type="submit" value="SAVE" name="submit"/></div>
			</li>
		<ul>
		
		
		
		
		
		</div>
	</form>
</div>	
<style>
.label{float:left;width:20%;}
</style>	