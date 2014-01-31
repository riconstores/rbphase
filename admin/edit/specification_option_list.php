<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb;

$sql=$wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_specification_options HAVING Specification_id=%d",$_GET['specid']);
 $planspec=$wpdb->get_results($sql);

 
 if(!$planspec){echo 'Sorry..!!';}
 
?>
<br>


<div class="wrap">
<h2>Plans Spec Options</h2>



<table cellspacing="0" class="wp-list-table widefat fixed pages">
	<thead>
		<th style="" class="manage-column column-author" id="author" scope="col">OptionCode</th>
		
		<th style="" class="manage-column column-author" id="author" scope="col">OptionDesc</th>
		<th style="" class="manage-column column-author" id="author" scope="col">OptionGroupName</th>
		<th style="" class="manage-column column-author" id="author" scope="col">Qty</th>
		<th style="" class="manage-column column-author" id="author" scope="col"> Price</th>
		<th style="" class="manage-column column-author" id="author" scope="col"> Actioin</th>
		
		</tr>
	<thead>
	<tfoot>
	</tfoot>
	<tbody id="the-list">
		<?php foreach($planspec as $spec):?>
			<tr>
				<td><?php echo $spec->OptionCode;?></td>
				<td><?php echo $spec->OptionDesc;?></td>
				<td><?php echo $spec->OptionGroupName;?></td>
				<td><?php echo $spec->Qty;?></td>
				<td><?php echo $spec->Price;?></td>
				<td><a href="?page=builder_design/admin/edit/edit_specification_option.php&specoptionid=<?php echo $spec->ID;?>">Edit</a></td>
				
			</tr>
		<?php endforeach;?>
	<tbody>
	
</table>	
</div>	
	