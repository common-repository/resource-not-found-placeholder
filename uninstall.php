<?php
if( !defined( 'WP_UNINSTALL_PLUGIN' ) && !defined( 'FDP_RESET_SETTINGS' ) ){
    die();
}
delete_site_option( 'eos_rnfp_activation_info' );
delete_site_option( 'eos_rnfp_version' );
delete_transient( 'eos-rnfp-notice-fail' );
