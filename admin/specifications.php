<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
global $wpdb;
/**Start pagination coding**/
global $wp_query;

$big = 999999999; // need an unlikely integer
if(isset($_GET['divid']))
{
$nospec = $wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_specification HAVING divison_id=%d ",$_GET['divid']));
}
else
{
	$nospec = $wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_specification");
}

$limit=30;
 $totalpage=ceil(count($nospec)/$limit);
if(isset($_GET['paged']))
{   $start=$_GET['paged']*$limit-$limit;
 $current=$_GET['paged'];
}
else{ $start=0; $current=1;}
$pagination= paginate_links( array(
	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	'format' => '?paged=%#%',
	'current' => max($current, get_query_var('paged') ),
	'total' => $totalpage
) );

if(isset($_GET['divid']))
{
	
	$specifications = $wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_specification HAVING divison_id=%d LIMIT $start, $limit",$_GET['divid']));
	
	
}
else
{
	
	$specifications = $wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_specification LIMIT $start, $limit");
}
//Apply filter

if(isset($_POST['apply'])){
	 $status=$_POST['status'];
	foreach($_POST['specids'] as $specsid){
				 $wpdb->query($wpdb->prepare("UPDATE  {$wpdb->prefix}bd_specification SET Status=%d WHERE ID=%d",array($status,$specsid)));
	}	
}
?>
<br>
<style>.pagination{float:right;}.pagination a{border:1px solid #ccc;text-decoration:none;  display: block;
    float: left;
    margin-right: 1px;
    padding: 0.2em 0.5em;font-size:10px; }
	.pagination span{float:left;}
	.pagination .current{ border: 1px solid #CCCCCC;
	background:#ccc;     
    display: block;
    float: left;
    font-size: 10px;
    margin-right: 1px;
    padding: 0.2em 0.5em;
    text-decoration: none;}
    .check-column{padding-left: 25px!important;}
	</style>

<div class="wrap">
<form action="" method="post">	
<h2>Specifications Listing </h2>
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
		<th id="cb" scope="row" class="column-cb check-column"><input id="cb-select-all-1" type="checkbox"/></th>
		<th class="manage-column column-author"  scope="col">Specs ID</th>
		<th  class="manage-column column-author"  scope="col">Specs Number</th>
		<th  class="manage-column column-author"  scope="col">Plan Name</th>
		<th  class="manage-column column-author"  scope="col">SpecIsModel</th>
		<th  class="manage-column column-author"  scope="col">Type</th>
		<th  class="manage-column column-author"  scope="col">Price</th>
		<th  class="manage-column column-author"  scope="col"> Action</th>
		</tr>
	</thead>
	<tfoot>
	</tfoot>
	<tbody id="the-list">
		<?php /*starting */ if(count($specifications)>0):?>
		<?php foreach($specifications as $specification):?>
		<tr>
			<td class="check-column" scope="row"><input class="checkboxweb" type="checkbox" name="specids[]" value="<?php echo $specification->ID;?>"/></td>
			<td class="manage-column "><?php echo $specification->ID;?></td>
			<td class="manage-column "><?php echo $specification->SpecNumber ;?></td>
			<td class="manage-column "><?php $plani=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_plan WHERE ID=%d ",$specification->plan_id));
			echo $plani[0]->plan_name;?></td>
			
			<td class="manage-column "><?php if($specification->SpecIsModel==1){echo 'Yes';}else{echo'No';};?></td>
			<td class="manage-column "><?php $type=(array)json_decode($specification->Attributes);
				
			echo $type['Type'];?></td>
			<td class="manage-column "><?php echo '$'.$specification->SpecPrice;?></td>
			<td class="manage-column ">
			<a href="?page=builder_design/admin/edit/edit_specification.php&planid=<?php echo $specification->plan_id; ?>">Edit</a>
			/<a href="?page=builder_design/admin/add/add_specification_option.php&specid=<?php echo $specification->ID; ?>">Add Specs option</a>
			</td>
		</tr>
		<?php endforeach; else :?>
			<tr><td colspan="5">There are no data available.</td></tr>
		<?php endif;?>
	</tbody>
	
</table>	
</form>
</div>	
<?php /*
<script>jQuery(document).ready(function(){
	jQuery('#cb-select-all-1').click(function(){
		if(jQuery(this).attr('checked')){jQuery('.checkboxweb').each(function(){jQuery(this).attr("checked",'checked')});}
		else{jQuery('.checkboxweb').each(function(){jQuery(this).attr("checked",false)});}
	});
});	</script>*/?>