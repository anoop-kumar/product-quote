<?php
global $wpdb;
if(!function_exists("pq_install")){

	function pq_install(){
		
		global $wpdb;
		
		$sql = "CREATE TABLE IF NOT EXISTS `".ORDER_TRANSACTION_TABLE."` (
	  `trans_id` bigint(20) NOT NULL auto_increment,  
	  `user_id` bigint(20) NOT NULL,
	  `post_id` bigint(20) NOT NULL,
	  `post_title` varchar(255) NOT NULL,
	  `quantity` varchar(255) NOT NULL,
	  `email_me` int(2) NOT NULL,
	  `status` int(2) NOT NULL,
	  `payment_method` varchar(255) NOT NULL,
	  `payable_amt` float(25,5) NOT NULL,
	  `payment_date` datetime NOT NULL,
	  `payment_transection_id` varchar(255) NOT NULL,
	  `user_name` varchar(255) NOT NULL,
	  `pay_email` varchar(255) NOT NULL,
	  `billing_name` varchar(255) NOT NULL,
	  `billing_phone` varchar(255) NOT NULL,
	  `billing_telephone` varchar(255) NOT NULL,
	  `billing_address` text NOT NULL,
	  `billing_city` varchar(255) NOT NULL,
	  `billing_state` varchar(255) NOT NULL,
	  `billing_zipcode` varchar(255) NOT NULL,  
	  `shipping_status` varchar(255) NOT NULL,
	  `delivered_on` datetime NOT NULL,
	  PRIMARY KEY  (`trans_id`)
	)";
	
	$wpdb->query($sql);	
	}
}

if(!function_exists("pq_deactivate")){

	function pq_deactivate(){
		require_once( ABSPATH . 'wp-load.php' );
		global $wpdb;
		$sql = "DROP TABLE IF EXISTS `".ORDER_TRANSACTION_TABLE."`;";	
		$wpdb->query($sql);	
	}
}
?>