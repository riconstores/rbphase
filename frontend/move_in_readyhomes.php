<?php

if(isset($_GET['division']))
{
	
	include_once (dirname (__FILE__) . '/show_plans.php');

}
elseif(isset($_GET['showreadyhomesdetail']))
{
	include_once (dirname (__FILE__) . '/showreadyhomesdetail.php');
}
elseif(isset($_GET['readyhomes-list']))
{
	include_once (dirname (__FILE__). '/show_readyhomes_list.php');
}
else
{	
		include_once (dirname (__FILE__) . '/show_readyhomes.php');
	
	
}





?>
<style>.entry-title,.entry-header{display:none;}
.entry-content:max-width: 699px !important;</style>