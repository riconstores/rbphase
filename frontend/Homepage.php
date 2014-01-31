<?php
 global $wpdb;
$pageurls=$wpdb->get_results("SELECT ID,post_content,guid FROM {$wpdb->prefix}posts  WHERE post_status='publish' AND post_excerpt ='BuilderPhase'");
foreach($pageurls as $url)
{
	
	if($url->post_content=='[BuilderPhase]')
	{
		 $floorplanurl=$url->guid;
	}
	elseif($url->post_content=='[BuilderPhase_ReadyHomes]')
	{$readyhomeurl= $url->guid;}
	elseif($url->post_content=='[BuilderPhase_build]')
	{$buildhomeurl=$url->guid;}
	elseif($url->post_content=='[BuilderPhase_Model]')
	{$modelurl=$url->guid;}
	else{}
}




?>
    <script type="text/javascript">
    $(window).load(function() {
        $('#slider').nivoSlider();
    });
 </script>
 <style>.entry-header{display:none;}
 
 .nivo-slice{
 display:none !important;
 }
 .nivo-box{
  display:none !important;
 }</style>
 
<div id="home_slidewrapper_holder">
	  <div class="slider-wrapper theme-default">
		<div id="slider" class="nivoSlider">
		   <img src="<?php echo plugins_url('/css/slider/1.jpg', __FILE__);?>"  />
			<img src="<?php echo plugins_url('/css/slider/2.jpg', __FILE__);?>"  />
			<img src="<?php echo plugins_url('/css/slider/3.jpg', __FILE__);?>"  />
		    <img src="<?php echo plugins_url('/css/slider/4.jpg', __FILE__);?>"  />
			<img src="<?php echo plugins_url('/css/slider/5.jpg', __FILE__);?>"  />
		  <img src="<?php echo plugins_url('/css/slider/6.jpg', __FILE__);?>"  />
		 </div> 
	 </div>
</div>
<div id="home_content">
                            	
	<!-- start of home_content_left -->
	<div id="home_content_left">
		
		<!-- start of home_welcome -->
		<div id="home_welcome">
			
			<!--MODULE:copy01.php|CACHE:copy_home-->
						<div style="padding: = 0 px;" class="textbox"><h1><span>Welcome</span> to Builder Phase</h1>
<h2>Your North Carolina Home Builder</h2>
<p>At Logan Homes, we turn your dream home into a reality. From traditional to contemporary, grand to cozy, Logan Homes offers customizable floor plans sure&nbsp;to&nbsp;fit your unique style. Our quality craftsmanship, superior designs and decades of building experience make us the&nbsp;right builder for you.<br><br>Whether you want to live close to town or in the country, near the beach or on a golf course, the Builder Specialists at Logan Homes can help you find the ideal community or home site to suit your lifestyle perfectly. So dream as big as you want; we'd love to build you a Logan home!</p>
<p style="text-align: center;"><span style="font-size: medium;">&nbsp;</span></p>            </div>                                        
			<div class="clear"></div>
			
		</div><!-- end of home_welcome -->
		
		<div class="clear"></div>
		
		
	</div><!-- end of home_content_left -->
                                
	<!-- start of home_content_right -->
	<div id="home_content_right">
		<div id="home_btn_box">
			<div class="home_btn_box_mod01">
			<a href="<?php echo $modelurl;?>">
			<img width="141" height="265" alt="Model Homes" src="<?php echo plugins_url('/css/slider/btn_home01.png', __FILE__);?>">
			</a></div>
			<div class="home_btn_box_mod01">
			<a href="<?php echo $readyhomeurl;?>">
			<img width="140" height="265" alt="Move-In Ready Homes" src="<?php echo plugins_url('/css/slider/btn_home02.png', __FILE__);?>"></a></div>
			<div class="home_btn_box_mod01">
			<a href="<?php echo $buildhomeurl;?>">
			<img width="141" height="265" alt="View Homes By Map" src="<?php echo plugins_url('/css/slider/btn_home03.png', __FILE__);?>"></a></div>
			
		</div><!-- end of home_btn_box -->
		
		<div class="clear"></div>
		
	</div><!-- end of home_content_right -->
                                
	<div class="clear"></div>
	
</div>
<!--Start Request Elead Form-->

<div class="inq_form" >
<?php 
$riconllc=$wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_riconllc");
$riconllc=$riconllc[0];

if (class_exists('SoapClient')&& $riconllc->Elead_Certificate_ID!='') :
//TODO: Fill in your GUID
$guid  = $riconllc->Elead_Certificate_ID;//4CABF9DF-EB03-4CFE-B094-5F99DA9E2F10";

//TODO: Verify this URL
$client = new SoapClient($riconllc->Elead_Service_URI);

// This pulls a list of your Demographics
// We would really like you to store these demos Locally And grab a new set daily....
$result = $client->GetDemosXML(array('SubscriptionGUID' => $guid));

$xml = simplexml_load_string($result->GetDemosXMLResult, 'SimpleXMLElement', LIBXML_NOCDATA) or die ("Unable to load XML file!");



?><a class="close" style="cursor:pointer;" onclick="$('.inq_form').hide();$('.overlay').hide();">X</a>
	<div class="request-form">
		<div style="width: 620px; margin: 0px auto; padding: 15px;">
        
            <h3>Information Request Form</h3>
            
            <h5><span style="font-size: 19px;"><strong>Call:</strong> 800.761.4707</span> | <a href="mailto:<?php echo  $admin_email = get_option( 'admin_email' );?>">Builder Phase</a></h5>
                                                    
   	     <h5>Fill out required fields below <span style="color:#990000;">*</span> then press the submit button</h5>
          
           			
           <form name='Lead' id="form1" method='post'>        
            
            <div style="float: left; width: 250px; margin-right: 10px; margin-top: 10px; margin-bottom: 10px;">
            
            	<input type="hidden" value="<?php echo$riconllc->Elead_Certificate_ID ;?>" name="guid">
				<input type="hidden" value="<?php echo $riconllc->Elead_Service_URI ;?>" name="eleaduri">
                <input type="hidden" value="<?php echo $division->name ;?>" name="communityname">
				<input type="hidden" value="<?php echo $division->number ;?>" name="communitynumber">
				<input type="hidden" value="<?php echo $division->ID ;?>" name="divisionid">
				<input type="hidden" value="<?php echo $_SERVER['REMOTE_ADDR'] ;?>" name="ipaddress">
				<input type="hidden" value="<?php echo  $admin_email = get_option( 'admin_email' );?>" name="adminto">
              
            
                <p><label for="email">FirstName <span style="color:#990000;">*</span></label><br>
                <input type="text" style="width: 220px;" class="input" id="f_name" name="FirstName"></p>
                <p><label for="email">LastName <span style="color:#990000;">*</span></label><br>
                <input type="text" style="width: 220px;" class="input" id="l_name" name="LastName"></p>
                <p><label for="email">Email Address: <span style="color:#990000;">*</span></label><br>
                <input type="text" style="width: 220px;" class="input" id="c_email" name="Email"></p>
                
                <p><label for="email">How did you hear about us:</label><br>
                <select name='Demo1'>
				<?php foreach ($xml->xpath("//Table[SystemName='Demo1']/Description") as $desc) {
				print "<option value='$desc'>$desc</option>";
				}?>
				</select>
				
				</p>
                
            </div>
            
           
            
            <div style="float: left; width: 330px; margin-top: 10px; margin-bottom: 10px;">
            
                <p><label for="email">Comments / Questions</label><br>
                <textarea style="height: 150px; width: 300px;" class="textarea02" cols="45" rows="10" name="Comments" id="c_comments"></textarea></p>
                
                <div class="clear"></div>
                            
                <input type="button" class="submit" style="margin-top: 5px;" value="Submit" id="submitit" name="eleadSubmit">
            
            </div>
            
            </form>
            
                        
            <script type="text/javascript">
				function validateForm2(frm)
				{
					if(!$("#f_name").val() ||!$("#l_name").val() || !$("#c_email").val())
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
										alert(da.xml);
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
	<?php else:?>
		<div class="request-form" style="width:100%;height:100%;text-align:center;padding-top:50%;">SOAP NOT ENABLED</div>
	<?php endif;?>
	<div class="clear"></div>
	
</div>

