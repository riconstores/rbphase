<?php

/*
	Getting all the data from DataBase
	1. Corporations
	2. Builders
	3. Divisions
	4. Plans
	5. Specification
	
*/


class builder{
	
		// 1. Getting Corporations Array

		function Corporations()
		{
			global $wpdb;
			$Corporations = $wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_corporations");
			return $corporations;
		}

		//2. Getting Builders Array

		function Builders()
		{
			global $wpdb;
			$builders = $wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_builders");
			return $builders;
		}


		//3. Getting Divisions Array

		function Divisions()
		{
			global $wpdb;
			$divisons = $wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_divison");
			return $divisons;
		}

		//4. Getting Plans Array

		function Plans()
		{
			global $wpdb;
			$plans = $wpdb->get_results("SELECT *FROM {$wpdb->prefix}bd_plan ");
			return $plans;
		}


}









?>


