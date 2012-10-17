<?php
/*
Plugin Name: Get Products Quote
Plugin URI: http://anoop4u.wordpress.com/get-product-quote
Description: Let the visoty to get a price quote for various products
Version: 1.0
Author: Anoop Kumar Srivastava
Author URI: http://anoop4u.wordpress.com/
License: GPL2
*/
/*
Products Quote - Let the visoty to get a price quote for various products
Copyright (C) 2012  Anoop Srivastava

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
if(!defined('PQ_ROOT_PATH')){
		define('PQ_ROOT_PATH', plugin_dir_path(__FILE__).'/');
		define('PQ_URL',plugins_url());
}
// Adding all the required file
require(PQ_ROOT_PATH."lib/constant.php");
require(PQ_ROOT_PATH."lib/utility.php");	
require(PQ_ROOT_PATH."lib/routes.php");	
?>