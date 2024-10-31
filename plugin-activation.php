<?php
defined( 'EOS_RNFP_PLUGIN_DIR' ) || exit;

if( file_exists( WPMU_PLUGIN_DIR.'/0-resource-not-found-placeholder.php' ) ){
	unlink( WPMU_PLUGIN_DIR.'/0-resource-not-found-placeholder.php' );
}
eos_rnfp_write_file( EOS_RNFP_PLUGIN_DIR.'/mu-plugins/0-resource-not-found-placeholder.php',WPMU_PLUGIN_DIR,WPMU_PLUGIN_DIR.'/0-resource-not-found-placeholder.php',true );

//Helper function to write file
function eos_rnfp_write_file( $source,$destination_dir,$destination,$update_info = false ){
	$writeAccess = false;
	$access_type = get_filesystem_method();
	if( $access_type === 'direct' ){
		/* you can safely run request_filesystem_credentials() without any issues and don't need to worry about passing in a URL */
		$creds = request_filesystem_credentials( admin_url(), '', false, false, array() );
		/* initialize the API */
		if ( ! WP_Filesystem( $creds ) ) {
			/* any problems and we exit */
			return false;
		}
		global $wp_filesystem;
		$writeAccess = true;
		if( empty( $wp_filesystem ) ){
			require_once ( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}
		if( !$wp_filesystem->is_dir( $destination_dir ) ){
			/* directory didn't exist, so let's create it */
			$wp_filesystem->mkdir( $destination_dir );
		}

		$copied = @$wp_filesystem->copy( $source,$destination );
		if ( !$copied ) {
			printf( esc_html__( 'Failed to create %s','resource-not-found-placeholder' ),$destination );
		}
		else{
			if( $update_info ){
				update_site_option( 'eos_rnfp_activation_info',time(),'no' );
			}
		}
	}
	else{
		if( $update_info ){
			set_transient( 'eos-rnfp-notice-fail', true, 5 ); /* don't have direct write access. Prompt user with our notice */
		}
	}
}
