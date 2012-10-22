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
		add_meta_box(CUSTOM_POST_TYPE1."-meta", CUSTOM_MENU_NAME." Options", "pq_meta_options", CUSTOM_POST_TYPE1, "normal", "high");
		
	}
	
	function pq_meta_options(){
		
		global $post;
	
		$custom = get_post_custom($post->ID);
		
		//echo "<pre>";print_r($custom); die;
		$owner_name = $custom["owner_name"][0];
		$owner_email = $custom["owner_email"][0];
		$coupon_website = $custom["coupon_website"][0];
		$coupon_type = $custom["coupon_type"][0];
		$coupon_code = $custom["coupon_code"][0];
		$used_coupon_code = $custom["used_coupon_code"][0];
		$coupon_link = $custom["coupon_link"][0]; 
		if($custom["coupon_entry"][0] == 'coupon_entry_0')
		{		
			$single_coupon_code = $custom["coupon_code"][0];
		}else{
		   $coupon_code = $custom["coupon_code"][0];
		
		}
		$is_expired = $custom["is_expired"][0];
		if($is_expired=='')
		$is_expired=0;
		$status = $custom["status"][0];
		if($status =='')
		$status=0;
		$our_price = $custom["our_price"][0];
		$current_price = $custom["current_price"][0];
		
		$currency_format = get_option('ddbwpthemes_default_currency');
		list($currency_code, $currency_name) = explode('-',$currency_format);
			
		$currency = isset($custom["currency"][0])?$custom["currency"][0]:$currency_code;
		
		$coupon_address = $custom["coupon_address"][0];
		$no_of_coupon = $custom["no_of_coupon"][0];
		$fineprint = $custom["fineprint"][0];
		$thankyou_page_url = $custom["thankyou_page_url"][0];
		$coupon_entry = $custom["coupon_entry"][0];
		$coupon_start_date_time = date("Y-m-d H:i:s",$custom["coupon_start_date_time"][0]);
		$coupon_start_date_time_arry = explode(" ",$coupon_start_date_time);
		$product_images = $custom['file_name'][0];
		$min_purchases = $custom["min_purchases"][0];
		$filename = $custom["filename"][0];
		$max_purchases_user = $custom["max_purchases_user"][0];
		
		$shipping_address = $custom["shhiping_address"][0];
		$shipping_cost = $custom["shipping_cost"][0];
		$geo_latitude = $custom["geo_latitude"][0];
		$geo_longitude = $custom["geo_longitude"][0];

		if($coupon_start_date_time_arry[0]!="" && $coupon_start_date_time_arry[0]!="1970-01-01")
		$coupon_start_date = $coupon_start_date_time_arry[0];
		else
		$coupon_start_date = date("Y-m-d");
		$coupon_start_time = explode(":",$coupon_start_date_time_arry[1]);
		$coupon_start_time_hh = $coupon_start_time[0];
		$coupon_start_time_mm = $coupon_start_time[1];
		$coupon_start_time_ss = $coupon_start_time[2];
		
		if($custom["coupon_end_date_time"][0] != '') {
		$coupon_end_date_time = date("Y-m-d H:i:s",$custom["coupon_end_date_time"][0]);
		$coupon_end_date_time_arry = explode(" ",$coupon_end_date_time);
		if($coupon_end_date_time_arry[0]!="" && $coupon_end_date_time_arry[0]!="1970-01-01")
		$coupon_end_date = $coupon_end_date_time_arry[0];
		else
		$coupon_end_date = date("Y-m-d");
		$coupon_end_time = explode(":",$coupon_end_date_time_arry[1]);
		$coupon_end_time_hh = $coupon_end_time[0];
		$coupon_end_time_mm = $coupon_end_time[1];
		$coupon_end_time_ss = $coupon_end_time[2];
		}
?>
	<script>var rootfolderpath = '<?php echo DDB_PUGIN_URL;?>/images/';</script>
    <script type="text/javascript" src="<?php echo DDB_PUGIN_URL;?>/js/dhtmlgoodies_calendar.js"></script>

<script type="text/javascript">
function coupon_change_type(ctype)
{
	
	if(ctype=='1')
	{  	
		document.getElementById('afflink').style.display = "block";
		document.getElementById("no_of_coupons").style.display = "none";
		document.getElementById('multicode').style.display = "none";
		document.getElementById('singlecode').style.display = "none";
		document.getElementById('coupadd').style.display = "none";
		document.getElementById('coupon_entry').style.display = "none";
		document.getElementById('shipping_details').style.display = "none";
	}
	if(ctype=='2')
	{
	document.getElementById('media_upload').style.display = "block";
		document.getElementById("no_of_coupons").style.display = "none";
		document.getElementById('multicode').style.display = "none";
		document.getElementById('coupadd').style.display = "none";
		document.getElementById('singlecode').style.display = "none";
		document.getElementById('afflink').style.display = "none";
		
		document.getElementById('shipping_details').style.display = "none";
		
	}
	if(ctype=='3')
	{
		
		document.getElementById("no_of_coupons").style.display = "none";
		document.getElementById('multicode').style.display = "none";
		document.getElementById('coupon_entry').style.display = "block";
		document.getElementById('afflink').style.display = "none";
		document.getElementById('singlecode').style.display = "none";
		document.getElementById('coupadd').style.display = "none";
		document.getElementById('media_upload').style.display = "none";
		document.getElementById('shipping_details').style.display = "none";
	}
	if(ctype=='4')
	{
		document.getElementById('shipping_details').style.display = "block";
		document.getElementById("no_of_coupons").style.display = "none";
		document.getElementById('multicode').style.display = "none";
		document.getElementById('coupon_entry').style.display = "block";
		document.getElementById('afflink').style.display = "none";
		document.getElementById('singlecode').style.display = "none";
		document.getElementById('coupadd').style.display = "none";
		
		document.getElementById('media_upload').style.display = "none";
	}
	
	if(ctype == 'coupon_entry_0'  || document.getElementById('coupon_entry_0').checked == true)
	{
		
		document.getElementById('singlecode').style.display = "block";
		document.getElementById('coupon_entry').style.display = "block";
		document.getElementById("no_of_coupons").style.display = "none";
		document.getElementById('multicode').style.display = "none";
		document.getElementById('coupadd').style.display = "none";
		
		document.getElementById('media_upload').style.display = "none";
	
		
	}
	if(ctype == 'coupon_entry_1' || document.getElementById('coupon_entry_1').checked == true)
	{
		var no_of_c = document.getElementById('no_of_coupon').value;
		if(no_of_c == "")
		{
			alert("Please Enter Number of Items");
			document.getElementById('no_of_coupon').focus();
		}else{
			
		noofcoupon();
		document.getElementById('coupon_entry').style.display = "block";
		document.getElementById("no_of_coupons").style.display = "block";
		document.getElementById('multicode').style.display = "block";
				
		document.getElementById('singlecode').style.display = "none";
		document.getElementById('afflink').style.display = "none";
		document.getElementById('media_upload').style.display = "none";
		}
	}
	
	
	return true;
}
function no_of_coupon1()
{
	noofcoupon();
	return true;
}
function noofcoupon()
{
	document.getElementById("coupons").innerHTML =  document.getElementById("no_of_coupon").value+" coupons";
	return true;
}
function coupon_count()
{

	var cno = document.getElementById('no_of_coupon').value;
	if( document.getElementById('coupon_code').value!="")
	{
		var coupon_codes = document.getElementById('coupon_code').value;
		var spcoupon_codes = coupon_codes.split(",");
		var spcoupon_codes_count = spcoupon_codes.length;
		
		if( document.getElementById('used_coupon_code').value!="")
		{
			var used_coupon_code = document.getElementById('used_coupon_code').value;
			var used_coupon_code = used_coupon_code.split(",");
			var used_coupon_code_count = used_coupon_code.length;
			var spcoupon_codes_count = spcoupon_codes_count  + used_coupon_code_count;
		}
		if(cno!=spcoupon_codes_count)
		{
			document.getElementById('no_of_coupon').value =spcoupon_codes_count;
			alert("Number of coupon code should be" +cno );
			//return false;
		}
	
	}
	return true;
}

function admin_seller_edit(fid)
{

	if(fid == 'Yes')
	{
		sid = '1';
	}else
	{
		sid='0';
	}
	 if (fid=="")
	  {
		document.getElementById("allowedit").innerHTML="";
	  return;
	  }
		if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
		else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
		xmlhttp.onreadystatechange=function()
	  {
	    if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("allowedit").innerHTML=xmlhttp.responseText;
		
		 //location.href = 'admin.php?page=seller&allowedit=updated';
		}
	  }
	 
	  url = "<?php echo DDB_PUGIN_URL; ?>/monetize/seller/ajax_seller_status.php?allowedit="+sid+"&allowsid="+fid
	  xmlhttp.open("GET",url,true);
	  xmlhttp.send();
}
function disable_endtime() {
	if(document.getElementById('enddate').checked == true)	{
		document.getElementById('end_deal_time').style.display = 'none';
	} else {
		document.getElementById('end_deal_time').style.display = 'block';
	}
}
</script>
<link href="<?php echo DDB_PUGIN_URL;?>/library/css/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo DDB_PUGIN_URL;?>/js/dhtmlgoodies_calendar.js"></script>
<link href="<?php echo DDB_PUGIN_URL;?>/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />


	<div class="rows">
	<label class="label">Status :</label>
 	<select type="select" name="status" id= "status">
		<option name="0" value="0" <?php if($status == '0') { ?>selected="selected" <?php } ?> >Set deal status </option>
		<option name="2" value="2" <?php if($status == '2') { ?>selected="selected" <?php } ?>><?php echo "Active"; ?></option>
		<option name="1" value="1" <?php if($status == '1') { ?>selected="selected" <?php } ?>><?php echo "Accept" ; ?> </option>
	
		<?php if($status != '2' || $status != '1' ) { ?>
		<option name="3" value="3" <?php if($status == '3') { ?>selected="selected" <?php } ?>><?php echo "Reject"; ?></option>
		<?php } ?>
		<?php if($status == '2' || $status == '1' ) { ?>
		<option name="4" value="4" <?php if($status == '4') { ?>selected="selected" <?php } ?>><?php echo "Terminate"; ?></option>
		<?php } ?>
	</select>
    <p class="notif">
	<?php echo _e('Active &rarr; feature on homepage. <br/>','ddb_wp'); 
	if($status == 1 || $status == 2)
	{
		echo _e('Terminate &rarr; Remove this deal as featured on homepage. <br/>','ddb_wp'); 
	}
	_e('Accept or reject the deal here.If you wish to feature this deal on homepage,select appropriate option.','ddb_wp');
	?>
	
	
	</p>
    </div>
    
	<div class="rows">
    <label class="label"><?php echo _e('Name of the seller :','ddb_wp'); ?></label>
    <input type="text" name="owner_name" value="<?php echo $owner_name; ?>" /> 
	</div>
    
    <div class="rows">
    <label class="label"><?php echo _e('Email of the seller :','ddb_wp'); ?></label> 
    <input type="text" name="owner_email" value="<?php echo $owner_email; ?>" />
    </div>
    
    <div class="rows">
    <label class="label"><?php echo _e('Seller&rsquo;s website:','ddb_wp'); ?></label>
    <input type="text" name="coupon_website" value="<?php echo $coupon_website; ?>" />
    </div>
    
    <div class="rows">
    <label class="label"><?php echo _e('Total number of items:','ddb_wp'); ?></label>
    <input type="text" name="no_of_coupon" value="<?php echo $no_of_coupon; ?>" id="no_of_coupon" />
    </div>
    
    <div class="rows">
    <label class="label"><?php echo _e('Currency:','ddb_wp'); ?></label>
    <select name="currency"><?php echo getCurrencyDropdown($currency)?></select>
    </div>
    
    <div class="rows">
    <label class="label"><?php echo _e('Original price:','ddb_wp'); ?></label>    
    <input type="text" name="current_price" value="<?php echo $current_price; ?>" id="current_price" />
    </div>
    
    <div class="rows">
    <label class="label"><?php echo _e('Discounted price:','ddb_wp'); ?></label>
    <input type="text" name="our_price" value="<?php echo $our_price; ?>" id="our_price" />
    </div>
    
		
    <div class="rows">    		
    <label class="label"><?php echo _e('&lsquo;Thank you&rsquo; page (Redirect URL) set by the seller','ddb_wp'); ?> <span class="indicates">*</span></label> 
		<input type="text" class="textfield" id="thankyou_page_url" name="thankyou_page_url" value="<?php echo _e($thankyou_page_url,'ddb_wp');?>"> 
        <span id="our_priceInfo" class="error"></span>
    <p class="notif">After a successful purchase, users will be taken to this page. </p> 
	</div>
	
    <div class="rows">   
	<input type="hidden" id="link_url" name="link_url" value="<?php	the_permalink();?>"/>
	<label class="label"><?php echo _e('Deal type selected by the seller','ddb_wp'); ?></label>
	
  <select class="textfield" onchange="coupon_change_type(this.value);" name="coupon_type" id="coupon_type" >
		<option id="coupon_type_0" value="0" <?php if($coupon_type == '0') { ?>selected="selected"<?php } ?> ><?php echo "Deal types";?></option>
	
		<option id="coupon_type_1" value="1" <?php if($coupon_type == '1') { ?>selected="selected"<?php } ?>><?php echo DEAL_TYPES_1;?></option> 
		
		<option id="coupon_type_2" value="2" <?php if($coupon_type == '2') { ?>selected="selected"<?php } ?> ><?php echo DEAL_TYPES_2;?></option>

		<option id="coupon_type_3" value="3" <?php if($coupon_type == '3') { ?>selected="selected"<?php } ?> ><?php echo DEAL_TYPES_3;?></option>
		
		<option id="coupon_type_4" value="4" <?php if($coupon_type == '4') { ?>selected="selected"<?php } ?> ><?php echo DEAL_TYPES_4;?></option>
	
		<?php ?>
  </select>
 	</div> 




    <?php echo '<input type="hidden" name="mytheme_meta_box_nonce" value="'.wp_create_nonce('deal_panel').'" />';?>
   
    
    <?php echo '<input type="hidden" name="mytheme_meta_box_nonce" value="'.wp_create_nonce('deal_panel').'" />';?>
 
  						 <div id="coupon_entry" <?php if($coupon_type == '2' || $coupon_type == '3' || $coupon_type == '4' || $coupon_entry != '' ) { ?>style="display:block;"<?php }else{ ?>style="display:none;"<?php } ?>>
                         <div class="rows clearfix">
						
						<label class="label"><?php _e('Seller wants to provide','ddb_wp');  if($coupon_entry == "coupon_entry_0"){ echo "<b>".SINGLE_COUPON_TEXT."</b>"; }else{ echo "<b>".MULTIPPLE_COUPON_TEXT."</b>"; } ?> <span class="indicates">*</span></label>
							  <p>
								  <label class="label">
								    <input type="radio" name="coupon_entry" value="coupon_entry_0" id="coupon_entry_0" onclick="coupon_change_type(this.value);" <?php if($coupon_entry == 'coupon_entry_0') { ?> checked="checked"<?php } ?>/>
								    <?php echo SINGLE_COUPON_TEXT; ?></label>
								
								  <label class="label">
								    <input type="radio" name="coupon_entry" value="coupon_entry_1" id="coupon_entry_1" onclick="coupon_change_type(this.value);" <?php if($coupon_entry == 'coupon_entry_1') { ?> checked="checked"<?php } ?> />
								   <?php echo MULTIPPLE_COUPON_TEXT; ?></label>
								 
					   		 </p>
                            <span id="coupon_info" class="error"></span>
						</div>
                        </div>
                        
                                
                        <div class="rows" id="no_of_coupons" style="display:none;">
							<?php echo _e('Please enter ','ddb_wp');?><span style="font-weight:bold;" id="coupons"></span>
						</div>	
                        
						<div class="rows" id="afflink" style=" <?php if($coupon_type == '1'){ ?>display:block;<?php  }else{?>display:none; <?php }?>">
                    	 <label class="label"><?php echo _e('Your Affiliate Link:','ddb_wp');?></label> 
                   			 <input type="text" name="coupon_link" value="<?php echo $coupon_link; ?>" />
                             <p class="notif">Enter your affiliate link for this coupon.</p>
                   		 </div>
						 
						 <div class="rows" id="media_upload" name="media_upload" style=" <?php if($coupon_type == '2'){ ?>display:block;<?php  }else{?>display:none; <?php }?>">
						
							<label class="label"><?php echo UPLOAD_PRODUCT_TEXT; ?></label>
							<input type="text" name="_wp_attached_file" id="_wp_attached_file" value="<?php echo $custom["_wp_attached_file"][0]; ?>"/>
	 </div>
	  
	<div id="_wp_attached_file_div" class="iframe" >
      <iframe name="mktlogoframe" id="upload_target" style="border: none; width:100%; height: 75px;" frameborder="0" marginheight="0" marginwidth="0" scrolling="no" src="<?php echo DDB_PUGIN_URL;?>/monetize/upload/index.php?img=_wp_attached_file&nonce=mktnonce" ></iframe>
	</div> 
							
							
						

						<div class="rows" id="singlecode" <?php if($coupon_entry == 'coupon_entry_0'){ ?>style="display:block;" <?php }else if($coupon_type =='1' || $coupon_type =='2' || $coupon_type =='') { ?>style="display:none;"<?php }else{ ?>style="display:none;"<?php } ?>>
							<div class="form_row clearfix">
								<label class="label"><?php echo PRO_ADD_COUPON_TEXT; ?></label> 
								<input type="text" name="coupon_code1" class="textfield" value="<?php if($coupon_entry == 'coupon_entry_0') { echo $single_coupon_code; } ?>"/> 
                             </div>	
                                <p class="notif"><?php echo SINGLE_COUPON_TEXT; ?></p>
											
                        </div>
						
                        <div class="rows" id="multicode" <?php if($coupon_entry == 'coupon_entry_1'){ ?>style="display:block;" <?php }else if($coupon_type =='1' || $coupon_type =='2' || $coupon_type =='') { ?>style="display:none;"<?php }else{ ?>style="display:none;"<?php } ?>>
							 	<label class="label"><?php echo PRO_ADD_COUPON_TEXT; ?></label> 
								<textarea rows="4" cols="60" id="coupon_code" name="coupon_code"  class="textfield"><?php if($coupon_entry == 'coupon_entry_1') { echo $coupon_code; } ?></textarea>
                               <p class="notif"><?php echo COMMA_SEPRATED_DEAL_CODE_TEXT; ?></p>
                         </div> 			
                        
							<div class="rows clearfix">
								<label class="label"><?php echo MIN_PUR_TEXT; ?> </label>
								<input type="text" class="textfield" id="min_purchases" name="min_purchases" value="<?php echo $min_purchases; ?>">
								<span id="min_purchaseinfo" class="error"></span>
								 <p class="notif"><?php echo MIN_PUR_MSG;?></p>
							</div>
                        	
                          
                             <div class="rows clearfix">
								<label class="label"><?php echo MAX_USERPUR_TEXT; ?></label> 
								<input type="text" class="textfield" id="max_purchases_user" name="max_purchases_user" value="<?php echo $max_purchases_user; ?>">
								<span id="max_userpurchaseinfo" class="error"></span>
							    <p class="notif"><?php echo MAX_USERPUR_MSG;?></p>
							</div>
                        
						 <div class="rows"  id="shipping_details" <?php if($coupon_type == '4') { ?>style="display:block;" <?php }else{ ?>style="display:none;"<?php } ?>>
                        <h4 class="title"><?php echo SHIPPING_DETAIL_TEXT; ?> </h4>
							<div class="rows clearfix">
                                <label class="label"><?php echo SHIPPINGCOST_TEXT; ?> <span class="indicates">*</span></label> 
                                <input type="text" class="textfield" id="shipping_cost" name="shipping_cost" value="<?php echo _e($shipping_cost,'ddb_wp');?>">
                                <span id="shippingcost_info" class="error"></span>
                            </div>
							
							
                             <div class="rows clearfix">
                                <label class="label"><?php echo SHIPPINGADRS_TEXT; ?> <span class="indicates">*</span></label>
                                <input type="text" class="textfield" id="address" name="shhiping_address" value="<?php echo _e($shipping_address,'ddb_wp');?>"> 
                                <span id="shipping_info" class="error"></span></div>
							<?php  
								if(get_option('ptttheme_google_map_opt') == 'Enable' && get_option('pttheme_google_map_api') != '') {?>
							<div class="rows clearfix">
							<?php include_once(DDB_PUGIN_PATH . "/library/map/location_add_map.php");?>
								<p class="notif"><?php echo GET_MAP_MSG;?></p>
							 </div> 
							  
							 <div class="rows clearfix">
								<label class="label"><?php echo EVENT_ADDRESS_LAT;?> </label>
								<input type="text" name="geo_latitude" id="geo_latitude" class="textfield" value="<?php echo $geo_latitude; ?>" size="25"  /> 
								<p class="notif"><?php echo GET_LATITUDE_MSG;?></p>
							 </div>
							 <div class="rows clearfix">
								<label class="label"><?php echo EVENT_ADDRESS_LNG;?> </label>
								<input type="text" name="geo_longitude" id="geo_longitude" class="textfield" value="<?php echo $geo_longitude; ?>" size="25"  />
							   <p class="notif"><?php echo GET_LOGNGITUDE_MSG;?></p>
							 </div>
							<?php } ?>
                        </div>
		
         <div class="rows ">	 			
   <label class="label"><?php echo _e('Deal start date & time:','ddb_wp');?></label> 
    <input type="text" name="coupon_start_date"  id="coupon_start_date"  style="width:100px;"  value="<?php echo $coupon_start_date; ?>"/>
    &nbsp;<img src="<?php echo DDB_PUGIN_URL;?>/images/cal_s.png" alt="Calendar" onclick="displayCalendar(document.post.coupon_start_date,'yyyy-mm-dd',this)" style="cursor: pointer;" align="absmiddle" border="0">&nbsp;&nbsp;
	<label>HH:</label>
    <select name="coupon_start_time_hh">
    <?php
	for($i=0;$i<=23;$i++){
		if($i<10)
		$i="0".$i;
	?>
    <option value="<?php echo $i;?>" <?php if($coupon_start_time_hh==$i){?> selected="selected" <?php } ?>><?php echo $i;?></option>
    <?php }?>
    </select>&nbsp;&nbsp;
    <label>MM:</label>
    <select name="coupon_start_time_mm">
    <?php
	for($i=0;$i<=59;$i++){
		if($i<10)
		$i="0".$i;
	?>
    <option value="<?php echo $i;?>" <?php if($coupon_start_time_mm==$i){?> selected="selected" <?php } ?>><?php echo $i;?></option>
    <?php }?>
    </select>&nbsp;&nbsp;
     <label>SS:</label>
    <select name="coupon_start_time_ss">
    <?php
	for($i=0;$i<=59;$i++){
		if($i<10)
		$i="0".$i;
	?>
      	 
    <option value="<?php echo $i;?>" <?php if($coupon_start_time_ss==$i){?> selected="selected" <?php } ?>><?php echo $i;?></option>
    <?php }?>
    </select>
    </div>
     	
        <div class="rows clearfix">
     <label class="label"><?php echo _e('Deal end date & time:','ddb_wp');?></label> 
	 
	 <span class="disable_checkbox" ><label>
	 <input name="enddate" id="enddate" type="checkbox" value="0" onchange="disable_endtime()"<?php if($custom['enddate'][0] == "0") { ?>checked<?php } ?> /><?php echo DISABLE_END_TIME; ?></label>
	 </span>
	<div class="form_row clearfix" id="end_deal_time" <?php if($custom['enddate'][0] == "0") { ?>style="display:none" <?php } ?>>	
    <input type="text" name="coupon_end_date"  id="coupon_end_date"  style="width:100px;"  value="<?php echo $coupon_end_date; ?>"/>
    &nbsp;<img src="<?php echo DDB_PUGIN_URL;?>/images/cal_s.png" alt="Calendar" onclick="displayCalendar(document.post.coupon_end_date,'yyyy-mm-dd',this)" style="cursor: pointer;" align="absmiddle" border="0">&nbsp;&nbsp;
	<label>HH:</label>
    <select name="coupon_end_time_hh">
    <?php
	for($i=0;$i<=23;$i++){
		if($i<10)
		$i="0".$i;
	?>
    <option value="<?php echo $i;?>" <?php if($coupon_end_time_hh==$i){?> selected="selected" <?php } ?>><?php echo $i;?></option>
    <?php }?>
    </select>&nbsp;&nbsp;
    <label>MM:</label>
    <select name="coupon_end_time_mm">
    <?php
	for($i=0;$i<=59;$i++){
		if($i<10)
		$i="0".$i;
	?>
    <option value="<?php echo $i;?>" <?php if($coupon_end_time_mm==$i){?> selected="selected" <?php } ?>><?php echo $i;?></option>
    <?php }?>
    </select>&nbsp;&nbsp;
     <label>SS:</label>
    <select name="coupon_end_time_ss">
    <?php
	for($i=0;$i<=59;$i++){
		if($i<10)
		$i="0".$i;
	?>
    <option value="<?php echo $i;?>" <?php if($coupon_end_time_ss==$i){?> selected="selected" <?php } ?>><?php echo $i;?></option>
    <?php }?>
    </select>
	</div> 
    </div>
     <div class="rows clearfix"> 
	<label class="label">Deal image :</label> 	 
	 <input type="text" name="file_name[]" id="file_name" value="<?php echo $custom["file_name"][0]; ?>"/>
	 </div>
	  <div class="rows clearfix"> 
	  <img src ="<?php $thumb_path = empty($custom["file_name"][0])?DDB_IMAGE_URL.'no-image.png':$custom["file_name"][0]; echo DDBWP_thumbimage_filter($thumb_path,'&amp;w=285&amp;h=275&amp;zc=1&amp;q=80'); ?>" id="deal_img" alt="deal image"  hspace="15"/>       
	  </div>
	<div id="file_name_div" class="iframe" >
      <iframe name="mktlogoframe" id="upload_target" style="border: none; width:100%; height: 75px;" frameborder="0" marginheight="0" marginwidth="0" scrolling="no" src="<?php echo DDB_PUGIN_URL;?>monetize/upload/index.php?img=file_name&nonce=mktnonce&imgid=deal_img" ></iframe>
      </div>
    <div class="rows clearfix"> 
		<label class="label">Deal image - 2 :</label> 	
         <input type="text" name="file_name[]" id="file_name1" value="<?php echo $custom["file_name1"][0]; ?>"/> 
    </div>
      <div class="rows clearfix"> 
      <img src ="<?php $thumb_path = empty($custom["file_name1"][0])?DDB_IMAGE_URL.'no-image.png':$custom["file_name1"][0]; echo DDBWP_thumbimage_filter($thumb_path,'&amp;w=285&amp;h=275&amp;zc=1&amp;q=80'); ?>" id="deal_img1" alt="deal image" hspace="15"/>
      <iframe name="mktlogoframe1" id="upload_target1" style="border: none; width:100%; height: 75px;" frameborder="0" marginheight="0" marginwidth="0" scrolling="no" src="<?php echo DDB_PUGIN_URL;?>monetize/upload/index.php?img=file_name1&nonce=mktnonce&imgid=deal_img1" ></iframe>
      </div>
       <div class="rows clearfix"> 
	        <label class="label">Deal image - 3 :</label> 	
            <input type="text" name="file_name[]" id="file_name2" value="<?php echo $custom["file_name2"][0]; ?>"/> 
        </div>
      <div class="rows clearfix"> 
       <img src ="<?php $thumb_path = empty($custom["file_name2"][0])?DDB_IMAGE_URL.'no-image.png':$custom["file_name2"][0]; echo DDBWP_thumbimage_filter($thumb_path,'&amp;w=285&amp;h=275&amp;zc=1&amp;q=80'); ?>" id="deal_img2" alt="deal image" hspace="15"/>
      <iframe name="mktlogoframe2" id="upload_target2" style="border: none; width:100%; height: 75px;" frameborder="0" marginheight="0" marginwidth="0" scrolling="no" src="<?php echo DDB_PUGIN_URL;?>monetize/upload/index.php?img=file_name2&nonce=mktnonce&imgid=deal_img2" ></iframe>
      </div>
	 
     <div class="rows clearfix">       

	 <?php 
	 if(isset($_REQUEST['post']) || $_REQUEST['post'] != "") {
	 if( $is_expired == 1) { ?> <?php echo _e('This deal has expired','ddb_wp'); ?> <?php }else{ 
			echo "This deal will expire on "."<b>".$coupon_end_date_time."</b>";
	 ?>		
	<?php } }
	?><input type="hidden" name="is_expired" value="<?php _e($is_expired,'ddb_wp'); ?>"/>
    </div>
	
 <?php 
			/*$custom_metaboxes = get_post_custom_fields_DDBWP_1();
			if(count($custom_metaboxes) > 0){
				echo '<h4>'.CUSTOM_TEXT.'</h4>';
			
			foreach($custom_metaboxes as $key=>$val)
			{
				$name = $val['name'];
				$site_title = $val['site_title'];
				$type = $val['type'];
				$admin_desc = $val['desc'];
				$option_values = $val['option_values'];
				$default_value = $val['default'];
				$value = get_post_meta($_REQUEST['editdeal'], $name,true);
				
			?>
            <div class="rows clearfix">
			   <?php if($type=='text'){?>
               <label class="label"><?php echo $site_title; ?></label>
             <input name="<?php echo $name;?>" id="<?php echo $name;?>" value="<?php echo $value;?>" type="text" class="textfield" />
               <?php 
                }elseif($type=='checkbox'){
                ?>     
                    
                <input name="<?php echo $name;?>" id="<?php echo $name;?>" <?php if($value){ echo 'checked="checked"';}?>  value="<?php echo $value;?>" type="checkbox" /> <?php echo $site_title; ?>
                <?php
                }
				elseif($type=='multicheckbox')
				{ ?>
				 <label class="label"><?php echo $site_title; ?></label>
				<?php
					$options = $val['option_values'];
					if($options)
					{  $chkcounter = 0;
					    
						$option_values_arr = explode(',',$options);
						for($i=0;$i<count($option_values_arr);$i++)
						{
							$chkcounter++;
							$seled='';
							if($default_value == $option_values_arr[$i]){ $seled='checked="checked"';}							
							echo '
							<div class="form_cat">
								<label>
									<input name="'.$key.'[]"  id="'.$key.'_'.$chkcounter.'" type="checkbox" value="'.$option_values_arr[$i].'" '.$seled.' /> '.$option_values_arr[$i].'
								</label>
							</div>';								
						}
						
					}
				}
				
				elseif($type=='texteditor'){
                ?>
                <label class="label"><?php echo $site_title; ?></label>
                <textarea name="<?php echo $name;?>" id="<?php echo $name;?>" cols="55" class="mce"><?php echo $value;?></textarea>       
                <?php
                }elseif($type=='select'){
                ?>
                 <label class="label"><?php echo $site_title; ?></label>
                <select name="<?php echo $name;?>" id="<?php echo $name;?>" class="textfield textfield_x">
                <?php if($option_values){
				$option_values_arr = explode(',',$option_values);
				
				for($i=0;$i<count($option_values_arr);$i++)
				{
				?>
               <option value="<?php echo $option_values_arr[$i]; ?>" <?php if($value==$option_values_arr[$i]){ echo 'selected="selected"';} else if($default_value==$option_values_arr[$i]){ echo 'selected="selected"';}?>><?php echo $option_values_arr[$i]; ?></option>
                <?php	
				}
				?>
                <?php }?>               
                </select>                
                <?php
                }
                ?>
             	 <span class="message_note"><?php echo $admin_desc;?></span>
              </div>
              <?php }
			  }*/?>
   
<?php
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