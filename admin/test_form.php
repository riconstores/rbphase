<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb;
$builders = $wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_builders");
//
if(isset($_POST['apply'])&& count($_POST['buildids'])>0){
	echo $status=$_POST['status'];
	foreach ($_POST['buildids'] as $subid) {
		echo $subid;
		$wpdb->query($wpdb->prepare("UPDATE  {$wpdb->prefix}bd_builders SET Status=%d WHERE ID=%d",array($status,$subid)));
	}
	if($status==1){$message='Selected Sub Division has been disabled.';}
	else{$message='Selected Sub Division has been enabled.';}
}
?>
<br>


<div class="wrap">
<h2>Divisions <a class="add-new-h2" href="?page=builder_design/admin/add/add_builder.php">Add New</a></h2>
<?php if(isset($message)):?><div class="updated " id="setting-error-settings_updated"> <p><?php echo $message;?></p></div><?php endif;?>
<form action="" method="post">	
<div class="tablenav top">
	<div class="alignleft actions bulkactions">
		<select name="status">
			<option>Select Action</option>
			<option value="0">Show On web</option>
			<option value="1">Don't Show On web</option>
		</select>
		<input type="submit" class="button" name="apply" value="apply">
	</div>	
	<div class="tablenav-pages one-page"><span class="pagination"><?php echo $pagination; ?></span></div>
</div>
<table cellspacing="0" class="wp-list-table widefat fixed pages">
	<thead>
		<th id="cb" scope="row" class="manage-column check-column column-author " scope="col"><input id="cb-select-all-1" type="checkbox"/></th>
		<th style="" class="manage-column column-author" id="author" scope="col">Division Number</th>
		<th style="" class="manage-column column-author" id="author" scope="col">Brand Name</th>
		<th style="" class="manage-column column-author" id="author" scope="col">Reporting Name</th>
		<th style="" class="manage-column column-author" id="author" scope="col">Lead Email</th>
		<th style="" class="manage-column column-author" id="author" scope="col">Action</th>
		</tr>
	</thead>
	<tfoot>
	</tfoot>
	<tbody id="the-list">
		<?php /*starting */ if(count($builders)>0):?>
		<?php foreach($builders as $builder):?>
		<tr>
			<td class="manage-column check-column" scope="col"><input class="checkboxweb" type="checkbox" name="buildids[]" value="<?php echo  $builder->ID;?>"/></td>
			<td><?php echo $builder->builder_number;?></td>
			<td><?php echo $builder->brand_name;?></td>
			<td><?php echo $builder->reporting_name;?></td>
			<td><?php echo $builder->lead_email;?></td>
			<td><a href="?page=builder_design/admin/edit/edit_builder.php&builderid=<?php echo $builder->ID; ?>">Edit</a></td>
		</tr>
		<?php endforeach; else :?>
			<tr><td colspan="5">There are no data available.</td></tr>
		<?php endif;?>
	</tbody>
	
</table>
</form>	
</div>	
<style>

.check-column{padding-left: 25px!important;}
</style>		