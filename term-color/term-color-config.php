<?php

/*-------------------------------------------*/
/*  Load modules
/*-------------------------------------------*/
if ( ! class_exists( 'Vk_term_color' ) )
{

	global $vk_term_color_textdomain;
	$vk_term_color_textdomain = 'XXXX_plugin_text_domain_XXXX';

	global $vk_term_color_taxonomies;
	$vk_term_color_taxonomies = array('category','post_tag');
	
	require_once( 'term-color/class.term-color.php' );

	/*  transrate
	/*-------------------------------------------*/
	function XXXX_term_color_translate(){
		__( 'Color', 'XXXX_plugin_text_domain_XXXX' );
	}

}