<?php

/*-------------------------------------------*/
/*  Load modules
/*-------------------------------------------*/
if ( ! class_exists( 'Vk_Page_Header' ) )
{
	require_once( 'vk-page-header/class-vk-page-header.php' );
	require_once( 'custom-field-builder-config.php' );

	global $vk_page_header_textdomain;
	$vk_page_header_textdomain = 'lightning_skin_jpnstyle';

	global $vk_page_header_output_class;
	$vk_page_header_output_class = '.page-header';

	global $customize_setting_prefix;
	$customize_setting_prefix = 'Lightning';

}

/*
Sample Image
https://pixabay.com/ja/%E4%BA%AC-%E6%97%A5%E6%9C%AC-%E7%AB%B9-%E3%83%9C%E3%82%B1%E5%91%B3-%E5%86%92%E9%99%BA-%E6%A3%AE%E6%9E%97-%E6%97%85%E8%A1%8C-1860521/
 */