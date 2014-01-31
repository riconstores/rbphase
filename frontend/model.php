<?php

if(isset($_GET['division']))
{
	
	include_once (dirname (__FILE__) . '/show_plans.php');

}
elseif(isset($_GET['model_detail']))
{
	include_once (dirname (__FILE__) . '/showmodeldetail.php');
}
else
{	
		
	include_once (dirname (__FILE__) . '/showmodel.php');
	
}





?>
<style>.entry-title,.entry-header{display:none;}
.entry-content:max-width: 699px !important;</style>