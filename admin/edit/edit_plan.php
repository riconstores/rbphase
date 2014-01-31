<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb;

$plan=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_plan WHERE ID=%d",$_GET['planid']));
$plan=$plan[0];



if(isset($_POST['submit']))
{	
		
	if($_POST['submit']=='Delete')
	{
		$planres=$wpdb->get_results( 
				$wpdb->prepare("SELECT ID,plan_id FROM {$wpdb->prefix}bd_specification WHERE plan_id = %d",
						$_POST['planid'] 
					)
			);
		if(count($planres)>0){
			$wpdb->query( 
						$wpdb->prepare("DELETE FROM {$wpdb->prefix}bd_specification	 WHERE ID = %d",
								$planres->ID 
							)
					);
		 $wpdb->query( $wpdb->prepare("DELETE FROM {$wpdb->prefix}bd_specification_options WHERE Specification_id = %d ",
						$planres->ID  
					)
			);
		
		
		}
		$res=$wpdb->query( 
						$wpdb->prepare("DELETE FROM {$wpdb->prefix}bd_plan	 WHERE ID = %d",
								$_POST['planid'] 
							)
					);
		
			
			
			
		 $redirect='?page=list_plans';
			if($res):?>
			<script>	
					alert('Plan deleted successfully..!!!');
					window.location.assign("<?php echo $redirect;?>");
						</script>
	<?php endif;
	} 
	else
	{
	$Status=$_POST['Status'];
	$res=$wpdb->query( $wpdb->prepare( "UPDATE  {$wpdb->prefix}bd_plan SET builder_id=%d, divison_id=%s, plan_name=%s, plan_type=%s, plan_number=%s, base_price=%d,base_sqft=%d,
	  descr=%s, stories=%d, bath=%d, bedrooms=%d, half_bath=%d, garage=%s, planimages=%s, brochure_url=%s,Update_From_Xml=%d,Status=%d	WHERE ID=%d ",
	 array(
		$_POST['builder_id'],
		$_POST['divison_id'],
		$_POST['plan_name'],
		$_POST['plan_type'],
		$_POST['plan_number'],
		$_POST['base_price'],
		$_POST['base_sqft'],
		$_POST['descr'],
		$_POST['stories'],
		$_POST['bath'],
		$_POST['bedrooms'],
		$_POST['half_bath'],
		$_POST['garage'],
		json_encode($_POST['planimages']),
		$_POST['brochure_url'],
		$_POST['xmlupdate'],
		$Status,
		$plan->ID
		))); 
	 
	 if($res)
	 {
		$message='<div class="updated below-h2" id="message"><p> Division has been updated.!!! </p></div>';
		$plan=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_plan WHERE ID=%d",$_GET['planid']));
		$plan=$plan[0];
		
	 }
	}
	
}

/************/

 //$planspec=$wpdb->get_results($sql=$wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_specification WHERE ID=%d",$_GET['planid']));
 //print_r($planspec);
 
 
/**************/


?>

<div class="wrap">
<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
<h2>Edit Plan</h2>

<?php if(isset($message)){echo $message;}?>

	<form method="post" id="post">
		<div id="poststuff">
		<input type="hidden" name="planid" value="<?php echo $plan->ID;?>"/>
		
		<ul>
			
			<li>
				<div class="label">Do Not Update By Xml </div>
				<div class="input-box">
				<input style="width:10px;" <?php if($plan->Update_From_Xml==1){echo 'checked="checked"';}?> type="checkbox" value="1" name="xmlupdate"/>
				
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">Don't show on the web</div>
				<div class="input-box">
				<input style="width:10px;" <?php if($plan->Status==1){echo 'checked="checked"';}?> type="checkbox" value="1" name="Status"/>
				
				</div><div class="clear"></div>
			</li>
			
			<!--Attribute-->
			<li>
				<div class="label">Plan Number</div>
				<div class="input-box"><input type="text" value="<?php echo $plan->plan_number;?>" name="plan_number"/></div><div class="clear"></div>
			</li>
					<li>
				<div class="label">Plan Name</div>
				<div class="input-box"><input type="text" value="<?php echo $plan->plan_name;?>" name="plan_name"/></div><div class="clear"></div>
			</li>
					<li>
				<div class="label">Plan Type</div>
				<div class="input-box"><input type="text" value="<?php echo $plan->plan_type;?>" name="plan_type"/></div><div class="clear"></div>
			</li>
					<li>
				<div class="label">base_price</div>
				<div class="input-box"><input type="text" value="<?php echo $plan->base_price;?>" name="base_price"/></div><div class="clear"></div>
			</li>
					<li>
				<div class="label">base_sqft</div>
				<div class="input-box"><input type="text" value="<?php echo $plan->base_sqft;?>" name="base_sqft"/></div><div class="clear"></div>
			</li>
			</li>
					<li>
				<div class="label">Description</div>
				<div class="input-box">
				
				<textarea name="descr"><?php echo $plan->descr;?></textarea>
				</div><div class="clear"></div>
			</li>
			</li>
					<li>
				<div class="label">stories</div>
				<div class="input-box"><input type="text" value="<?php echo $plan->stories;?>" name="stories"/></div><div class="clear"></div>
			</li>
			</li>
					<li>
				<div class="label">bath</div>
				<div class="input-box"><input type="text" value="<?php echo $plan->bath;?>" name="bath"/></div><div class="clear"></div>
			</li>
			</li>
					<li>
				<div class="label">bedrooms</div>
				<div class="input-box"><input type="text" value="<?php echo $plan->bedrooms;?>" name="bedrooms"/></div><div class="clear"></div>
			</li>
					<li>
				<div class="label">half_bath</div>
				<div class="input-box"><input type="text" value="<?php echo $plan->half_bath;?>" name="half_bath"/></div><div class="clear"></div>
			</li>
			<li>
				<div class="label">garage</div>
				<div class="input-box"><input type="text" value="<?php echo $plan->garage;?>" name="garage"/></div><div class="clear"></div>
			</li>
			<li>
				<div class="label">Plan Images</div>
				<div class="input-box">
					<table>
						<?php
						$planimages=(array)json_decode($plan->planimages);
						foreach($planimages as $key=>$imageurl):
						$key1=$key;
						?>
						
						<tr>
							<td colspan="2"> <h2 style="border-bottom:1px solid #ccc;"><?php echo $key;?></h2></td>
						</tr>
						<?php if(is_array($imageurl)):?>
						<?php foreach($imageurl as $key=>$imgurl):?>
								<tr>
									<td> <img width="100" src="<?php echo $imgurl;?>"/></td>
									<td> <input name="planimages[<?php echo $key1;?>][<?php echo $key;?>]" type="text" value="<?php echo $imgurl;?>" ></td>
								</tr>
						<?php 
							endforeach;
						else:?>
							<tr>
									<td> <img width="100" src="<?php echo $imageurl;?>"/></td>
									<td> <input name="planimages[<?php echo $key;?>]" type="text" value="<?php echo $imageurl;?>" ></td>
								</tr>
						<?php endif;?>
						
						<?php endforeach;?>
						
					</table>
				
				</div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">brochure_url</div>
				<div class="input-box"><input type="text" value="<?php echo $plan->brochure_url;?>" name="brochure_url"/></div><div class="clear"></div>
			</li>
			<li>
				
				<div class="input-box">
				<input class="button" type="submit" value="Save Changes" name="submit"/>
				<a class="button" href="?page=builder_design/admin/edit/edit_specification.php&planid=<?php echo $plan->ID; ?>"> View Specification</a>
				<input class="button" onclick="if(!confirm('Are you sure to delete this plan.All Information will be deleted related to this plan..'))return false;" type="submit" value="Delete" name="submit"/>
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