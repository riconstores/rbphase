<?php
global $wpdb;
$specification=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_specification WHERE ID=%d",$_GET['model_detail']));
$specification=$specification[0];

 $rurl=$_SERVER["REQUEST_URI"];
$rurl=explode('&plan_detail',$rurl);
  $rurl='http://'.$_SERVER["HTTP_HOST"].$rurl[0];

function checkRemoteFile($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    // don't download content
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if(curl_exec($ch)!==FALSE)
    {
        return true;
    }
    else
    {
        return false;
    }
}
/***Thumbnail generation***/
function createThumbs( $pathToImages, $pathToThumbs, $thumbWidth )
{
	 $fname=end(explode('/',$pathToImages));
	$pathToThumbs=dirname(__FILE__).'/images/thumbnails/';
	 // parse path for the extension
     $info = end(explode('.',$pathToImages));
	if(strtolower($fname)!='')
	{// continue only if this is a JPEG image
    
      // load image and get image size
	if ( strtolower($info) == 'jpg' )
    {
      $img = imagecreatefromjpeg( "{$pathToImages}" );
	} 
	elseif( strtolower($info) == 'png')
	{
		 $img = imagecreatefrompng( "{$pathToImages}" );
	}
	  
	  
      $width = imagesx( $img );
      $height = imagesy( $img );

      // calculate thumbnail size
      $new_width = $thumbWidth;
      $new_height = 98;//floor( $height * ( $thumbWidth / $width ) );

      // create a new temporary image
      $tmp_img = imagecreatetruecolor( $new_width, $new_height );

      // copy and resize old image into new image
      imagecopyresized( $tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height );

      // save thumbnail into a file
      imagejpeg( $tmp_img, "{$pathToThumbs}{$fname}" );
	}
    
    
}
// Getting Divisions
  
  $divisions= $wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_divison");
  $pageurl=$wpdb->get_results("SELECT ID,post_content,guid FROM {$wpdb->prefix}posts  WHERE post_status='publish' AND post_excerpt ='BuilderPhase' AND post_content='[BuilderPhase_build]'");
  $communitypageurl=$pageurl[0]->guid;
?>

    <script type="text/javascript">
    $(window).load(function() {
        $('#slider').nivoSlider();
    });
	
		jQuery(document).ready(function(){
			jQuery("a[rel^='prettyPhoto']").css("background","none");
			jQuery("a[rel^='prettyPhoto']").css("padding","0px");
			jQuery("a[rel^='prettyPhoto']").css("margin","0px");
				jQuery("area[rel^='prettyPhoto']").prettyPhoto();
				
				jQuery(".gallery:first a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'light_square',slideshow:3000, autoplay_slideshow: false});
				jQuery(".gallery:gt(0) a[rel^='prettyPhoto']").prettyPhoto({animation_speed:'fast',slideshow:10000, hideflash: true});
		
				
			});
 </script>
 
 <div class="plans">
 


 <section class="flr_pln2">
      <div class="noise">
    </div></section>

<br />

<section class="container">
 <div class="head-bx">
   <h2>Model Home Detail</h2>
  <p>Find here Model Home detail </p>
</div>

<!-- Start Slider-->
<?php $planimages=(array)json_decode($specification->SpecImages);
		
	if(is_array($planimages['ElevationImage'])):?>
		  <div class="slider-wrapper theme-default">
			<div id="slider" class="nivoSlider">
			  <?php 
				 foreach($planimages['ElevationImage'] as $eleimage):
			?>

			  <img src="<?php echo $eleimage;?>"  alt="<?php echo $eleimage;?>" />
			<?php endforeach;?>
			 </div> 
		 </div>
	<?php else:?>
	<?php if($planimages['ElevationImage']!=''):?>
	<div style="width:98%;margin:auto;"> <img class="shadow" style="width:100%;" src="<?php echo $planimages['ElevationImage'];?>"  alt="<?php echo $eleimage;?>" /></div>
	<?php endif;?> 
	<?php endif;?>     
	


 <div class="clear"></div>
 <br>

	<div class="">
		<div class="span4">
            <div class="detail_left_content_module" >
				<div>
				<h2 class="spec_head">
				<?php 
				$pname=$wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_plan WHERE ID=%d",$specification->plan_id));
				echo $pname[0]->plan_name ;?></h2>
				<p>
 <table width="100%" border="0" class="rt_contner" >
    <tr>
      <td><strong>SpecPrice:</strong></td>
      <td> <?php echo $specification->SpecPrice;?></td>
    </tr>
    <tr>
     <td><strong>SQ FT:</strong></td>
     <td><?php echo $specification->SpecSqft;?></td>
    </tr>
    <tr>
      <td><strong>Bedrooms:</strong></td>
      <td><?php echo $specification->SpecBedrooms;?></td>
    </tr>
    <tr>
      <td><strong>Baths:</strong></td>
      <td><?php echo $specification->SpecBaths;?></td>
    </tr>
     <tr>
      <td><strong>Home Style:</strong></td>
      <td><?php $ftype=(array)json_decode($specification->Attributes); echo $ftype['Type'];?></td>
     </tr>
   </table>
			</div>
			   <?php if(count($divisions)>0):?>
				<div class="community-feature" style="margin-top:10px;">
				<h3>Available To Build</h3>
				<ul>
					<?php foreach($divisions as $division):?>
					<li>
						<a href="<?php echo $communitypageurl;?>&detail=<?php echo $division->ID;?>"><?php echo $division->name;?><br></a>
					</li>
					<?php endforeach;?>
					</ul>
				</div>
				<?php endif;?>
			</div>
			
			
				
			<div class="clear"></div>
                                                
            </div>
	
	<div class="span8">
    
      <div class="floorplan"><h2>
 <?php  $address=$specification->SpecAddress;
		$address=(json_decode($address));
		echo $address->SpecStreet1.''.$address->SpecCity.', '.$address->SpecState.' '.$address->SpecZIP;
 ?></h2>
 </div>
    
    
		<?php $planimages=(array)json_decode($specification->SpecImages);
		if($planimages['FloorPlanImage']!=''&&checkRemoteFile($planimages['FloorPlanImage'])):
		?>
		<div class="floorplan">
		<a rel="prettyPhoto[gallery]" href="<?php echo $planimages['FloorPlanImage'];?>">
			<img class="shadow" src="<?php echo $planimages['FloorPlanImage'];?>"/>
			</a>
		</div>
		<?php endif;?>
		
		
		
			
		<?php $planimages=(array)json_decode($specification->SpecImages);
		
		if($planimages['InteriorImage']):?>
		<div class="floorplan">
		<h2 class="spec_head">Additional Photos</h2>
		<?php
			foreach($planimages['InteriorImage'] as $key=>$pimage):
		?>
		<?php if(is_array($pimage)||is_object($pimage)):?>
			<?php foreach($pimage as $pimgurl):	
				$dimage=end(explode('/',$pimgurl));
				$dimageurl=plugins_url().'/builder_design/frontend/images/thumbnails/'.$dimage;	
				if(!checkRemoteFile($dimageurl)&&checkRemoteFile($pimgurl)){createThumbs( $pimgurl, $file_loc, 145 );}
				if(checkRemoteFile($dimageurl)):
			?>	
			
			<a rel="prettyPhoto[gallery]" href="<?php echo $pimgurl;?>">
				<img class="photo_edge" width="125" height="94" src="<?php echo $dimageurl;?>">
			</a>
			<?php endif;endforeach;?>
		<?php else:?>
		<?php
			$dimage=end(explode('/',$pimage));
				$dimageurl=plugins_url().'/builder_design/frontend/images/thumbnails/'.$dimage;	
				if(!checkRemoteFile($dimageurl)&&checkRemoteFile($pimage)){createThumbs( $pimage, $file_loc, 145 );}
				if(checkRemoteFile($dimageurl)):
		?>
		<a rel="prettyPhoto[gallery]" href="<?php echo $pimage;?>">
			<img class="photo_edge" width="125" height="94" src="<?php echo $dimageurl;?>">
		</a>
		<?php endif;endif;?>
		<?php endforeach;?>
		
		</div>
		<?php endif;?>
		
		<?php $vi=json_decode($specification->SpecVirtualTour);
		if($vi):
		?>
		<div class="floorplan">
		<h2 class="spec_head" style="margin-bottom:7px;">Virtual Tour / Video</h2>
		<iframe width="100%" height="730" frameborder="0" allowfullscreen="" src="<?php echo $vi;?>"></iframe>
		</div>
		<?php endif;?>
	</div></div>
</section>
</div>