<?php
global $wpdb;
global $wp_query;

$big = 999999999; // need an unlikely integer
$noofitems= $wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_specification where Status=0");
$limit=30;
$totalpage=ceil(count($noofitems)/$limit);
if(isset($_GET['paged']))
{  $start=$_GET['paged']*$limit-$limit;}
else{ $start=0;}
$pagination= paginate_links( array(
	'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
	'format' => '?paged=%#%',
	'current' => max( 1, get_query_var('paged') ),
	'total' => $totalpage
) );
$specifications= $wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_specification LIMIT $start, $limit");
$rurl=$_SERVER["REQUEST_URI"];
$rurl=explode('&',$rurl);
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

?>
<?php $i=0; $beaches;
		foreach($specifications as $specification)
		{ if($specification->Status==0):
			$address=json_decode($specification->SpecAddress);
			$loglat=$address->SpecGeocode;
			$images=(array)json_decode($specification->SpecImages);
		
			$planname= $wpdb->get_results($wpdb->prepare("SELECT ID,plan_name FROM {$wpdb->prefix}bd_plan WHERE ID=%d",$specification->plan_id));
		
			$beaches['m'.$i]['title']=$address->SpecStreet1;
			$beaches['m'.$i]['longi']=$loglat->SpecLongitude;
			$beaches['m'.$i]['lati']=$loglat->SpecLatitude;
			$beaches['m'.$i]['zind']=$i;
			$beaches['m'.$i]['plan']=$planname[0]->plan_name;
			$beaches['m'.$i]['price']=$specification->SpecBaths;
			$beaches['m'.$i]['beds']=$specification->SpecBedrooms;
			$beaches['m'.$i]['bath']=$specification->SpecBaths;
			$beaches['m'.$i]['url']='http://'.$_SERVER["HTTP_HOST"].$rurl[0].'&showreadyhomesdetail='.$specification->ID;
			if($images[InteriorImage]){ 
				if(file_exists($images[InteriorImage][0])){ 
					$beaches['m'.$i]['image']=$images[InteriorImage][0];
					}
					else{
					$beaches['m'.$i]['image']=plugins_url().'/builder_design/frontend/images/placement.png';
					}
			
			}
			else{$beaches['m'.$i]['image']=plugins_url().'/builder_design/frontend/images/placement.png';}
			$i++;
			endif;
		}
		
	?>

<style>
	.infoBox{
.infoBox .close{float:right; }
.infoBox strong{color:#000; }
.infoBox span{font-size: 11px;font-weight: 400;}
 .map_hover_title{border-bottom: 1px solid #cccccc;
    color: #666666;
    font-size: 15px;
    font-weight: 400;
    line-height: 21px;
    margin-bottom: 6px;
    padding: 3px 3px 6px;
    text-decoration: none;}
	.hor_mod_sqft, .hor_mod_garage, .hor_mod_story, .hor_mod_bath {
    width: 14%;
}

</style>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script>
jQuery(document).ready(function(){
	 var geocoder;
    var map;
	var beaches =JSON.parse('<?php echo json_encode($beaches); ?>'); 
	function initialize() {
		   var mapOptions = {
			zoom:10,
			center: new google.maps.LatLng('<?php echo $beaches["m0"]["lati"];?>','<?php echo $beaches["m0"]["longi"];?>')
		  }
		  var map = new google.maps.Map(document.getElementById('map_canvas'),
										mapOptions);

		  setMarkers(map, beaches);

	}
	var infowindow = new google.maps.InfoWindow();
	var image = '<?php echo plugins_url().'/builder_design/frontend/images/map_icn.png';?>';
  //var service = new google.maps.places.PlacesService(map);
	var markers = [];
	var infos=[];
		function setMarkers(map, beaches) {
		  var i=0;
		  for(var key in beaches) {
						
						var myLatLng = new google.maps.LatLng(beaches[key].lati, beaches[key].longi);
						var marker = new google.maps.Marker({
							position: myLatLng,
							map: map,
							Icon:image,
							title: beaches[key].title,
							zIndex: beaches[key].zind
						});
						// var content = '<div class="map-content"><img src="'+beaches[key].image+'"><h3>' + beaches[key].title + '</h3></div>';
						  /*start content*/
						  
var content='<div class="infoBox" style="width:400px;">'+
'<div class="gmap-popup-body mapMarkerInfoItem"><div style="float:left;width:50%" class="gmap-popup-left">'+
'<div class="img-div"><img src="'+beaches[key].image+'"/></div></div>'+
'<div style="float:right;width:50%" class="gmap-popup-right"><h4 style="border-bottom: 1px solid #cccccc; color: #666666;  font-size: 15px;  font-weight: 400;  line-height: 21px; margin-bottom: 6px;  padding: 3px 3px 6px; text-decoration: none;"class="map_hover_title">'+beaches[key].title+
'</h4><span><strong>'+beaches[key].title+'</strong><br><span style="font-size: 11px;">'+
	'<strong>Price:</strong>'+beaches[key].price+'<br>'+
		'<strong>Plan:</strong>'+beaches[key].plan+'<br>'+
		'<strong>Beds:</strong>'+beaches[key].beds+'<br>'+
		'<strong>Baths:</strong> '+beaches[key].bath+'<br>'+
	'</span>'+
	'<ul style="margin: 0px; width: 130px;" class="callout_nav01">'+
		' <li style="margin: 0px;"><a href="'+beaches[key].url+'">View Details</a></li>'+
	'</ul></div></div></div>'
 ;
						  /*end content*/
						  google.maps.event.addListener(marker, 'click', (function(marker, content) {
							return function() {
								infowindow.setContent(content);
								infowindow.open(map, marker);
							}
						})(marker, content));
						google.maps.event.addListener(map, 'click', function() {
						infowindow.close();
						});  
					}
					
		}
		
		
		
		
		function openinfowindow(map,marker)
		{
			infowindow.setContent(marker.title);
			infowindow.open(map, marker);
		}
		initialize();
});
</script>


<div class="plans">
	<div id="map_canvas"></div>

<section class="full_width_cont">
    <div class="container">
    
    <div class="head-bx">
       <h2>Move In Ready Homes</h2>
       <p>Move New Homes in North Carolina </p>
     </div>
             
             

	<div class="divisions">
		<div class="viewallbutton" style="padding:2px;margin-top:0px; text-align:right">
			<a href="http://<?php echo $_SERVER["HTTP_HOST"].$rurl[0] ;?>&readyhomes-list">View all Available Homes</a>
		</div>
		<div class="collection_sort_row02"><div class="pages"><?php echo $pagination;?></div></div>
		
		<div class="collection_sort_row02">
			
				<?php foreach($specifications as $specification):if($specification->Status==0):?>
				<div class="hor_mod_wrapper">
					<div class="hor_mod_img_div">
					<?php $image=json_decode($specification->SpecImages);
						$image=$image->ElevationImage;
						
					$dimage=end(explode('/',$image));
					
					$dimageurl=plugins_url().'/builder_design/frontend/images/thumbnails/'.$dimage;	
					if(!checkRemoteFile($dimageurl)&&checkRemoteFile($image)){createThumbs( $image, $file_loc, 145 );}
						
					if(checkRemoteFile($dimageurl)):
					
					
					?><a href="http://<?php echo $_SERVER["HTTP_HOST"].$rurl[0] ;?>&showreadyhomesdetail=<?php echo $specification->ID;?>">
					<img class="shadow" width="150" alt="" src="<?php echo $dimageurl;?>" />
					</a>
					
					<?php endif;?>
						
					</div>
					
					<!-- start of horiz_box_content_right -->
			<div class="hor_mod_text_wrapper" >
			<div class="hor_mod_title">
					<?php $address=json_decode($specification->SpecAddress);?>
						<p class="hor_mod_address">
						<a href="http://<?php echo $_SERVER["HTTP_HOST"].$rurl[0] ;?>&showreadyhomesdetail=<?php echo $specification->ID;?>">
								<?php  echo $address->SpecStreet1.','.$address->SpecCity.','.$address->SpecState;?>
							</a></p>
						<h4 class="hor_mod_price"></h4>
					</div>
				<ul>
					<li class="hor_mod_bed">BEDS<br><span><?php echo $specification->SpecBedrooms;?></span></li>
					<li class="hor_mod_bath">BATHS<br><span><?php echo $specification->SpecBaths;?></span></li>
					<li class="hor_mod_story">STORIES<br><span><?php echo $specification->SpecStories;?></span></li>
					<li class="hor_mod_sqft">SQFT<br><span><?php  echo number_format($specification->SpecSqft);?>	</span></li>
					<li class="hor_mod_sqft1">Price<br><span><?php echo '$'.number_format($specification->SpecPrice); ?></span></li>
					<div class="hor_mod_btn">
					<a href="http://<?php echo $_SERVER["HTTP_HOST"].$rurl[0] ;?>&showreadyhomesdetail=<?php echo $specification->ID;?>">View Detail</a></div>
				</ul>
			   
			</div><!-- end of hor_mod_text_wrapper -->
						
						<div class="clear"></div>
					</div>
					
					
				<?php  endif; endforeach;?>
			
		</div>
	
	</div>

</div></section>

</div>