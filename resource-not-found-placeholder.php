<?php
/*
Plugin Name: Resource Not Found Placeholder
Description: No more 404 errors and redirections for missing resources. When a resource is not found, the plugin will replace it with an empty resource.
Author: Jose Mortellaro
Author URI: https://josemortellaro.com/
Domain Path: /languages/
Text Domain: resource-not-found-placeholder
Version: 0.0.4
*/
/*  This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/
defined( 'ABSPATH' ) || exit; // Exit if accessed directly

//Definitions
define( 'EOS_RNFP_VERSION','0.0.4' );
define( 'EOS_RNFP_PLUGIN_DIR',untrailingslashit( dirname( __FILE__ ) ) );

if( is_admin() && !wp_doing_ajax() ){
 	require_once EOS_RNFP_PLUGIN_DIR.'/admin/rnfp-admin.php';
	//It adds a settings link to the action links in the plugins page
	add_filter( 'plugin_action_links_'.untrailingslashit( plugin_basename( __FILE__ ) ) , function( $links ){
		array_push( $links,'<a class="rnfp-link" href="#">' . __( 'No settings needed','resource-not-found-placeholderr' ). '</a>' );
		return $links;
	} );
}

//Actions triggered after plugin activation or after a new site of a multisite installation is created
register_activation_hook( __FILE__,function( $networkwide ){
  if( is_multisite() && $networkwide ){
		wp_die( sprintf( esc_html__( "Resource Not Found Placheholder can't be activated networkwide, but only on each single site. %s%s%s","resource-not-found-placeholder" ),'<div><a class="button" href="'.admin_url( 'network/plugins.php' ).'">',esc_html__( 'Back to plugins','resource-not-found-placeholder' ),'</a></div>' ) );
	}
	require_once EOS_RNFP_PLUGIN_DIR.'/plugin-activation.php';
} );


register_deactivation_hook( __FILE__,function(){
	if( !is_multisite() && file_exists( WPMU_PLUGIN_DIR.'/0-resource-not-found-placeholder.php' ) ){
		unlink( WPMU_PLUGIN_DIR.'/0-resource-not-found-placeholder.php' );
	}
} );
