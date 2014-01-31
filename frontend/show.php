<?php

if(isset($_GET['plan_detail']))
{
	
	include_once ( dirname (__FILE__)  . '/plans/pdetail.php');

}

else
{	global $wpdb;
	echo '<h2 class="page_title">Available Plans</h2>';	
		
	 include(dirname (__FILE__) . '/plans/show_planlist.php');
}





?>
<style>.entry-title,.entry-header{display:none;}
.entry-content:max-width: 699px !important;</style>