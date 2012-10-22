<?php

add_action( 'init', 'pg_create_post_type' );

function pg_create_post_type()
{
//===============EVENT SECTION START================
$custom_post_type = CUSTOM_POST_TYPE1;
$custom_cat_type = CUSTOM_CATEGORY_TYPE1;
$custom_tag_type = CUSTOM_TAG_TYPE1;

register_post_type(	"$custom_post_type", 
				array(	'label' 			=> CUSTOM_MENU_TITLE,
						'labels' 			=> array(	'name' 					=> 	CUSTOM_MENU_NAME,
														'singular_name' 		=> 	CUSTOM_MENU_SIGULAR_NAME,
														'add_new' 				=>  CUSTOM_MENU_ADD_NEW,
														'add_new_item' 			=>  CUSTOM_MENU_ADD_NEW_ITEM,
														'edit' 					=>  CUSTOM_MENU_EDIT,
														'edit_item' 			=>  CUSTOM_MENU_EDIT_ITEM,
														'new_item' 				=>  CUSTOM_MENU_NEW,
														'view_item'				=>  CUSTOM_MENU_VIEW,
														'search_items' 			=>  CUSTOM_MENU_SEARCH,
														'not_found' 			=>  CUSTOM_MENU_NOT_FOUND,
														'not_found_in_trash' 	=>  CUSTOM_MENU_NOT_FOUND_TRASH	),
						'public' 			=> true,
						'can_export'		=> true,
						'show_ui' 			=> true, // UI in admin panel
						'_builtin' 			=> false, // It's a custom post type, not built in
						'_edit_link' 		=> 'post.php?post=%d',
						'capability_type' 	=> 'post',
						'menu_icon' 		=> PQ_PLUGIN_URL.'/images/book.png',
						'hierarchical' 		=> false,
						'rewrite' 			=> array("slug" => "$custom_post_type"), // Permalinks
						'query_var' 		=> "$custom_post_type", // This goes to the WP_Query schema
						'supports' 			=> array(	'title',
														//'author', 
														'excerpt',
														'thumbnail',
														'comments',
														'editor', 
														//'trackbacks',
														//'custom-fields',
														'revisions') ,
						'show_in_nav_menus'	=> true ,
						//'show_in_menu' =>'ddb_wp_wp_admin_menu',
						'taxonomies'		=> array("$custom_cat_type","$custom_tag_type")
					)
				);

// Register custom taxonomy
register_taxonomy(	"$custom_cat_type", 
				array(	"$custom_post_type"	), 
				array (	"hierarchical" 		=> false, 
						"label" 			=> CUSTOM_MENU_CAT_LABEL, 
						'labels' 			=> array(	'name' 				=>  CUSTOM_MENU_CAT_TITLE,
														'singular_name' 	=>  CUSTOM_MENU_SIGULAR_CAT,
														'search_items' 		=>  CUSTOM_MENU_CAT_SEARCH,
														'popular_items' 	=>  CUSTOM_MENU_CAT_SEARCH,
														'all_items' 		=>  CUSTOM_MENU_CAT_ALL,
														'parent_item' 		=>  CUSTOM_MENU_CAT_PARENT,
														'parent_item_colon' =>  CUSTOM_MENU_CAT_PARENT_COL,
														'edit_item' 		=>  CUSTOM_MENU_CAT_EDIT,
														'update_item'		=>  CUSTOM_MENU_CAT_UPDATE,
														'add_new_item' 		=>  CUSTOM_MENU_CAT_ADDNEW,
														'new_item_name' 	=>  CUSTOM_MENU_CAT_NEW_NAME,	), 
						'public' 			=> true,
						'show_ui' 			=> true,
						'show_in_nav_menus' => true,
						'show_in_menu' =>'ddb_wp_wp_admin_menu',
						"rewrite" 			=> true	)
				);
register_taxonomy(	"$custom_tag_type", 
				array(	"$custom_post_type"	), 
				array(	"hierarchical" 		=> false, 
						"label" 			=> CUSTOM_MENU_TAG_LABEL, 
						'labels' 			=> array(	'name' 				=>  CUSTOM_MENU_TAG_TITLE,
														'singular_name' 	=>  CUSTOM_MENU_TAG_NAME,
														'search_items' 		=>  CUSTOM_MENU_TAG_SEARCH,
														'popular_items' 	=>  CUSTOM_MENU_TAG_POPULAR,
														'all_items' 		=>  CUSTOM_MENU_TAG_ALL,
														'parent_item' 		=>  CUSTOM_MENU_TAG_PARENT,
														'parent_item_colon' =>  CUSTOM_MENU_TAG_PARENT_COL,
														'edit_item' 		=>  CUSTOM_MENU_TAG_EDIT,
														'update_item'		=>  CUSTOM_MENU_TAG_UPDATE,
														'add_new_item' 		=>  CUSTOM_MENU_TAG_ADD_NEW,
														'new_item_name' 	=>  CUSTOM_MENU_TAG_NEW_ADD,	),  
						'public' 			=> true,
						'show_in_menu' =>'ddb_wp_wp_admin_menu',
						'show_ui' 			=> true,
						"rewrite" 			=> true	)
				);
}

/////The filter code to get the custom post type in the RSS feed
function myfeed_request($qv) {
	if (isset($qv['feed']))
		$qv['post_type'] = get_post_types();
	return $qv;
}
add_filter('request', 'myfeed_request');

add_filter( 'manage_edit-'.CUSTOM_POST_TYPE1.'_columns', 'pq_wp_edit_columns' ) ;

function pq_wp_edit_columns( $columns ) {
		
	$columns = array(
		'cb' => '<input type="checkbox" />',
		'title' => __( CUSTOM_MENU_NAME ),		
		'price' => __( 'Price (Rs)' ),
		'book_sold' => __( CUSTOM_MENU_NAME.' sold' ),
		'total_item' => __( 'Total items' ),
		'status' => __( 'Status' ),
		'post_category' => __( CUSTOM_MENU_CAT_TITLE ),
		'post_tags' => __( 'Tags' )
	);

	return $columns;
}

add_action( 'manage_'.CUSTOM_POST_TYPE1.'_posts_custom_column', 'pq_wp_manage_columns', 10, 2 );

function pq_wp_manage_columns( $column, $post_id ) {
	
	echo '<link href="'.PQ_PLUGIN_URL.'/css/admin.css" rel="stylesheet" type="text/css" />';
	global $post;

	switch( $column ) {

		/* If displaying the 'status' column. */
		case 'status' :

			/* Get the post meta. */
			$status = fetch_status(get_post_meta( $post_id, 'status', true ),get_post_meta( $post_id, 'stock', true ));

			/* If no status is found, output a default message. */
			 _e($status,'pq_wp');

			break;

		/* If displaying the 'coupon_start_date_time' column. */
		case 'price' :

			/* Get the price. */
			$price = get_post_meta( $post_id, 'price', true );

			_e($price,'pq_wp');

			break;
		
		case 'book_sold' :

			/* Get the deal_sold for the post. */
			//$deal_sold = deal_transaction($post_id);

			/* If deal_sold were found. */
			//if ( !empty( $deal_sold ) ) {
				//echo $deal_sold;
			//}

			/* If no deal_sold were found, output a default message. */
			//else {
				_e( '0' );
			//}

			break;
		case 'post_category' :
			/* Get the post_category for the post. */
			
			$category = get_the_taxonomies($post);							
			$category_display = str_replace(CUSTOM_MENU_CAT_TITLE.':','',$category[CUSTOM_CATEGORY_TYPE1]);
			$category_display = str_replace(' and ',', ',$category_display);
			echo $category_display = str_replace(',,',', ',$category_display);		

			break;
			
		case 'post_tags' :
			/* Get the post_tags for the post. */
				$tags = get_the_taxonomies($post);
				$tags_display = str_replace(CUSTOM_MENU_TAG_TITLE.':','',$tags['seller_tags']);
				$tags_display = str_replace(' and ',', ',$tags_display);
				echo $tags_display = str_replace(',,',', ',$tags_display);
			break;

		case 'total_item' :

			/* Get the total_item for the post. */
			$total_item = get_post_meta( $post_id, 'no_of_item', true );

			/* If terms were found. */
			if ( !empty( $total_item ) ) {
				echo $total_item;
			}

			/* If no terms were found, output a default message. */
			else {
				_e( '0' );
			}

			break;

		/* Just break out of the switch statement for everything else. */
		default :
			break;
	}
}

add_filter( 'manage_edit-'.CUSTOM_POST_TYPE1.'_sortable_columns', 'pq_wp_sortable_columns' );

function pq_wp_sortable_columns( $columns ) {	
		
	$columns['price'] = 'price';	
	$columns['book_sold'] = 'book_sold';
	$columns['total_item'] = 'total_item';
	$columns['status'] = 'status';
	$columns['post_category'] = CUSTOM_MENU_CAT_TITLE;
	return $columns;
}

#####################  Meta Box Option  ########################
add_action("admin_init", "admin_init");
	
add_action("save_post", "save_deal");

	function admin_init(){
		
		//Price Display
		add_meta_box(CUSTOM_POST_TYPE1."-meta-price", CUSTOM_MENU_NAME." Price", "pq_meta_options_price", CUSTOM_POST_TYPE1, "side", "high");
		
		//Attribute
		add_meta_box(CUSTOM_POST_TYPE1."-meta-attribute", CUSTOM_MENU_NAME." Attributes", "pq_meta_options_attributes", CUSTOM_POST_TYPE1, "normal", "low");
		//Images
		add_meta_box(CUSTOM_POST_TYPE1."-meta-pdt-images", CUSTOM_MENU_NAME." Images", "pq_meta_options_images", CUSTOM_POST_TYPE1, "normal", "high");
		//Shipping
		add_meta_box(CUSTOM_POST_TYPE1."-meta-shipping", CUSTOM_MENU_NAME." Shipping", "pq_meta_options_shipping", CUSTOM_POST_TYPE1, "normal", "low");
		//Addition Info
		add_meta_box(CUSTOM_POST_TYPE1."-meta-additional", CUSTOM_MENU_NAME." Additional Info", "pq_meta_options_additional", CUSTOM_POST_TYPE1, "normal", "low");
		//SEO Settings
		add_meta_box(CUSTOM_POST_TYPE1."-meta-seo", CUSTOM_MENU_NAME." SEO", "pq_meta_options_seo", CUSTOM_POST_TYPE1, "normal", "low");
		
		
	}
	
	//Attribute
	function pq_meta_options_attributes(){		
		global $post;	
		$custom = get_post_custom($post->ID);				
		require_once(PQ_ROOT_PATH."view/admin_product_attribute.php");		
	}
	//Images
	function pq_meta_options_images(){		
		global $post;	
		$custom = get_post_custom($post->ID);				
		require_once(PQ_ROOT_PATH."view/admin_product_images.php");		
	}
	
	//Shipping
	function pq_meta_options_shipping(){		
		global $post;	
		$custom = get_post_custom($post->ID);				
		require_once(PQ_ROOT_PATH."view/admin_product_shipping.php");		
	}
	//Additional Info
	function pq_meta_options_additional(){		
		global $post;	
		$custom = get_post_custom($post->ID);				
		require_once(PQ_ROOT_PATH."view/admin_product_options.php");		
	}
	
	//SEO Settings
	function pq_meta_options_seo(){		
		global $post;	
		$custom = get_post_custom($post->ID);				
		require_once(PQ_ROOT_PATH."view/admin_product_seo.php");		
	}
	
	//Price Options
	function pq_meta_options_price(){		
		global $post;	
		$custom = get_post_custom($post->ID);				
		require_once(PQ_ROOT_PATH."view/admin_product_price.php");		
	}
function save_deal($post_id){
		
	global $post;
	
		if ( !wp_verify_nonce( $_POST['mytheme_meta_box_nonce'], 'deal_panel' )) {
		return $post_id;
		}
	
	if($_POST["currency"]!="")
		update_post_meta($post->ID, "currency", $_POST["currency"]);
	else
		delete_post_meta($post->ID, "currency", $_POST["currency"]);
			
	$uploadpath = wp_upload_dir(); 
	$tmpfolder = $uploadpath['baseurl'].'/tmp/';
	$imgstr = '';
	if($_POST["file_info"] != ''){
	foreach($_POST["file_info"] as $key=>$val)
	{
		$imagepath =  $tmpfolder.$key.'.jpg';	
		$imgstr .= '<a href="'.$imagepath.'" target="_blank" ><img src="'.$imagepath.'" height="100" width="100" alt="" /></a> &nbsp; &nbsp;';
	}}
	$user_fname = $_POST['owner_name'];
	$user_email = $_POST['owner_email'];
	$deal_title = '<a href="'.$_POST['link_url'].'">'.$_POST['post_title'].'</a>';
	$deal_desc = $_POST['post_content'];
	
	$coupon_website = $_POST['coupon_website'];
	$no_of_coupon = $_POST['no_of_coupon'];
	$our_price = $_POST['our_price'];
	$current_price =$_POST['current_price'];
	$coupon_type = $_POST['coupon_type'];
	if($_POST['coupon_entry'] == 'coupon_entry_0') { 
	$single_coupon_code =$_POST['coupon_code1'];
	}else{
	$coupon_code= $_POST['coupon_code'];
	}
	//echo  $coupon_code= $_POST['coupon_code'];

	$coupon_address = $_POST['coupon_address'];
	$coupon_link = $_POST['coupon_link'];
	$mediafilename = $_POST['media_file'];
	if($coupon_type=='1')
	   $coupon_type='Custom Link Deal';
	 elseif($coupon_type=='2')
	   $coupon_type='Fixed Deal';
	 elseif($coupon_type=='3')
	   $coupon_type='Custom Generated Deal';
	 elseif($coupon_type=='4')
	   $coupon_type='Physical Barcode Deal';
	 elseif($coupon_type=='5')
	$coupon_type='Physical Product Deal';
	
	// Start All Transection Details With Deal
	if($coupon_type=='4' || $coupon_type=='5')
	{
		if($coupon_address!="")	{
			$transaction_details = sprintf(__("
			<p><h3>Deal Details</h3> \r</p>
			<p><strong>Deal Title: </strong>%s \r	</p>
			<p><strong>Deal Desc: </strong>%s \r	</p>
			<p><strong>Deal Type: </strong>%s \r	</p>
			<p><strong>Original Price: </strong>%s \r	</p>
			<p><strong>Current Price: </strong>%s \r	</p>
			<p><strong>Store Address:</strong> %s \r	</p>
			",'ddb_wp'),$deal_title,$deal_desc,$coupon_code,get_post_currency_sym($post_id).$current_price,get_post_currency_sym($post_id).$our_price,$current_price,$coupon_address);
		} else	{
			$transaction_details = sprintf(__("
			<p><h3>Deal Details </h3> \r</p>
			<p><strong>Deal Title:</strong> %s \r</p>
			<p><strong>Deal Desc:</strong> %s \r	</p>
			<p><strong>Deal Type: </strong>%s \r	</p>
			<p><strong>Original Price: </strong>%s \r	</p>
			<p><strong>Current Price: </strong>%s \r	</p>
			",'ddb_wp'),$deal_title,$deal_desc,$coupon_code,$coupon_type,get_post_currency_sym($post_id).$current_price,get_post_currency_sym($post_id).$our_price);
		}
	}	else {
		 
		$transaction_details = sprintf(__("
		<p><h3>Deal Details </h3>\r</p>
		<p><strong>Deal Title:</strong> %s \r</p>
		<p><strong>Deal Coupon:</strong> %s \r	</p>
		<p><strong>Deal Type:</strong> %s \r	</p>
		<p><strong>Original Price: </strong>%s \r	</p>
		<p><strong>Current Price: </strong>%s \r	</p>
		",'ddb_wp'),$deal_title,$single_coupon_code,$coupon_type,get_post_currency_sym($post_id).$current_price,get_post_currency_sym($post_id).$our_price);
	}
	// End All Transection Details With Deal	
	$fromEmail = get_site_emailId();
	$fromEmailName = get_site_emailName();
	$store_name = get_option('blogname');
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
         return $post_id;
	if($_POST["status"]!="") {
	global $post;
		$pre = get_post_meta($post->ID,'status',true);
		if($pre != $_POST["status"]) 
		{
			update_post_meta($post->ID, "status", $_POST["status"]);
				if($_POST["status"] == '1' || $_POST["status"] == '2'){
					$client_message = get_option('req_accept_success_email_content');
					$users_details = sprintf(__("
						<p><h3>Seller Details</h3> \r</p>
						<p><strong>Name:</strong> %s \r	</p>
						<p><strong>Email:</strong> %s \r	</p>",'ddb_wp'),$user_fname,$user_email);
					$subject = get_option('req_accept_success_email_subject');
					$search_array = array('[#to_name#]','[#site_name#]','[#deal_details#]','[#seller_details#]');
					$replace_array = array($user_fname.",",$store_name,$transaction_details,$users_details);
					$email_seller_content = str_replace($search_array,$replace_array,$client_message);	
					if(get_option('pttthemes_send_mail') == 'Enable' || get_option('pttthemes_send_mail') == '') {
						DDBWP_sendEmail($fromEmail,$fromEmailName,$user_email,$user_fname,$subject,$email_seller_content,$extra='');
					}
				}  else if($_POST["status"] == '3' ) {
					$fromEmail = get_site_emailId();
					$fromEmailName = get_site_emailName();
					$store_name = get_option('blogname');
					$client_message = get_option('req_reject_success_email_content');
					$subject = get_option('req_reject_success_email_subject');
					$search_array = array('[#to_name#]','[#site_name#]');
					$replace_array = array($user_fname.",",$store_name);

					$email_seller_content = str_replace($search_array,$replace_array,$client_message);	
					if(get_option('pttthemes_send_mail') == 'Enable' || get_option('pttthemes_send_mail') == '') {
						DDBWP_sendEmail($fromEmail,$fromEmailName,$user_email,$userName,$subject,$email_seller_content,$extra='');
					}
				} else {
					// Do nothing
				}
		}
	}
	else
	delete_post_meta($post->ID, "status", $_POST["status"]);
	
	if($_POST["owner_name"]!="")
	update_post_meta($post->ID, "owner_name", $_POST["owner_name"]);
	else
	delete_post_meta($post->ID, "owner_name", $_POST["owner_name"]);
	if($_POST["_wp_attached_file"]!="")
	update_post_meta($post->ID, "_wp_attached_file", $_POST["_wp_attached_file"]);
	else
	delete_post_meta($post->ID, "_wp_attached_file", $_POST["_wp_attached_file"]);
	
	if($_POST["owner_email"]!="")
	update_post_meta($post->ID, "owner_email", $_POST["owner_email"]);
	else
	delete_post_meta($post->ID, "owner_email", $_POST["owner_email"]);

	
	if($_POST["thankyou_page_url"]!="")
	update_post_meta($post->ID, "thankyou_page_url", $_POST["thankyou_page_url"]);
	else
	delete_post_meta($post->ID, "thankyou_page_url", $_POST["thankyou_page_url"]);
	
	if($_POST["coupon_website"]!="")
	update_post_meta($post->ID, "coupon_website", $_POST["coupon_website"]);
	else
	delete_post_meta($post->ID, "coupon_website", $_POST["coupon_website"]);
	
	if($_POST["no_of_coupon"]!="")
	update_post_meta($post->ID, "no_of_coupon", $_POST["no_of_coupon"]);
	else
	delete_post_meta($post->ID, "no_of_coupon", $_POST["no_of_coupon"]);
	
	if($_POST["coupon_type"]!="")
	update_post_meta($post->ID, "coupon_type", $_POST["coupon_type"]);
	else
	delete_post_meta($post->ID, "coupon_type", $_POST["coupon_type"]);
	
	if($_POST["coupon_entry"]!="")
	{
	update_post_meta($post->ID, "coupon_entry", $_POST["coupon_entry"]);
	if($_POST["coupon_entry"]== "coupon_entry_0")
	{
		update_post_meta($post->ID, "coupon_code", $_POST["coupon_code1"]);
	}else{
		update_post_meta($post->ID, "coupon_code", $_POST["coupon_code"]);
	}
	}
	else{
	delete_post_meta($post->ID, "coupon_entry", $_POST["coupon_entry"]);
	delete_post_meta($post->ID, "coupon_code", $_POST["coupon_code"]);
	}
	
	if($_POST["min_purchases"]!="")
	update_post_meta($post->ID, "min_purchases", $_POST["min_purchases"]);
	else
	delete_post_meta($post->ID, "min_purchases", $_POST["min_purchases"]);
	
	
	if($_POST["max_purchases_user"]!="")
	update_post_meta($post->ID, "max_purchases_user", $_POST["max_purchases_user"]);
	else
	delete_post_meta($post->ID, "max_purchases_user", $_POST["max_purchases_user"]);
	
	if($_POST["shhiping_address"]!="")
	update_post_meta($post->ID, "shhiping_address", $_POST["shhiping_address"]);
	else
	delete_post_meta($post->ID, "shhiping_address", $_POST["shhiping_address"]);
	
	if($_POST["shipping_cost"]!="")
	update_post_meta($post->ID, "shipping_cost", $_POST["shipping_cost"]);
	else
	delete_post_meta($post->ID, "shipping_cost", $_POST["shipping_cost"]);

	if($_POST["geo_latitude"]!="")
	update_post_meta($post->ID, "geo_latitude", $_POST["geo_latitude"]);
	else
	delete_post_meta($post->ID, "geo_latitude", $_POST["geo_latitude"]);
	
	if($_POST["geo_longitude"]!="")
	update_post_meta($post->ID, "geo_longitude", $_POST["geo_longitude"]);
	else
	delete_post_meta($post->ID, "geo_longitude", $_POST["geo_longitude"]);

	
	if($_POST["coupon_type"]=="3" || $_POST["coupon_type"]=="4")
	{
		
		if($_POST["coupon_code"]=="")
		{
			$sys_gen_coupon = '';
			if($single_coupon_code == '')
			{
			for($c=0;$c<$_POST["no_of_coupon"];$c++)
			{   
			    $user_coup = wp_generate_password(3,false);
				$sys_gen_coupon.=$post->ID.$user_coup.",";
			}
			$sys_gen_coupon = trim($sys_gen_coupon,",");
			}
			else
			{
				$sys_gen_coupon = $single_coupon_code;
			}
			update_post_meta($post->ID, "coupon_code", $sys_gen_coupon);
		}else{
			$totcop = $_POST["coupon_code"];
			if($_POST["used_coupon_code"]!="")
			$totcop .= ",".$_POST["used_coupon_code"];
			$totalcpoarr = explode(",",$totcop);
			if(sizeof($totalcpoarr)==$_POST["no_of_coupon"]){
			}elseif($_POST["no_of_coupon"]>sizeof($totalcpoarr))
			{
				$totorigcop = $_POST["coupon_code"];
				$extra_coupon=  $_POST["no_of_coupon"] - sizeof($totalcpoarr);
				for($c=0;$c<$extra_coupon;$c++)
				{   
					$user_coup = wp_generate_password(3,false);
					$sys_gen_coupon.=$post->ID.$user_coup.",";
				}
				$sys_gen_coupon = trim($sys_gen_coupon,",");
				$addextra = $totorigcop.",".$sys_gen_coupon; 
				update_post_meta($post->ID, "coupon_code", $addextra);
			}
			
			
		}
	}
	if($_POST["used_coupon_code"]!="")
	update_post_meta($post->ID, "used_coupon_code", $_POST["used_coupon_code"]);
	else
	delete_post_meta($post->ID, "used_coupon_code", $_POST["used_coupon_code"]);
	
	
	if($_POST["coupon_address"]!="")
	update_post_meta($post->ID, "coupon_address", $_POST["coupon_address"]);
	else
	delete_post_meta($post->ID, "coupon_address", $_POST["coupon_address"]);
	
	
	if($_POST["coupon_link"]!="")
	update_post_meta($post->ID, "coupon_link", $_POST["coupon_link"]);
	else
	delete_post_meta($post->ID, "coupon_link", $_POST["coupon_link"]);
	
	//Uploading Design
	if($_POST["file_name"][0] != "")
		update_post_meta($post->ID, "file_name", $_POST["file_name"][0]);
	else
		delete_post_meta($post->ID, "file_name", $_POST["file_name"][0]);
	
	if($_POST["file_name"][1] != "")
		update_post_meta($post->ID, "file_name1", $_POST["file_name"][1]);
	else
		delete_post_meta($post->ID, "file_name1", $_POST["file_name"][1]);
	
	if($_POST["file_name"][2] != "")
		update_post_meta($post->ID, "file_name2", $_POST["file_name"][2]);
	else
		delete_post_meta($post->ID, "file_name2", $_POST["file_name"][2]);
	
	
	if($_POST["coupon_start_date"]!="" && $_POST["coupon_start_time_hh"]!="" && $_POST["coupon_start_time_mm"]!="" && $_POST["coupon_start_time_ss"]!=""){
		//echo $_POST["coupon_start_date"]." ".$_POST["coupon_start_time_hh"].":".$_POST["coupon_start_time_mm"].":".$_POST["coupon_start_time_ss"];
	$coupon_start_date_time=strtotime($_POST["coupon_start_date"]." ".$_POST["coupon_start_time_hh"].":".$_POST["coupon_start_time_mm"].":".$_POST["coupon_start_time_ss"]);	
	update_post_meta($post->ID, "coupon_start_date_time", $coupon_start_date_time);
	}else{
	delete_post_meta($post->ID, "coupon_start_date_time", $coupon_start_date_time);}
	if($_POST["enddate"]!="" || isset($_POST["enddate"]) )
	{
		update_post_meta($post->ID, "coupon_end_date_time",'');
		update_post_meta($post->ID, "enddate", '0');
		if(get_post_meta($post->ID, 'is_expired' , true) != '0') 
			{
				update_post_meta($post->ID, "is_expired", '0');
			}
	}else{
		if($_POST["coupon_end_date"]!="" && $_POST["coupon_end_time_hh"]!="" && $_POST["coupon_end_time_mm"]!="" && $_POST["coupon_end_time_ss"]!=""){
			$_POST["coupon_end_date"]." ".$_POST["coupon_end_time_hh"].":".$_POST["coupon_end_time_mm"].":".$_POST["coupon_end_time_ss"];
			$coupon_end_date_time=strtotime($_POST["coupon_end_date"]." ".$_POST["coupon_end_time_hh"].":".$_POST["coupon_end_time_mm"].":".$_POST["coupon_end_time_ss"]);	
			update_post_meta($post->ID, "coupon_end_date_time", $coupon_end_date_time);
			if(get_post_meta($post->ID, 'is_expired' , true) != '0') 
			{
				update_post_meta($post->ID, "is_expired", '0');
			}
		}else{
			update_post_meta($post->ID, "coupon_end_date_time", "");
		}
			update_post_meta($post->ID, "enddate", '');
	}
	//update_post_meta($post->ID, "is_expired", $_POST["is_expired"]);
	
	if($_POST["is_expired"] != "")
	{
	//update_post_meta($post->ID, "is_expired", $_POST["is_expired"]);
	}
	else
	{
	//update_post_meta($post->ID, "is_expired", '0');
	}	
	if($_POST["our_price"]!="")
	update_post_meta($post->ID, "our_price", $_POST["our_price"]);
	else
	delete_post_meta($post->ID, "our_price", $_POST["our_price"]);
	
	
	if($_POST["current_price"]!="")
	update_post_meta($post->ID, "current_price", $_POST["current_price"]);
	else
	delete_post_meta($post->ID, "current_price", $_POST["current_price"]);
	
	
	
	global $post, $meta_boxes, $key;

	if ( !wp_verify_nonce( $_POST[ $key . '_wpnonce' ], plugin_basename(__FILE__) ) )
	return $post_id;

	if ( !current_user_can( 'edit_post', $post_id ))
	return $post_id;

	foreach( $meta_boxes as $meta_box ) {
	update_post_meta( $post_id, $meta_box[ 'name' ], $_POST[ $meta_box[ 'name' ] ] );
	}
	
	
}
?>