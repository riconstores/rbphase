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

</div></section>

<br />

<section class="container">
 <div class="head-bx">
   <h2>Floor Plan Detail</h2>
  <p>Find here plan detail </p>

</div>

  <div class="slider-wrapper theme-default">
<div id="slider" class="nivoSlider">
              
          <?php $planimages=(array)json_decode($plan->planimages);
	
	 foreach($planimages['ElevationImage'] as $eleimage):
?>

  <img src="<?php echo $eleimage;?>"  alt="" />
<?php endforeach;?>     
 </div> </div>


	
	<div class="span4">
		<div class="detail_left_content_module">
			<h2 style="padding-left: 3px; padding-right: 3px; font-size: 21px;"><?php echo $plan->plan_name;?></h2>
			<p>
			
			<span>SQ FT:</span> <?php echo number_format($plan->base_sqft);?><br>
			<span>Bedrooms:</span> <?php echo $plan->bedrooms;?><br>
			<span>Baths:</span> <?php echo $plan->bath;?><br>
			
			<span>Garages:</span> <?php echo $plan->garage;?><br>
			<span>Stories:</span> <?php echo $plan->stories;?></p>
		   
		</div><div class="clear"></div>
											
		</div>
	
	<div class="span8">
		<div class="floorplan">
		<h3>Floor Plan</h3>	
		<?php $planimages=(array)json_decode($plan->planimages);
		
		?>
		<img class="shadow" src="<?php echo $planimages['FloorPlanImage'];?>"/>
		</div>
		<div class="floorplan">
		<h3>Floor Plan Description</h3>	
		<?php echo $plan->descr;?>
		
		</div>
		<div class="floorplan">
		<h3>Additional Images</h3>	
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
	</div>
</section>
</div>