<?php
global $wpdb;
$plan=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_plan WHERE ID=%d",$_GET['plan_detail']));
$plan=$plan[0];
//print_r($divisions);
$rurl=$_SERVER["REQUEST_URI"];
$rurl=explode('&plan_detail',$rurl);
$rurl='http://'.$_SERVER["HTTP_HOST"].$rurl[0];
?>
<script src="<?php echo plugins_url('/js/jquery-1.9.0.min.js', __FILE__);?>"></script>
<script src="<?php echo plugins_url('/js/jquery.nivo.slider.js', __FILE__);?>"></script>
<link rel="stylesheet" href="<?php echo plugins_url('/css/nivo-slider.css', __FILE__);?>" />
<link rel="stylesheet" href="<?php echo plugins_url('/css/style.css', __FILE__);?>" />
    <script type="text/javascript">
    $(window).load(function() {
        $('#slider').nivoSlider();
    });
 </script>


<div class="plans">
   <section class="flr_pln2">
      <div class="noise">
      </div>
   </section>

<br />

<section class="container">
 <div class="head-bx">
   <h2>Floor Plan Detail</h2>
  <p>Find here plan detail </p>

</div>

  <div class="slider-wrapper theme-default">
<div id="slider" class="nivoSlider">
              
          <?php $planimages=(array)json_decode($plan->planimages);
	$url = plugins_url();  
	 foreach($planimages['ElevationImage'] as $eleimage):
?>

<?php/*  <img src="<?php echo $eleimage;?>"  alt="" />*/ ?>
  <img src="<?php echo $url,'/builder_design/admin/thumbnail.php?';?>src=<?php echo $eleimage,'&h=300&w=600&zc=0';?>" alt=""> 
<?php endforeach;?>     
 </div> </div>


	<div>
	<div class="span4">
		<div class="detail_left_content_module">
			<h2 style="padding-left: 3px; padding-right: 3px; font-size: 21px;"><?php echo $plan->plan_name;?></h2>
			
            
    <table width="100%" border="0" class="rt_contner" >
    <tr>
     <td><strong>SQ FT:</strong></td>
     <td><?php echo number_format($plan->base_sqft);?></td>
    </tr>
    <tr>
      <td><strong>Bedrooms:</strong></td>
      <td><?php echo $plan->bedrooms;?></td>
    </tr>
    <tr>
      <td><strong>Baths:</strong></td>
      <td><?php echo $plan->bath;?></td>
    </tr>
     <tr>
      <td><strong>Garages:</strong></td>
      <td><?php echo $plan->garage;?></td>
    </tr>
     <tr>
      <td><strong>Stories:</strong></td>
      <td><?php echo $plan->stories;?></td>
     </tr>
   </table>
		   
		</div><div class="clear"></div>
											
		</div>
	
	<div class="span8">
		<div class="floorplan">
		<h2 class="spec_head">Floor Plan</h2>	
		<?php $planimages=(array)json_decode($plan->planimages);
		
		?>
		<img class="shadow" src="<?php echo $planimages['FloorPlanImage'];?>"/>
		</div>
		<div class="floorplan">
		<h2 class="spec_head">Floor Plan Description</h2>	
		<?php echo $plan->descr;?>
		
		</div>
		<div class="floorplan">
		<h2 class="spec_head">Additional Images</h2>	
		<?php $planimages=(array)json_decode($plan->planimages);
		
		if($planimages['InteriorImage']):
			foreach($planimages['InteriorImage'] as $key=>$pimage):
		?>
		<?php if(is_array($pimage)||is_object($pimage)):?>
			<?php foreach($pimage as $pimgurl):	?>
				<img class="photo_edge" width="125" height="94" src="<?php echo $pimgurl;?>">
			<?php endforeach;?>
		<?php else:?>
		<img class="photo_edge" width="125" height="94" src="<?php echo $pimage;?>">
		<?php endif;?>
		<?php endforeach;?>
		<?php endif;?>
		</div>
	</div></div>
</section>
</div>