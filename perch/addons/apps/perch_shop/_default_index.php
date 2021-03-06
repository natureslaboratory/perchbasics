<?php

	# include the API
	include(__DIR__.'/../../../core/inc/api.php');

	$API  = new PerchAPI(1.0, 'perch_shop');
	$Lang = $API->get('Lang');
	$HTML = $API->get('HTML');
	$Paging = $API->get('Paging');

	// Try to update
    $Settings = $API->get('Settings');

    if ($Settings->get('perch_shop_default_currency')->val() && 
    	version_compare(PERCH_SHOP_VERSION, $Settings->get('perch_shop_update')->val(), '>')) {
    	$mode = 'update';
    }

	# Set the page title
	$Perch->page_title = $Lang->get($title);

	
	include('modes/_subnav.php');
	include('modes/'.$mode.'.pre.php');

	# Top layout
	include(PERCH_CORE . '/inc/top.php');

	# Display your page
	include('modes/'.$mode.'.post.php');

	# Bottom layout
	include(PERCH_CORE . '/inc/btm.php');
