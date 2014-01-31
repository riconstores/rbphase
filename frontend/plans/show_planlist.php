<?php
global $wpdb;
global $wp_query;
$rurl1=$_SERVER["REQUEST_URI"];
$rurl=explode('&',$rurl1);
$sorting=explode('&sort',$rurl1);
$divisions = $wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_divison");
if(isset($_GET['division'])){$divisionid=$_GET['division'];}
else{$divisionid=$divisions[0]->ID; }
if(isset($_GET['sort'])){$sort=$_GET['sort'];}else{$sort='plan_name';}
if(isset($_GET['order'])){$order=$_GET['order'];}else{$order='ASC';}

$big = 999999999; // need an unlikely integer
$noofitems= $wpdb->get_var("SELECT count(*) FROM {$wpdb->prefix}bd_plan where Status=0");
$limit=30;

$totalpage=ceil($noofitems/$limit);
if(isset($_GET['paged']))
{  $start=$_GET['paged']*$limit-$limit;
	$current=$_GET['paged'];
}
else{ $start=0;$current=1;}

$plans=$wpdb->get_results("SELECT * FROM  {$wpdb->prefix}bd_plan  Group By `plan_number` ORDER BY $sort $order LIMIT $start,$limit ");
 
$pagination= paginate_links( array(
	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	'format' => '?paged=%#%',
	'current' => max( $current, get_query_var('paged') ),
	'total' => $totalpage
) );

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
	$pathToThumbs=dirname(__FILE__).'/thumbnails/';
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


?>

<link rel="stylesheet" href="<?php echo plugins_url().'/builder_design/frontend/css/style.css';?>" />
<meta name="viewport" content="width=device-width">
<style>
.planleft ul li{list-style:none;curson:pointer;background:#000;}
</style>

 
 <section class="flr_pln">
      <div class="noise">
          <div class="container">
          
            <div class="row flr_planbx">
             <div class="span6"><img src="http://demo7.builderphase.com/wp-content/uploads/2014/01/floor_plan.png" alt="floor_plan" /></div>  
              <div class="span6">
                  <h2 class="magictime swashIn">Find Our<br />
              <strong> Floor Plan</strong></h2>
                <p class="magictime tinLeftIn">Imagine Yourself in This Home</p>
                <p class="flr_cont">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since</p>
             </div>
            </div>
          
          </div>
      </div>
 </section>
 
 
 <section class="full_width_cont">
    <div class="container">

<div class="head-bx">
       <h2> Available Plans</h2>
       <p>We materialize ideas into digital work </p>
     </div>
<div class="plans2">
	<div style="float:left;margin-left:2px;"  class="collection_sort_row02">
	<div class="collection_sort_row01">
                                            
	<div class="jmp_rt"><span>Jump Directly to a Selected Floor Plan</span>
	<select class="select_bx" name="division" onchange="window.location=this.value;">
			<option value="http://<?php echo $_SERVER["HTTP_HOST"].$rurl[0] ;?>">Select Floor Plan</option>
				<?php foreach($plans as $plan):if($plan->Status==0):?>
					<option value='http://<?php echo $_SERVER["HTTP_HOST"].$rurl[0] ;?>&plan_detail=<?php echo $plan->ID;?>'>
						<?php echo $plan->plan_name;?>
					</option>
				<?php endif;endforeach;?>
			</select></div><br />
	<div class="srt_list">
    <ul>
    <li class="srt_frst"><strong>Sort By:</strong></li>
	  <li><a href="http://<?php echo $_SERVER["HTTP_HOST"].$sorting[0] ;?>&sort=plan_name">A to Z</a> </li>
	  <li><a href="http://<?php echo $_SERVER["HTTP_HOST"].$sorting[0] ;?>&sort=base_price&order=DESC">Price High to Low</a></li>
	  <li><a href="http://<?php echo $_SERVER["HTTP_HOST"].$sorting[0] ;?>&sort=base_price">Price Low to High</a> </li>
	  <li> <a href="http://<?php echo $_SERVER["HTTP_HOST"].$sorting[0] ;?>&sort=base_sqft">SQFT</a></li>
	  <li><a href="http://<?php echo $_SERVER["HTTP_HOST"].$sorting[0] ;?>&sort=bath">Bath</a></li>
	  <li><a href="http://<?php echo $_SERVER["HTTP_HOST"].$sorting[0] ;?>&sort=bedrooms">Beds</a></li>
	</ul>
	</div>
	
	<div class="clear"></div>
	</div>
				
	
	<?php if(count($plans)<=0):?> <h2 class="errormessage">There are no Data.</h2><?php endif;?>
	<?php if(isset($pagination)):?>
	<div class="collection_sort_row02"><div class="pages"><?php echo $pagination;?></div></div>
	<?php endif;?>
	<div class="clear"></div>
	<div class="divisions">
	
	<?php if($noofitems==0){echo 'There are no Plan available.';}?>
	<?php foreach($plans as $plan): if($plan->Status==0):?>
	<div class="hor_mod_wrapper">
			<div class="hor_mod_img_div">
			<?php  $planimages=json_decode($plan->planimages);
				$img=$planimages->InteriorImage;
				if(is_array($img))
				{  $thumburl=$img[0];}
				else{  $thumburl=$img;}
				
				  $dimage=end(explode('/',$thumburl));
			
					
					$dimageurl=plugins_url().'/builder_design/frontend/plans/thumbnails/'.$dimage;	
					if(!checkRemoteFile($dimageurl)&&checkRemoteFile($thumburl)){createThumbs($thumburl, $file_loc, 145 );}
						
			if(checkRemoteFile($dimageurl)&& $dimage!=''):
			
			?>
				<a href="http://<?php echo $_SERVER["HTTP_HOST"].$rurl[0] ;?>&plan_detail=<?php echo $plan->ID;?>">
				<img width="130" class="shadow" alt="" src="<?php echo $dimageurl;?>"></a>
				
				<?php endif;?>
			</div><!-- end of hor_mod_img_div -->
		   <style type="text/css">
		   .hor_mod_text_wrapper{
		   float:none;
		   width:auto;
		   overflow:hidden;}
		   </style>
			
			
			<!-- start of horiz_box_content_right -->
			<div class="hor_mod_text_wrapper">
			<div class="hor_mod_title">
				<p class="hor_mod_address">
				<a href="http://<?php echo $_SERVER["HTTP_HOST"].$rurl[0] ;?>&plan_detail=<?php echo $plan->ID;?>">
				<?php echo $plan->plan_name;?>
				</a></p>
				<h4 class="hor_mod_price"></h4>
			</div><!-- end of hor_mod_text_wrapper -->
				<ul>
					<li class="hor_mod_bed">BEDS<br><span><?php echo $plan->bedrooms;?></span></li>
					<li class="hor_mod_bath">BATHS<br><span><?php echo $plan->bath;?></span></li>
					<li class="hor_mod_story">STORIES<br><span>2</span></li>
					<li class="hor_mod_garage">GARAGE<br><span>2</span></li>
					<li class="hor_mod_sqft">SQFT<br><span><?php echo number_format($plan->base_sqft);?>	</span></li>
					<div class="hor_mod_btn">
					<a href="http://<?php echo $_SERVER["HTTP_HOST"].$rurl[0] ;?>&plan_detail=<?php echo $plan->ID;?>">
					VIEW DETAILS</a></div>
				</ul>
			   
			</div><!-- end of hor_mod_text_wrapper -->
		 <div class="clear"></div>
	</div>
	
	<?php endif; endforeach; ?>	
	
		
	</div>
	</div>
</div>


</div>
</section>