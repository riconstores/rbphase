<?php 
 global $wpdb;
$riconllc=$wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_riconllc");
$riconllc=$riconllc[0];
$corp=$wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_corporations");
$corp=$corp[0];








if (class_exists('SoapClient')&& $riconllc->Elead_Certificate_ID!='') :
//TODO: Fill in your GUID
$guid  = $riconllc->Elead_Certificate_ID;//4CABF9DF-EB03-4CFE-B094-5F99DA9E2F10";

//TODO: Verify this URL
$client = new SoapClient($riconllc->Elead_Service_URI);

// This pulls a list of your Demographics
// We would really like you to store these demos Locally And grab a new set daily....
$result = $client->GetDemosXML(array('SubscriptionGUID' => $guid));

$xml = simplexml_load_string($result->GetDemosXMLResult, 'SimpleXMLElement', LIBXML_NOCDATA) or die ("Unable to load XML file!");



?>
<style>
.entry-title, .entry-header {
    display: none;
}</style>
	<div class="">
		<div>
        
            <h3>Information Request Form</h3>
            
          
                                                    
   	     <h5>Fill out required fields below <span style="color:#990000;">*</span> then press the submit button</h5>
          
           			
           <form name='Lead' id="form1" method='post'>        
            
            <div style="float: left; width: 50%; margin-right: 10px; margin-top: 10px; margin-bottom: 10px;">
            
            	<input type="hidden" value="<?php echo$riconllc->Elead_Certificate_ID ;?>" name="guid">
				<input type="hidden" value="<?php echo $riconllc->Elead_Service_URI ;?>" name="eleaduri">
               	<input type="hidden" value="" name="communitynumber">
					<input type="hidden" value="<?php echo $corp->c_name;?>" name="buildername">
				<input type="hidden" value="" name="divisionid">
				<input type="hidden" value="<?php echo $_SERVER['REMOTE_ADDR'] ;?>" name="ipaddress">
				<input type="hidden" value="<?php echo  $admin_email = get_option( 'admin_email' );?>" name="adminto">
              
            
                <p><label for="email">FirstName <span style="color:#990000;">*</span></label><br>
                <input type="text" style="width: 220px;" class="input" id="f_name" name="FirstName"></p>
                <p><label for="email">LastName <span style="color:#990000;">*</span></label><br>
                <input type="text" style="width: 220px;" class="input" id="l_name" name="LastName"></p>
                <p><label for="email">Email Address: <span style="color:#990000;">*</span></label><br>
                <input type="text" style="width: 220px;" class="input" id="c_email" name="Email"></p>
                
               <!-- <p><label for="email">How did you hear about us:</label><br>
                <?php
				/*<select name='Demo2'>
				<?php 
				foreach ($xml->xpath("//Table[SystemName='Demo1']/Description") as $desc) {
				print "<option value='$desc'>$desc</option>";
				}?>
				</select>
				*/
				?>
				</p> !-->
				<div class="demi">
                <?php	
			
					$data=json_decode(json_encode($xml->xpath("//Table")));
					//echo '<pre>';
					//print_r($data);
					$eleaddemos=(explode(",",$riconllc->demos));
					echo "<ul>";
					if(count($eleaddemos)>0)
					{
						$demos=array();
					foreach($data as $datas)
					{
					if(!in_array($datas->SystemName,$demos)&& in_array($datas->SystemName,$eleaddemos))
					{
					//echo "<pre>";
					//echo "<br>";
					echo "<li style='float:left;list-style:none;width:100%;'>";
					//echo '<input type="checkbox"  name="demo['.$datas->SystemName.']" rel='."$datas->SystemName".'  class="checkboxdemo '.$datas->SystemName.'" value="" '.$checked.'/>';
					//echo "show " . "$datas->SystemName";
					echo "$datas->Display ";
					echo "&nbsp&nbsp&nbsp";
					$conn=$xml->xpath("//Table[SystemName='$datas->SystemName']/Description");
				//print_r ($conn);
				 echo '<div id='."$datas->SystemName".' style="float:right;">';
					
					 echo '<select class="selectd" name="demos['."$datas->SystemName".']">';
							foreach($conn as $des)
						{
						echo "<option value='$des'>$des</option>";
						}
						echo "</select>";
						echo "</div>";
						echo "</li>";
					//echo $datas->SystemName;
					//echo '<hr>';
				    $demos[]=$datas->SystemName;
					
					}
					}
					
					
				
					//echo count(SystemName);
					//echo $datas->SystemName;
					//print_r($datas);
					//echo '<hr>';
					}
						
					echo "</ul>";
					?>
					<style type="text/css">
					.demi > div {
							float: right;
						    }
					</style>
						<!--<style type="text/css">
							.box{
								display: none;
								float:right;
								width:auto;
							}
						</style>
						<script type="text/javascript">
							$(document).ready(function(){
								$('input[type="checkbox"]').click(function(){
									if($(this).is(":checked"))
									{
									$("#"+$(this).attr("rel")).show();
									$(this).val($(this).attr("rel"));
									}
									else
									{  $("#"+$(this).attr("rel")).hide();
										$(this).val('');
									}
											
									
								});
								/**/
								$('.selectd').change(function(){
									//alert($(this).attr("sid"));
									//$("."+$(this).attr("sid")).val($);
								});
								
							});
							
							
						</script>!-->
						</div>
            </div>
            
           
            
            <div style="float: left; width: 40%; margin-top: 10px; margin-bottom: 10px;">
            
                <p><label for="email">Comments / Questions</label><br>
                <textarea style="height: 150px; width: 300px;" class="textarea02" cols="45" rows="10" name="Comments" id="c_comments"></textarea></p>
                
                <div class="clear"></div>
            
            </div>
			<div style="float:left; width:100%;">         
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

