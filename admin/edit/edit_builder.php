<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb;

//$builders = $wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_builders");

$corporations = $wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_corporations");
 $builderid=$_GET['builderid'];
$builder=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_builders WHERE ID=%d",$builderid));
$builder=$builder[0];



if(isset($_POST['submit']))
{
	if($_POST['submit']=='Delete')
	{	
		/*get Division*/
		
		$divis=$wpdb->get_results( 
				$wpdb->prepare("SELECT ID,builder_number FROM {$wpdb->prefix}bd_divison WHERE builder_number= %d",
						$_POST['builderid'] 
					)
			);
			
		foreach($divis as $divid)
		{
			/*getplans*/
						
			$planres=$wpdb->get_results( 
					$wpdb->prepare("SELECT ID,divison_id FROM {$wpdb->prefix}bd_plan WHERE divison_id = %d",
							$divid->ID 
						)
				);
			/*Delete plan, specificationa,and speoption*/
			foreach($planres as $pID)
			{
							
				$planres=$wpdb->get_results( 
					$wpdb->prepare("SELECT ID,plan_id FROM {$wpdb->prefix}bd_specification WHERE plan_id = %d",
							$pID->ID 
						)
				);
				
				foreach($planres as $spid){
					$wpdb->query( 
								$wpdb->prepare("DELETE FROM {$wpdb->prefix}bd_specification	 WHERE ID = %d",
										$spid->ID 
									)
							);
				 $wpdb->query( $wpdb->prepare("DELETE FROM {$wpdb->prefix}bd_specification_options WHERE Specification_id = %d ",
								$spid->ID 
							)
					);

				}
								
				
			}
			$wpdb->query( 
							$wpdb->prepare("DELETE FROM {$wpdb->prefix}bd_plan WHERE divison_id = %d",
									$divid->ID  
								)
						);
			
			/*Delete Division*/
			$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}bd_divison WHERE ID = %d",
									$divid->ID  
								)
						);	
		}
		
		$res=$wpdb->query($wpdb->prepare("DELETE FROM {$wpdb->prefix}bd_builders WHERE ID = %d",
									$_POST['builderid']  
								)
						);	
			
			
		 $redirect='?page=edit_builders';
			if($res):?>
			<script>	
					alert('Builder and related information  deleted successfully..!!!');
					window.location.assign("<?php echo $redirect;?>");
						</script>
	<?php endif;
	} 
	else
	{
		$Status=$_POST['Status'];
	 $res=$wpdb->query( $wpdb->prepare( "UPDATE  {$wpdb->prefix}bd_builders SET builder_number=%s ,brand_name=%s,reporting_name=%s,lead_email=%s,Update_From_Xml=%d,Status=%d WHERE ID=%d ",
	 array($_POST['builder_number'], $_POST['brand_name'],$_POST['reporting_name'],$_POST['lead_email'],$_POST['xmlupdate'],$Status, $builderid)) ); 
	 
	 if($res)
	 {
		$message='<div class="updated below-h2" id="message"><p> Builder has been updated.!!! </p></div>';
		$builder=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_builders WHERE ID=%d",$builderid));
$builder=$builder[0];
	 }
	 }

}
?>

<div class="wrap">
<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
<h2>Edit Division</h2>

<?php if(isset($message)){echo $message;}?>

	<form method="post" id="post">
		<div id="poststuff">
		<input type="hidden" name="builderid" value="<?php echo $builder->ID;?>"/>
		
		<ul>
			<li>
				<div class="label">Do Not Update By Xml </div>
				<div class="input-box">
				<input style="width:10px;" <?php if($builder->Update_From_Xml==1){echo 'checked="checked"';}?> type="checkbox" value="1" name="xmlupdate"/>
				
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">Don't Show on web</div>
				<div class="input-box">
				<input style="width:10px;" <?php if($builder->Status==1){echo 'checked="checked"';}?> type="checkbox" value="1" name="Status"/>
				
				</div><div class="clear"></div>
			</li>
			
			<?php /*<li>
				<div class="label">Select Corporation</div>
				<div class="input-box"><select>
				<option>Select Corporation</option>
				<?php foreach($corporations as $corporation)
				{
					$HTML='<option';
					if($corporation->ID==$builder->corporat_id)
					{
						$HTML.=' selected="selected"';
					
					}
						echo $HTML.=' value="'.$corporation->ID.'">'.$corporation->c_name.'</option>';	

					
				}				
				?>
			</select></div>
			<div class="clear"></div>
			</li>*/?>
			
			<li>
				<div class="label">Builder Number</div>
				<div class="input-box"><input type="text" value="<?php echo $builder->builder_number;?>" name="builder_number"/></div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">Brand Name</div>
				<div class="input-box"><input type="text" value="<?php echo $builder->brand_name;?>" name="brand_name"/></div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">Reporting Name</div>
				<div class="input-box"><input type="text" value="<?php echo $builder->reporting_name;?>"name="reporting_name"/></div>
				<div class="clear"></div>
			</li>
			<li>
				<div class="label">Lead Email</div>
				<div class="input-box"><input type="text" value="<?php echo $builder->lead_email;?>" name="lead_email"/></div>
				<div class="clear"></div>
			</li>
			<li>
				
				<div class="input-box"><input class="button" type="submit" value="Save Changes" name="submit"/>
				<a class="button" href="?page=div_listing&builderid=<?php echo $builder->ID; ?>">View Divisions</a>
				<input class="button" onclick="if(!confirm('Are you sure to delete this builder.All Information will be deleted related to this builder..'))return false;" type="submit" value="Delete" name="submit"/>
				</div>
				<div class="clear"></div>
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