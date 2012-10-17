<?php

/********************************************
*	Author 	:	Anoop Kumar Srivastava		*
*	Date	:	17 Oct 2012					*
********************************************/

$pq_routes[] = array('all-products','products','pq_showall');
$pq_routes[] = array('cart','cart','pq_cart');
$pq_routes[] = array('checkout','cart','pq_checkout');

$custom_urls = apply_filters('pq_routes', 'slug','controller', 'function');
if(is_array($custom_urls))
	$pq_routes = array_merge($pq_routes, $custom_urls);

$url_param = parse_url(site_url().$_SERVER["REQUEST_URI"], PHP_URL_PATH);

if(isset($_REQUEST['pq'])){
	foreach($pq_routes as $rurl){
		if($_REQUEST['pq'] == $rurl[0]){			
			if(!function_exists($rurl[2]))
				include PQ_ROOT_PATH.$rurl[1].'.php';
			parse_str($url_param['query'], $pass_arg);
			call_user_func($rurl[2],$pass_arg);		
		}
	}
}
?>