<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb;
if(isset($_GET['builderid']))
{
	
	$divisons = $wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_divison HAVING builder_number=%d",$_GET['builderid']));
	
	if(!$divisons)
	{?>
		<script>	
					alert('Builder have not Divisions !!!');
					window.location.assign("?page=builder_design/admin/edit/edit_builder.php&builderid=<?php echo $_GET['builderid'];?>");
						</script>
<?php	}
	
}
else
{
	$divisons = $wpdb->get_results("SELECT *FROM wp_bd_divison");
	
}
///

if(isset($_POST['apply'])&& count($_POST['subids'])>0){
	echo $status=$_POST['status'];
	foreach ($_POST['subids'] as $subid) {
		echo $subid;
		$wpdb->query($wpdb->prepare("UPDATE  {$wpdb->prefix}bd_divison SET status=%d WHERE ID=%d",array($status,$subid)));
	}
	if($status==1){$message='Selected Sub Division has been disabled.';}
	else{$message='Selected Sub Division has been enabled.';}
}

?>

<div class="wrap">
<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
<h2>Division Listing<a class="add-new-h2" href="?page=builder_design/admin/add/add_division.php">Add New</a></h2>

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
		<tr>
		<th id="cb" scope="row" class="manage-column check-column column-author " scope="col"><input id="cb-select-all-1" type="checkbox"/></th>
		<th class="manage-column column-author" scope="col">divison Number</th>
		<th  class="manage-column column-author"  scope="col">divison Name</th>
		<th class="manage-column column-author"  scope="col">Description</th>
		<th  class="manage-column column-author"  scope="col">Address</th>
		<th  class="manage-column column-author"  scope="col">Action</th>
		</tr>
	</thead>
	
	<tbody id="the-list">
		<?php /*starting */ //if(count($divisons)>0):?>
		<?php foreach($divisons as $divison):?>
		<tr>
			<td class="manage-column check-column" scope="col"><input class="checkboxweb" type="checkbox" name="subids[]" value="<?php echo  $divison->ID;?>"/></td>
			<td><?php echo $divison->number;?></td>
			<td><?php echo $divison->name;?></td>
			<td><?php echo $divison->descr;?></td>
			<td>
			
			<?php $address=json_decode($divison->sale_office_address);
			$address=(array)$address->Address;
			foreach($address as $key=>$add)
			{
				
				if(is_array($add)||is_object($add))
				{
					foreach($add as $key=>$a)
					{
						echo $key.'  :'.$a.'<br>';
					}
				}
				else
				{
					echo $key.'  :'.$add.'<br>';
				}
				
			}
			
			?>
			</td>
			<td><a href="?page=builder_design/admin/edit/edit_division.php&divid=<?php echo $divison->ID; ?>">Edit</a>
			/<a href="?page=builder_design/admin/add/add_plan.php&divid=<?php echo $divison->ID; ?>">Add Plan</a>
			</td>
		</tr>
		<?php endforeach;// else :?>
			<!--<tr><td colspan="5">There are no data available.</td></tr>-->
		<?php// endif;?>
	</tbody>
	
</table>
</form>
</div>	
<style>
.label{float:left;width:20%;}
.check-column{padding-left: 25px!important;}
</style>	