<?php
	if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

	// creates all tables for the plugin called during register_activation hook

	function builder_design_install_tables () {	
		global $wpdb;
		
		if ( !current_user_can('activate_plugins') ) return;
		
		
		
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		
			$sql = "CREATE TABLE {$wpdb->prefix}bd_builders (
					  `ID` int(11) NOT NULL AUTO_INCREMENT,
					  `corporat_id` int(255) NOT NULL,
					  `builder_number` varchar(255) NOT NULL,
					  `brand_name` varchar(255) NOT NULL,
					  `reporting_name` varchar(255) NOT NULL,
					  `lead_email` varchar(255) NOT NULL,
					  `Update_From_Xml` int(10) NOT NULL,
					  `Status` int(10) NOT NULL,
					  PRIMARY KEY (`ID`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
					CREATE TABLE {$wpdb->prefix}bd_corporations(
					  `ID` int(11) NOT NULL AUTO_INCREMENT,
					  `c_name` varchar(100) NOT NULL,
					  `c_number` varchar(100) NOT NULL,
					  `c_state` varchar(100) NOT NULL,
					  `c_email` varchar(150) NOT NULL,
					  `status` int(10) NOT NULL,
					  `Update_From_Xml` int(10) NOT NULL,
					  PRIMARY KEY (`ID`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
					
					CREATE TABLE {$wpdb->prefix}bd_divison (
					  `ID` int(11) NOT NULL AUTO_INCREMENT,
					  `corporat_id` int(11) NOT NULL,
					  `builder_number` varchar(255) NOT NULL,
					  `sqft_high` int(11) NOT NULL,
					  `sqft_low` int(11) NOT NULL,
					  `price_high` decimal(10,0) NOT NULL,
					  `price_low` decimal(10,0) NOT NULL,
					  `number` varchar(100) NOT NULL,
					  `name` varchar(255) NOT NULL,
					  `descr` text NOT NULL,
					  `build_lot` int(11) NOT NULL,
					  `sale_office_address` text NOT NULL,
					  `sub_address` text NOT NULL,
					  `driv_direction` text NOT NULL,
					  `phone` varchar(200) NOT NULL,
					  `image` text NOT NULL,
					  `status` varchar(20) NOT NULL,
					  `Update_From_Xml` int(10) NOT NULL,
					  `ShowTopo` int(10) NOT NULL,
					  PRIMARY KEY (`ID`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1;
					
					CREATE TABLE {$wpdb->prefix}bd_plan(
					  `ID` int(11) NOT NULL AUTO_INCREMENT,
					  `builder_id` int(11) NOT NULL,
					  `divison_id` int(11) NOT NULL,
					  `plan_name` varchar(255) NOT NULL,
					  `plan_type` varchar(50) NOT NULL,
					  `plan_number` varchar(30) NOT NULL,
					  `base_price` decimal(10,0) NOT NULL,
					  `base_sqft` int(11) NOT NULL,
					  `descr` text NOT NULL,
					  `stories` decimal(10,0) NOT NULL,
					  `bath` int(11) NOT NULL,
					  `bedrooms` int(11) NOT NULL,
					  `half_bath` int(11) NOT NULL,
					  `garage` varchar(200) NOT NULL,
					  `planimages` text NOT NULL,
					  `brochure_url` text NOT NULL,
					  `Update_From_Xml` int(10) NOT NULL,
					  `Status` int(10) NOT NULL,
					  PRIMARY KEY (`ID`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
					
					CREATE TABLE {$wpdb->prefix}bd_specification(
					  `ID` int(11) NOT NULL AUTO_INCREMENT,
					  `plan_id` int(11) NOT NULL,
					  `Attributes` varchar(100) NOT NULL,
					  `SpecIsModel` varchar(100) NOT NULL,
					  `SpecNumber` varchar(100) NOT NULL,
					  `SpecAddress` text NOT NULL,
					  `SpecPrice` int(10) NOT NULL,
					  `SpecStories` decimal(10,0) NOT NULL,
					  `SpecSqft` int(11) NOT NULL,
					  `SpecBaths` int(10) NOT NULL,
					  `SpecHalfBaths` int(11) NOT NULL,
					  `SpecBedrooms` int(11) NOT NULL,
					  `SpecVirtualTour` text NOT NULL,
					  `SpecImages` text NOT NULL,
					  `Options` int(10) NOT NULL,
					  `Update_From_Xml` int(10) NOT NULL,
					  `Status` int(10) NOT NULL,
					  PRIMARY KEY (`ID`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
					CREATE TABLE IF NOT EXISTS {$wpdb->prefix}bd_riconllc (
					  `ID` int(11) NOT NULL,
					  `Server_URL` varchar(255) NOT NULL,
					  `Topo_Certificate_ID` varchar(255) NOT NULL,
					  `Elead_Certificate_ID` varchar(255) NOT NULL,
					  `ShowLotStatus` varchar(255) NOT NULL,
					  `ShowLot` varchar(255) NOT NULL,
					  `ShowPhasePlan` varchar(255) NOT NULL,
					  `ShowLotSize` varchar(255) NOT NULL,
					  `ShowTotalPrice` varchar(255) NOT NULL,
					  `ShowStage` varchar(255) NOT NULL,
					  `ShowPremium` varchar(255) NOT NULL,
					  `ShowGarageOrient` varchar(255) NOT NULL,
					  `demos` varchar(255) NOT NULL,
					  `Elead_Service_URI` varchar(255) NOT NULL,
					  `Zoom` varchar(255) NOT NULL,
					  `DemoDivisionNumber` varchar(255) NOT NULL,
					  PRIMARY KEY (`ID`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1;
					CREATE TABLE {$wpdb->prefix}bd_specification_options (
					  `ID` int(11) NOT NULL AUTO_INCREMENT,
					  `Specification_id` int(11) NOT NULL,
					  `OptionCode` varchar(50) NOT NULL,
					  `OptionDesc` text NOT NULL,
					  `OptionGroupName` varchar(200) NOT NULL,
					  `Qty` int(10) NOT NULL,
					  `Price` int(10) NOT NULL,
					  `BuilderApproved` enum('0','1') NOT NULL,
					  `CustomerApproved` enum('0','1') NOT NULL,
					  `CustomerDesc` text NOT NULL,
					  `Update_From_Xml` int(10) NOT NULL,
					  PRIMARY KEY (`ID`)
					) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
					";
					dbDelta($sql);
					update_option( "builder_design_db_version", $dbversion );
		

		$options = get_option('builder_design_options');
		// set the default settings, if we didn't upgrade
		if ( empty( $options ) ) builder_design_default_options();


	}

	/**
	 * Setup the default option array for the plugin
	 * 
	 * @access internal
	 * @return void
	 */
	function builder_design_default_options() {

		$builder_design_options['access_to_tests']		= "corporation";  		// public or members 
		$builder_design_options['show_test_by']			= "builders";		// question or all	
		update_option('builder_design_options', $builder_design_options);
	}

	?>