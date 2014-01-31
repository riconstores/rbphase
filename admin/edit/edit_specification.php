<?php
// Stop direct call
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb;

$planspec=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_specification WHERE plan_id=%d",$_GET['planid']));
$planspec=$planspec[0];

if(isset($_POST['submit']))
{		
	 if($_POST['submit']=='Delete')
	{	
			$res=$wpdb->query( 
						$wpdb->prepare( 
							"DELETE FROM {$wpdb->prefix}bd_specification
							 WHERE ID = %d
							 ",
								$_POST['planspecid'] 
							)
					);
		 
		 
		 
		 
		  $redirect='?page=specifications';
			if($res):
			$res=$wpdb->query( 
				$wpdb->prepare( 
					"DELETE FROM {$wpdb->prefix}bd_specification_options
					 WHERE Specification_id = %d
					 ",
						$_POST['planspecid'] 
					)
			);
			
			
			?>
			
			
			
			
			<script>	
					alert('Specification  deleted successfully..!!!');
					window.location.assign("<?php echo $redirect;?>");
						</script>
	<?php endif;
	}
	else
	{
		$status=$_POST['Status'];
		 $sql=$wpdb->prepare( "UPDATE  {$wpdb->prefix}bd_specification SET Attributes=%s, SpecNumber=%s, SpecAddress=%s, SpecPrice=%d, SpecStories=%d, SpecSqft=%d,SpecBaths=%d,
	  SpecHalfBaths=%d, SpecBedrooms=%d, SpecVirtualTour=%s, SpecImages=%s,Update_From_Xml=%d,Status=%d WHERE ID=%d ",
	 array(
		json_encode($_POST['Attributes']),
		$_POST['SpecNumber'],
		json_encode($_POST['address']),
		$_POST['SpecPrice'],
		$_POST['SpecStories'],
		$_POST['SpecSqft'],
		$_POST['SpecBaths'],
		$_POST['SpecHalfBaths'],
		$_POST['SpecBedrooms'],
		json_encode($_POST['SpecVirtualTour']),
		json_encode($_POST['planimages']),
		$_POST['xmlupdate'],
		$status,
		$_POST['planspecid']
		
		));
	 $res=$wpdb->query($sql); 
	 
	 if($res)
	 {
		$message='<div class="updated below-h2" id="message"><p> Plan Specification has been updated.!!! </p></div>';
		$planspec=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_specification WHERE plan_id=%d",$_GET['planid']));
		$planspec=$planspec[0];

		
	 }
	}
	 
	
	
	
}
if(!$planspec){
?><script>	
					alert('Plan have not Specification !!!');
			window.location.assign("?page=builder_design/admin/edit/edit_plan.php&planid=<?php echo $_GET['planid'];?>");
						</script>
<?php
}


 

 
?>

<div class="wrap">
<div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
<h2>Edit Specification</h2>

<?php if(isset($message)){echo $message;}?>

	<form method="post" id="post">
		<div id="poststuff">
		<input type="hidden" name="planspecid" value="<?php echo $planspec->ID; ?>"/>
		
		<ul>
			<li>
				<div class="label">Do Not Update By Xml </div>
				<div class="input-box">
				<input style="width:10px;" <?php if($planspec->Update_From_Xml==1){echo 'checked="checked"';}?> type="checkbox" value="1" name="xmlupdate"/>
				
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">Don't Show on web </div>
				<div class="input-box">
				<input style="width:10px;" <?php if($planspec->Status==1){echo 'checked="checked"';}?> type="checkbox" value="1" name="Status"/>
				
				</div><div class="clear"></div>
			</li>
			
			
			
			<!--Attribute-->
			<li>
				<div class="label">Attributes</div>
				<div class="input-box">
				<?php $Attributes=(array)json_decode($planspec->Attributes);?>
				<table>
					<?php foreach($Attributes as $key=>$attr):?>
						<tr>
							<td><?php echo $key;?></td>
							<td><input type="text" value="<?php echo $attr;?>" name="Attributes[<?php echo $key;?>]"/></td>
						</tr>
					<?php endforeach;?>
				</table>
				
				
				
				
				
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">SpecNumber</div>
				<div class="input-box"><input type="text" value="<?php echo $planspec->SpecNumber;?>" name="SpecNumber"/></div><div class="clear"></div>
			</li>
			<li>
				<div class="label">SpecAddress</div>
				<div class="input-box">
				<table>
						<?php
						$address=(array)json_decode($planspec->SpecAddress);
						foreach($address as $key=>$add):
						$key1=$key;
						?>
						
						<?php if(is_array($add)||is_object($add)):?>
						<?php foreach($add as $key=>$a):?>
								<tr>
									<td> <?php echo $key;?></td>
									<td> <input name="address[<?php echo $key1;?>][<?php echo $key;?>]" type="text" value="<?php echo $a;?>" ></td>
								</tr>
						<?php 
							endforeach;
						else:?>
								<tr>
									<td> <?php echo $key;?></td>
									<td> <input name="address[<?php echo $key;?>]" type="text" value="<?php echo $add;?>" ></td>
								</tr>
						<?php endif;?>
						
						<?php endforeach;?>
						
					</table>
				
				
				
				</div><div class="clear"></div>
			</li>
			<li>
				<div class="label">SpecPrice</div>
				<div class="input-box"><input type="text" value="<?php echo $planspec->SpecPrice;?>" name="SpecPrice"/></div><div class="clear"></div>
			</li>
			<li>
				<div class="label">SpecStories</div>
				<div class="input-box"><input type="text" value="<?php echo $planspec->SpecStories;?>" name="SpecStories"/></div><div class="clear"></div>
			</li>
			
			<li>
				<div class="label">SpecSqft</div>
				<div class="input-box"><input type="text" value="<?php echo $planspec->SpecSqft;?>" name="SpecSqft"/></div><div class="clear"></div>
			</li>
			
			<li>
				<div class="label">SpecBaths</div>
				<div class="input-box"><input type="text" value="<?php echo $planspec->SpecBaths;?>" name="SpecBaths"/></div><div class="clear"></div>
			</li>
			<li>
				<div class="label">SpecHalfBaths</div>
				<div class="input-box"><input type="text" value="<?php echo $planspec->SpecHalfBaths;?>" name="SpecHalfBaths"/></div><div class="clear"></div>
			</li>
			<li>
				<div class="label">SpecBedrooms</div>
				<div class="input-box"><input type="text" value="<?php echo $planspec->SpecBedrooms;?>" name="SpecBedrooms"/></div><div class="clear"></div>
			</li>
			<?php $vtour=json_decode($planspec->SpecVirtualTour);
				
				if(count($vtour)>0):
				
				?>
			<li>
				<div class="label">SpecVirtualTour</div>
				<div class="input-box">
				
				<iframe width="560" height="315" src="<?php echo $vtour;?>" frameborder="0" allowfullscreen></iframe>
				</br>
				<input type="text" value="<?php echo $vtour;?>" name="SpecVirtualTour"/>
				
				
				
				
				</div><div class="clear"></div>
			</li>
			<?php endif;?>
			<?php 	$planimages=(array)json_decode($planspec->SpecImages);
			if(count($planimages)>0):
			?>	
			<li>
				<div class="label">Spec Images</div>
				<div class="input-box">
					<table>
						<?php
					
						foreach($planimages as $key=>$imageurl):
						$key1=$key;
						?>
						
						<tr>
							<td colspan="2"> <h2 style="border-bottom:1px solid #ccc;margin-top:0px;"><?php echo $key;?></h2></td>
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
			<?php endif;?>
			<li>
				
				<div class="input-box">
				<input class="button" type="submit" value="Save Changes" name="submit"/>
				
				<?php 
				$oplist=$wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}bd_specification_options HAVING Specification_id=%d",$planspec->ID));
				if($oplist):?>
					<a class="button" href="?page=builder_design/admin/edit/specification_option_list.php&specid=<?php echo $planspec->ID;?>"> View Specification Option List</a>
				<?php endif;?>
				<input class="button" onclick="if(!confirm('Are you sure to delete this specification.'))return false;" type="submit" value="Delete" name="submit"/>
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