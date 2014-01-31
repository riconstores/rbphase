<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb;
if(isset($_GET['delete_coid']))
{
$url=$_SERVER['REQUEST_URI'];
$url=explode("&delete_coid",$url);

if($res){$message="Deleted successfully.";}
}

$Corporations = $wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_corporations");
?>
<br>
<div class="wrap">
<h2>Corporation Listing<a class="add-new-h2" href="?page=builder_design/admin/add/add_corporation.php">Add New</a></h2>
<br><br>
<table cellspacing="0" class="wp-list-table widefat fixed pages">
	<thead>
		
		<th style="" class="manage-column column-author" id="author" scope="col">Corporation Number</th>
		<th style="" class="manage-column column-author" id="author" scope="col">Corporation Name</th>
		<th style="" class="manage-column column-author" id="author" scope="col">Corporation State</th>
		<th style="" class="manage-column column-author" id="author" scope="col">Lead Email</th>
		<th style="" class="manage-column column-author" id="author" scope="col">Action</th>
		</tr>
	</thead>
	<tfoot>
	</tfoot>
	<tbody id="the-list">
		<?php /*starting */ if(count($Corporations)>0): ?>
		<?php foreach($Corporations as $Corporation):?>
		<tr>
			
			<td><?php echo $Corporation->c_name;?></td>
			<td><?php echo $Corporation->c_number;?></td>
			<td><?php echo $Corporation->c_state;?></td>
			<td><?php echo $Corporation->c_email;?></td>
			<td><a href="?page=builder_design/admin/edit/edit_corporation.php&corporationid=<?php echo $Corporation->ID; ?>">Edit</a>
			
			</td>
		</tr>
		<?php endforeach; else :?>
			<tr><td colspan="5">There are no data available.</td></tr>
		<?php endif;?>
	</tbody>
	
</table>	
</div>	
	