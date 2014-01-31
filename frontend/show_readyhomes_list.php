<?php
global $wpdb;
global $wp_query;
$rurl1=$_SERVER["REQUEST_URI"];
$rurl=explode('&',$rurl1);
$sorting=explode('&sort',$rurl1);
if($_GET['sort']=='plan_name'){$table='t2';}else{$table='t1';}
if(isset($_GET[sort])){$sort=$_GET['sort'];}else{$sort='plan_name';$table='t2';}
if(isset($_GET[order])){$order=$_GET['order'];}else{$order='ASC';}


$big = 999999999; // need an unlikely integer
$noofitems= $wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_specification where Status=1");
$limit=100;
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

 $sqlquery="SELECT t1.ID, t1.SpecAddress,t1.SpecPrice, t1.SpecBedrooms, t1.SpecBaths,t1.Status, t2.plan_name
FROM {$wpdb->prefix}bd_specification AS t1
INNER JOIN {$wpdb->prefix}bd_plan AS t2 ON t1.plan_id = t2.ID
ORDER BY $table.$sort $order
LIMIT $start, $limit
"; 
$specifications= $wpdb->get_results($sqlquery);

?>
<?php $i=0; $beaches;
		foreach($specifications as $specification)
		{
		if($specification->Status==0):
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
'<div style="float:right;width:50%" class="gmap-popup-right"><h4 style="border-bottom: 1px solid #CBAC86; color: #666666;  font-size: 15px;  font-weight: 400;  line-height: 21px; margin-bottom: 6px;  padding: 3px 3px 6px; text-decoration: none;"class="map_hover_title">'+beaches[key].title+
'</h4><span><strong>'+beaches[key].title+'</strong><br><span style="font-size: 11px;">'+
	'<strong>Price:</strong>'+beaches[key].price+'<br>'+
		'<strong>Plan:</strong>'+beaches[key].plan+'<br>'+
		'<strong>Beds:</strong>'+beaches[key].beds+'<br>'+
		'<strong>Baths:</strong> '+beaches[key].bath+'<br>'+
	'</span>'+
	'<ul style="margin: 0px; width: 160px;" class="callout_nav01">'+
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
   


   window.onload = function()
                {
                 
					//alert(beaches.m0.title);
					  
				};
</script>

<style>
	.infoBox{
.infoBox .close{float:right; }
.infoBox strong{color:#000; }
.infoBox span{font-size: 11px;font-weight: 400;}
 .map_hover_title{border-bottom: 1px solid #CBAC86;
    color: #666666;
    font-size: 15px;
    font-weight: 400;
    line-height: 21px;
    margin-bottom: 6px;
    padding: 3px 3px 6px;
    text-decoration: none;}
.ready_homes th{background:#ccc;}
.rh{border-bottom:1px solid #cccccc!important;}
</style>


<div class="plans">

<div id="map_canvas"></div>

 <section class="full_width_cont">
    <div class="container">
    
    <div class="head-bx">
       <h2>Move In Ready Homes</h2>
       <p>We materialize ideas into digital work </p>
     </div>
             

	<div class="divisions">
		<div class="collection_sort_row01">
			<div class="cll_rt"><ul class="callout_nav01">
				<li><a href="http://<?php echo $_SERVER["HTTP_HOST"].$rurl[0] ;?>&list"><i class="icon-arrow-left"></i> Back To Detailed List</a></li>
				</ul>
			</div>
			<?php if($pagination):?>
			<div class="div_pagination"><?php echo $pagination;?></div>
			
			<?php endif;?>
		</div>
		<div class="collection_sort_row02">
		<table class="rt_contner" id="ready_homes">
			<tr>
				<th onclick="window.location='http://<?php echo $_SERVER["HTTP_HOST"].$sorting[0] ;?>&sort=plan_name&order=<?php if($_GET['order']=='ASC'){echo 'DESC';}else{echo 'ASC';}?>'" style="cursor:pointer;">Plan</th>
				<th>Address</th>
				<th onclick="window.location='http://<?php echo $_SERVER["HTTP_HOST"].$sorting[0] ;?>&sort=SpecPrice&order=<?php if($_GET['order']=='ASC'){echo 'DESC';}else{echo 'ASC';}?>'" style="cursor:pointer;">Price</th>
				<th onclick="window.location='http://<?php echo $_SERVER["HTTP_HOST"].$sorting[0] ;?>&sort=SpecBedrooms&order=<?php if($_GET['order']=='ASC'){echo 'DESC';}else{echo 'ASC';}?>'" style="cursor:pointer;">Beds</th>
				<th onclick="window.location='http://<?php echo $_SERVER["HTTP_HOST"].$sorting[0] ;?>&sort=SpecBaths&order=<?php if($_GET['order']=='ASC'){echo 'DESC';}else{echo 'ASC';}?>'" style="cursor:pointer;">Bath</th>
			</tr>
			<?php foreach($specifications as $specification):
			if($specification->Status==0):
			?>
			<tr>
				<td style="border-bottom:1px solid #ccc;">
				<a href="http://<?php echo $_SERVER["HTTP_HOST"].$rurl[0] ;?>&showreadyhomesdetail=<?php echo $specification->ID; ?>">
				<?php echo $specification->plan_name; ?>
								</a>
				</td>
				<td style="border-bottom:1px solid #ccc;"><?php $address=json_decode($specification->SpecAddress);?>
					<?php  echo $address->SpecStreet1.','.$address->SpecCity.','.$address->SpecState;?>
				</td>
				<td style="border-bottom:1px solid #ccc;"><?php echo '$'.$specification->SpecPrice; ?></td>
				<td style="border-bottom:1px solid #ccc;"> <?php echo $specification->SpecBedrooms; ?></td>
				<td style="border-bottom:1px solid #ccc;"><?php echo $specification->SpecBaths; ?></td>
				
			</tr>
			<?php endif; endforeach;?>
			
		</table>
		</div>
	</div>

</div></section>

</div>