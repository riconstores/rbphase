<script src="<?php echo plugins_url().'/builder_design/frontend/js/jquery-1.9.0.min.js';?>"></script>
<script src="<?php echo plugins_url().'/builder_design/frontend/js/jquery.nivo.slider.js';?>"></script>
<link rel="stylesheet" href="<?php echo plugins_url().'/builder_design/frontend/css/nivo-slider.css';?>" />
<link rel="stylesheet" href="<?php echo plugins_url().'/builder_design/frontend/css/style.css';?>" />
    <script type="text/javascript">
    $(window).load(function() {
        $('#slider').nivoSlider();
    });
 </script>
 <?php
 global $wpdb;
$divisions= $wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_divison WHERE ID=%d",$_GET['detail']));
$division=$divisions[0];
$rurl1=$_SERVER["REQUEST_URI"];
$rurl=explode('&',$rurl1);
$contenturl=explode('&content',$rurl1);
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




 ?>
<div id="bdesign" style="padding:4px 8px"> 
 <h1><span><?php echo $division->name ;?> </span> 
 <?php if($_GET['content']=='floorplan'):?>
 Floor Plans
 <?php elseif($_GET['content']=='model-homes'):?>
 Model Homes
 <?php elseif($_GET['content']=='move-in-ready-homes'):?>
 Move-in Ready Homes
 <?php else:?>
 Community Overview
 <?php endif;?>
 
 
 
 </h1>
	<div id="comm_header_box" style="width:100%;">
	   <div class="horiz_list_row_left">
		  <img class="shadow" width="120" height="90" alt="" src="<?php echo $division->image;?>">
		   <div class="clear"></div>
	   </div>
		<div class="horiz_list_row_right">
			 <div class="horiz_list_row_right_cols">
				<h5 style="line-height: 18px; padding: 0px;"><span>Address:</span><br>
				<?php $address=json_decode($division->sub_address);
				echo $address->SubCity.' , '.$address->SubState;
				?><br>
				<span>PH:</span>
				<?php $phone=json_decode($division->phone); echo $phone->AreaCode.'-'.$phone->Prefix.'-'.$phone->Suffix;?><br>
				  </h5>
			</div><!-- end of col01 -->
			
			<!-- start of col02 -->
			<div class="horiz_list_row_right_cols">
				<h5 style="line-height: 18px; padding: 0px;">
				<span>Price Range:</span> From <?php echo '$'.number_format($division->sqft_low);?> - <?php echo '$'.number_format($division->price_high);?>(home only)<br>
				<span>SQ FT Range:</span> <?php echo number_format($division->sqft_low);?> - <?php echo number_format($division->sqft_high);?><br>
					
				</h5>
			</div><!-- end of col02 -->
			
			<!-- 
			<div style="margin-right: 0px;" class="horiz_list_row_right_cols">
				<ul style="margin: 0px; margin-top: -10px;" class="list03_bullet">
					<li><a href="/brunswick-forest---c.f.-national-photos">Photos</a> | <a href="/brunswick-forest---c.f.-national-videos">Videos</a></li>
					<li><a href="/images/uploaded/277091018855571_cape_fear_natl.jpg" class="lb">Community Map</a></li>
					<li><a href="/dream.php?com=33">Add to Dream Folder</a></li>
				</ul>
			</div>end of col03 -->
			
			<div class="clear"></div>
			
		</div><!-- end of horiz_list_row_right -->
		
		<div class="clear"></div>
		
		<!-- start of comm_header_nav -->
		<ul style="margin-top: 10px;" class="comm_header_nav_bar">
			<li><a style="font-weight: 400; color: #fff; text-decoration: none;" href="http://<?php echo $_SERVER["HTTP_HOST"].$contenturl[0] ;?>">Overview</a></li>
			<li><a style="font-weight: 400; color: #fff; text-decoration: none;" href="http://<?php echo $_SERVER["HTTP_HOST"].$contenturl[0] ;?>&content=move-in-ready-homes">Move-In Ready Homes</a></li>
			<li><a style="font-weight: 400; color: #fff; text-decoration: none;" href="http://<?php echo $_SERVER["HTTP_HOST"].$contenturl[0] ;?>&content=floorplan">Floor Plans</a></li>
			<li><a style="font-weight: 400; color: #fff; text-decoration: none;" href="http://<?php echo $_SERVER["HTTP_HOST"].$contenturl[0] ;?>&content=model-homes">Model Homes</a></li>
			<li><a style="font-weight: 400; color: #fff; text-decoration: none;"  href="javascript:void(null)" onclick="$('.inq_form').show();$('.overlay').show();">Request More Info</a></li>
		</ul><!-- end of comm_header_nav -->
		
		<div class="clear"></div>
		
	</div>
	<div class="clear"></div>
	<!-- Start Slider-->
	<?php 
	$pageurl=$wpdb->get_results("SELECT ID,post_content,guid FROM {$wpdb->prefix}posts  WHERE post_status='publish' AND post_excerpt ='riconllc' AND post_content='[RiconLLC]'");
	$planpageurl=$pageurl[0]->guid;
	$plans = $wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_plan  HAVING divison_id=%d",$_GET['detail']));?>
	<?php if($_GET['content']=='floorplan'):?>
		
	<div class="collection_results_row" style="width:100%;">
	<?php if(count($plans)>0):?>
		<?php foreach($plans as $plan):?>
		<div class="vert_mod_wrapper">
			<div class="vert_mod_img_div">
			<a href="#">
			<?php  $planimages=json_decode($plan->planimages);
				$img=$planimages->InteriorImage;
			if($img[0] && checkRemoteFile($img[0])):
			
			?>
			<img class="shadow" width="158" height="119" alt="<?php echo $plan->plan_name;?>" src="<?php echo $img[0];?>">
			<?php else: ?>
			<img class="shadow" width="158" height="119" alt="<?php echo $plan->plan_name;?>" src="<?php echo plugins_url().'/builder_design/frontend/css/placement.png'; ?>">
			<?php endif; ?>
			</a></div>
			<div class="vert_mod_text_wrapper">
				<h4><a href="#">
				<?php echo $plan->plan_name;?>
				</a></h4>
				<h5><span>SQ FT:</span><?php echo number_format($plan->base_sqft);?><br>
				<span>Bedrooms:</span> <?php echo $plan->bedrooms;?><br>
				<span>Baths:</span> <?php echo $plan->bath;?><br>
				<span>Stories:</span> <?php echo $plan->stories;?></h5>
				<div class="vert_mod_text_wrapper_btns">
					<ul style="margin: 0px;">
						<li style="margin: 0px;">
						<a href="<?php echo $planpageurl;?>&plan_detail=<?php echo $plan->ID;?>">View Detail</a></li>
					</ul>
				</div>
			</div>
		</div>
		<?php endforeach;?>
		
		<?php else:?>
		 There are no plan..!!!
		<?php endif;?>
	</div>                                               
	<?php elseif($_GET['content']=='move-in-ready-homes'):?>
	<?php 
	$pageurl=$wpdb->get_results("SELECT ID,post_content,guid FROM {$wpdb->prefix}posts  WHERE post_status='publish' AND post_excerpt ='riconllc' AND post_content='[RiconLLC_ReadyHomes]'");
	$readyhomes = $wpdb->get_results($wpdb->prepare("SELECT *FROM {$wpdb->prefix}bd_specification  HAVING divison_id=%d",$_GET['detail']));?>
	<?php if(count($readyhomes)>0):?>
	<?php else: ?>
	
	<?php $pageurl=$pageurl[0]->guid;?>
	
	<?php $plans=$wpdb->get_results("SELECT ID,plan_name FROM {$wpdb->prefix}bd_plan  WHERE divison_id=$_GET[detail]");?>
	
	<?php 
	$j=0;
	foreach($plans as $plan):
	$pid=$plan->ID;
	$speci=$wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_specification  WHERE plan_id=$pid");
	$speci=$speci[0];
	
	if(count($speci)>0&&$speci->SpecIsModel!=1):
	$j++;
	?>
	<div class="vert_mod_wrapper">
			<div class="vert_mod_img_div">
			<a href="<?php echo $pageurl;?>&model_detail=<?php echo $speci->ID;?>">
			<?php  $planimages=json_decode($speci->SpecImages);
				$img=$planimages->InteriorImage;
				
			if($img[0]):
			
			?>
			<img width="158" height="119" alt="<?php echo $speci->plan_name;?>" src="<?php echo $img[0];?>">
			<?php else: ?>
			<div style="min-width:;min-height:100px;">No Image</div>
			<?php endif; ?>
			</a></div>
			<div class="vert_mod_text_wrapper">
				<h4><a href="<?php echo $pageurl;?>&model_detail=<?php echo $speci->ID;?>">
				<?php echo $plan->plan_name;?>
				</a></h4>
				<h5><span>SQ FT:</span><?php echo number_format($speci->SpecSqft);?><br>
				<span>Bedrooms:</span> <?php echo $speci->SpecBedrooms;?><br>
				<span>Baths:</span> <?php echo $speci->SpecBaths;?><br>
				<span>Stories:</span> <?php echo $speci->SpecStories;?></h5>
				<div class="vert_mod_text_wrapper_btns">
					<ul style="margin: 0px;">
						<li style="margin: 0px;">
						<a href="<?php echo $pageurl;?>&model_detail=<?php echo $speci->ID;?>">View Detail</a></li>
					</ul>
				</div>
			</div>
		</div>
	<?php endif;endforeach;
	if($j<=0){
	?>
		Sorry, there aren't currently any model homes in this neighborhood.
		<a href="<?php echo $pageurl;?>">Click here to view model homes by map.</a>
	<?php } endif;?>
	
	
	<?php elseif($_GET['content']=='model-homes'):?>
	<?php 
	/*model home section start*/
	$pageurl=$wpdb->get_results("SELECT ID,post_content,guid FROM {$wpdb->prefix}posts  WHERE post_status='publish' AND post_excerpt ='riconllc' AND post_content='[RiconLLC_Model]'");
	    $pageurl=$pageurl[0]->guid;
	?>
	
	<?php $plans=$wpdb->get_results("SELECT ID,plan_name FROM {$wpdb->prefix}bd_plan  WHERE divison_id=$_GET[detail]");?>
	
	<?php 
	$j=0;
	foreach($plans as $plan):
	$pid=$plan->ID;
	$speci=$wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_specification  WHERE plan_id=$pid");
	$speci=$speci[0];
	
	if(count($speci)>0&&$speci->SpecIsModel==1):
	$j++;
	?>
	<div class="vert_mod_wrapper">
			<div class="vert_mod_img_div">
			<a href="<?php echo $pageurl;?>&model_detail=<?php echo $speci->ID;?>">
			<?php  $planimages=json_decode($speci->SpecImages);
				$img=$planimages->InteriorImage;
				
			if($img[0]):
			
			?>
			<img width="158" height="119" alt="<?php echo $speci->plan_name;?>" src="<?php echo $img[0];?>">
			<?php else: ?>
			<div style="min-width:;min-height:100px;">No Image</div>
			<?php endif; ?>
			</a></div>
			<div class="vert_mod_text_wrapper">
				<h4><a href="<?php echo $pageurl;?>&model_detail=<?php echo $speci->ID;?>">
				<?php echo $plan->plan_name;?>
				</a></h4>
				<h5><span>SQ FT:</span><?php echo number_format($speci->SpecSqft);?><br>
				<span>Bedrooms:</span> <?php echo $speci->SpecBedrooms;?><br>
				<span>Baths:</span> <?php echo $speci->SpecBaths;?><br>
				<span>Stories:</span> <?php echo $speci->SpecStories;?></h5>
				<div class="vert_mod_text_wrapper_btns">
					<ul style="margin: 0px;">
						<li style="margin: 0px;">
						<a href="<?php echo $pageurl;?>&model_detail=<?php echo $speci->ID;?>">View Detail</a></li>
					</ul>
				</div>
			</div>
		</div>
	<?php endif;endforeach;
	if($j<=0){
	?>
		Sorry, there aren't currently any model homes in this neighborhood. 
		<a href="<?php echo $pageurl;?>">Click here to view model homes by map.</a>
		<?php } ?>
		
		
	<?php /*model home section end*/
	else:?>
	  <div class="slider-wrapper theme-default">
		<div id="slider" class="nivoSlider">
		  <img src="<?php echo $division->image;?>"/>
		</div> 
	 </div>
	<h2 style="padding-left: 3px; padding-right: 3px; font-size: 21px; line-height: 24px;">
	<?php echo $division->name ;?> Overview</h2>
	<div class="detail_left">

		<!-- start of nav -->
		<div class="detail_left_content_module">
			<ul class="left_menu">
				<li><a href="http://<?php echo $_SERVER["HTTP_HOST"].$rurl[0] ;?>">Back to Communities</a></li>
				<li><a onclick="$('.inq_form').show();$('.overlay').show();" href="javascript:void(null);">Inquire About Community</a></li>
				
				<li><a href="http://<?php echo $_SERVER["HTTP_HOST"].$rurl[0] ;?>">Floor Plans</a></li>
				
			</ul>
			<div class="community-feature">
				<h3>Community-Feature</h3>
			
			</div>
			<?php if(count($plans)>0):?>
				<div class="community-feature">
				<h3>Available Floor Plans</h3>
					<?php foreach($plans as $plan):?>
						<?php echo $plan->plan_name;?><br>
					<?php endforeach;?>
				</div>
			<?php endif;?>
			
		</div><!-- end of nav -->
		
		<div class="clear"></div>
	</div>
	<div class="detail_right">
		<div class="detail_right_module">
			<h6>Community Description</h6>
			<p><?php echo $division->descr;?></p>
		</div>
																									
		<div class="detail_right_module">
			<h6>Map &amp; Directions</h6>
			<div style="float: left; width: 45%; margin-right: 10px;">
			<h5><?php echo $division->driv_direction;?></h5></div>
			<?php
			 $i=0; $beaches;
			$address=json_decode($division->sub_address);
		
			$loglat=$address->SubGeocode;
			
			//$images=(array)json_decode($division->image);
		
			$planname= $wpdb->get_results($wpdb->prepare("SELECT ID,plan_name FROM {$wpdb->prefix}bd_plan WHERE ID=%d",$specification->plan_id));
		
			$beaches['m'.$i]['title']=$division->name;
			$beaches['m'.$i]['longi']=$loglat->SubLongitude;
			$beaches['m'.$i]['lati']=$loglat->SubLatitude;
			$beaches['m'.$i]['zind']=$i;
			$beaches['m'.$i]['price']='From $'.number_format($division->sqft_low).' - $'.number_format($division->price_high);
			$beaches['m'.$i]['url']='https://maps.google.com/maps?q='.$loglat->SubLatitude.'+'.$loglat->SubLongitude;
			$beaches['m'.$i]['image']=$division->image;
			$i++;
			
			
			?>
			<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script>
    var geocoder;
    var map;
	var beaches =JSON.parse('<?php echo json_encode($beaches); ?>'); 
	function initialize() {
		   var mapOptions = {
			zoom:10,
			center: new google.maps.LatLng(<?php echo $loglat->SubLatitude;?>,<?php echo $loglat->SubLongitude;?>)
		  }
		  var map = new google.maps.Map(document.getElementById('map_canvas'),
										mapOptions);

		  setMarkers(map, beaches);

	}
	var infowindow = new google.maps.InfoWindow();
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
		
	'</span>'+
	'<ul style="margin: 0px; width: 160px;" class="callout_nav01">'+
		' <li style="margin: 0px;"><a href="'+beaches[key].url+'">Direction</a></li>'+
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


   window.onload = function()
                {
                 initialize();
					//alert(beaches.m0.title);
					  
				};
				$(document).ready(function(){
					$('.overlay').click(function(){
						$('.inq_form').hide();
						$(this).hide();
					});
				});
				
</script>
		<div id="map_canvas" style="width:50%;height:259px;"></div>
		</div>
		<div class="clear"></div>
	</div>
<?php endif;?>
</div>

<div class="inq_form">
<iframe id="Iframe1" width="495" height = "495" scrolling=no  frameborder=0 style="border: 0;" runat="server" 		 src="http://www.salessimplicity.net/livedemo/topo/default.aspx?Certificate=11d18320-0c2e-43a2-a22e-cbb7c3ec780a&SubdivisionNum=SFH-01&PhaseName=Phase 2&Zoom=.75&ShowLotStatus=yes&ShowLot=yes&ShowPhasePlan=yes&ShowLotSize=yes&ShowTotalPrice=yes&ShowStage=yes&ShowPremium=yes&ShowGarageOrient=yes"></iframe>
	<div class="request-form" style="display:none;">
		<div style="width: 620px; margin: 0px auto; padding: 15px;">
        
            <h3>Information Request Form</h3>
            
            <h5><span style="font-size: 19px;"><strong>Call:</strong> 800.761.4707</span> | <a href="mailto:info@loganhomes.com">Logan Homes</a></h5>
                                                    
   	     <h5>Fill out required fields below <span style="color:#990000;">*</span> then press the submit button</h5>
          
           			
            <form action="" method="post" name="form1" id="form1">           
            
            <div style="float: left; width: 250px; margin-right: 10px; margin-top: 10px; margin-bottom: 10px;">
            
            	                
                <input type="hidden" value="<?php echo $division->name ;?>" name="community">
				 <input type="hidden" value="<?php echo  $admin_email = get_option( 'admin_email' );?>" name="adminto">
              
            
                <p><label for="email">Name <span style="color:#990000;">*</span></label><br>
                <input type="text" style="width: 220px;" class="input" id="c_name" name="name"></p>
                
                <p><label for="email">Email Address: <span style="color:#990000;">*</span></label><br>
                <input type="text" style="width: 220px;" class="input" id="c_email" name="email"></p>
                
                <p><label for="email">Phone Number:</label><br>
                <input type="text" style="width: 220px;" class="input" id="c_phone" name="phone"></p>
                
            </div>
            
           
            
            <div style="float: left; width: 330px; margin-top: 10px; margin-bottom: 10px;">
            
                <p><label for="email">Comments / Questions</label><br>
                <textarea style="height: 150px; width: 300px;" class="textarea02" cols="45" rows="10" name="comments" id="c_comments"></textarea></p>
                
                <div class="clear"></div>
                            
                <input type="button" class="submit" style="margin-top: 5px;" value="Submit" id="submitit" name="submitit">
            
            </div>
            
            </form>
            
                        
            <script type="text/javascript">
				function validateForm2(frm)
				{
					if(!$("#c_name").val() || !$("#c_email").val())
					{
						alert("Please fill all required fields.");
						return false;	
					}
					
					return true;
				}
				 
				$(document).ready(function(){
					$('#submitit').click(function(){
					 if(validateForm2('form1') )
					 {
						 var val = $('#form1').serialize();
						  /* Send the data using post and put the results in a div */
						  $.ajax({
							  url: "<?php echo plugins_url().'/builder_design/admin/email.php'; ?>"+'?ajax=1',
							  type: "post",
							  data: val,
							  success: function(data){
							  var da=JSON.parse(data);
									//$('#result').html(data.status +':' + data.message);   
									if(da.status=='sent')
									{
										alert('Your Request has been sent successfully..!!!');
										$('.inq_form').hide();$('.overlay').hide();
										window.location="<?php echo 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];?>";
									}
									else{
										alert('Failed to sent your request.Please Try again later..!!');
										$('.inq_form').hide();$('.overlay').hide();
										window.location="<?php echo 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];?>";
									}
								        
							  }
							   
							});
					 }
				
					
					});
				}); 
				
			</script>
            
          
            <div class="clear"></div>
        
        </div>
	</div>
	<div class="clear"></div>
	<a class="close" onclick="$('.inq_form').hide();$('.overlay').hide();">X</a>
</div>
<div class="overlay"></div>
