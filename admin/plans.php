<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
include_once(dirname (__FILE__).'/Include/pagination.php');
global $wpdb;
/**Start pagination coding**/
$p = new Pager;
$limit = 100;
$start = $p->findStart($limit); 

if(isset($_GET['divid']))
{
	
	$plans = $wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_plan HAVING divison_id=%d LIMIT $start, $limit",$_GET['divid']));
	$count = count($plans);
	
}
else
{
	$plans = $wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_plan ");
	$count = count($plans);
	$plans = $wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_plan LIMIT $start, $limit");
}

$pages = $p->findPages($count, $limit);
/* Now get the page list and echo it */
$pagelist = $p->pageList($_GET['p'], $pages);
/***********************************************/
if(isset($_POST['apply'])&&isset($_POST['status'])){
	$status=$_POST['status'];
	foreach ($_POST['planids'] as $planid) {
		 $wpdb->query($wpdb->prepare("UPDATE  {$wpdb->prefix}bd_plan SET Status=%d WHERE ID=%d",array($status,$planid)));
	}
	if($status==1){$message='Selected Plans has been disabled.';}
	else{$message='Selected Plans has been enabled.';}
	
}
?>
<br>
<style>.pagination{float:right;}.pagination a{border:1px solid #ccc;text-decoration:none;  display: block;
    float: left;
    margin-right: 1px;
    padding: 0.2em 0.5em;font-size:10px; }
    .check-column{padding-left: 25px!important;}</style>

<div class="wrap">
<h2>Plans Listing </h2>
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
		<th id="cb" scope="row" class="column-cb check-column"><label for="cb-select-all-1" class="screen-reader-text">Select All</label>
			<input id="cb-select-all-1" type="checkbox"/></th>
		<th  class="manage-column"  scope="col">Plan ID</th>
		<th  class="manage-column"   scope="col">Plan Name</th>		
		<th  class="manage-column"  scope="col">Plan Number</th>
		<th  class="manage-column"  scope="col">Base Price</th>
		<th  class="manage-column"  scope="col">Base Sqft</th>
		<th  class="manage-column"   scope="col"> Description</th>
		</tr>
	</thead>
	<tfoot>
	</tfoot>
	<tbody id="the-list">
		<?php /*starting */ if(count($plans)>0):?>
		<?php foreach($plans as $plan):?>
		<tr>
			<td class="check-column" scope="row"><input class="checkboxweb" type="checkbox" name="planids[]" value="<?php echo  $plan->ID;?>"/></td>
			<td><?php echo $plan->ID;?></td>
			<td><?php echo $plan->plan_name;?></td>			
			<td><?php echo $plan->plan_number;?></td>
			<td><?php echo $plan->base_price;?></td>
			<td><?php echo $plan->base_sqft;?></td>
			<td>
			<a href="?page=builder_design/admin/edit/edit_plan.php&planid=<?php echo $plan->ID; ?>">Edit</a>
			/<a href="?page=builder_design/admin/add/add_specification.php&planid=<?php echo $plan->ID; ?>">Add Specs</a>
			</td>
		</tr>
		<?php endforeach; else :?>
			<tr><td colspan="5">There are no data available.</td></tr>
		<?php endif;?>
	</tbody>
	
</table>
</form>	
</div>	
	