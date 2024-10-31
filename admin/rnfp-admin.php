<?php
defined( 'ABSPATH' ) || exit;

add_action( 'admin_notices',function(){
  //It warns the user if something is wrong with the MU plugin
	do_action( 'rnfp_admin_notices' );
	//It creates the transient needed for displaing plugin notices after activation
	if( get_transient( 'rnfp-notice-fail' ) ){
		delete_transient( 'rnfp-notice-fail' );
	?>
	<div class="rnfp-wrp notice notice-error is-dismissible">
		<p><?php _e( 'You have no direct write access, Resource Not Found Placeholder was not able to create the necessary mu-plugin and will not work.', 'resource-not-found-placeholder' ); ?></p>
	</div>
	<?php
	}
	$mu_exists = file_exists( WPMU_PLUGIN_DIR.'/0-resource-not-found-placeholder.php' );
	$message = $class = '';
	if( !defined( 'EOS_RNFP_MU_VERSION' ) || EOS_RNFP_MU_VERSION !== EOS_RNFP_VERSION || !$mu_exists ){
		if( !$mu_exists ){
			$class = 'error';
			$message = '<p><h1>'.sprintf( __( "Very important file missing. First, refresh this page, if you still see this message, disable Resource Not Found Placeholder and activate it again. If nothing helps, copy the file %s and put it into the directory %s","resource-not-found-placeholder" ),'/wp-content/plugins/resource-not-found-placeholder/mu-plugins/0-resource-not-found-placeholder.php','wp-content/mu-plugins/' ).'</h1></p>';
		}
		elseif( $mu_exists && !defined( 'EOS_RNFP_MU_VERSION' ) ){
			$class = 'error';
			$message = '<p><h1>'.sprintf( __( "Issue detected. It looks the file %s has been modified.","resource-not-found-placeholder" ),WPMU_PLUGIN_DIR.'/0-resource-not-found-placeholder.php',WPMU_PLUGIN_DIR ).'</h1></p>';
		}
		elseif( defined( 'EOS_RNFP_MU_VERSION' ) && EOS_RNFP_MU_VERSION !== EOS_RNFP_VERSION ){
			$class = 'warning';
			$message = '<p>'.__( 'Issue detected. Refresh this page. If you still see this message disable Resource Not Found Placeholder (only disable NOT DELETING it, or you will lose all the options), then activate it and refresh again this page.','resource-not-found-placeholder' ).'</p>';
			$message .= '<p>'.sprintf( __( "If you still see this message after disabling and reactivating Resource Not Found Placeholder and after refreshing this page, open a thread on the %sPlugin Support Forum%s","resource-not-found-placeholder" ).'</p>','<a href="https://wordpress.org/support/plugin/resource-not-found-placeholder/" target="_blank" rel="noopener">','</a>' ).'</p>';
		}
		?>
		<div class="rnfp-wrp notice notice-<?php echo $class; ?> is-dismissible" style="line-height:1.5;display:block !important">
			<?php echo wp_kses( $message,array( 'h1' => array(),'p' => array(),'a' => array( 'href' => array(),'rel' => array(),'target' => array() ) ) ); ?>
		</div>
		<?php
	}
} );

add_action( 'admin_init',function(){
	$previous_version = eos_rnfp_get_option( 'eos_rnfp_version' );
	if( version_compare( $previous_version, EOS_RNFP_VERSION,'<' ) ){
  	//if the plugin was updated we need to update also the mu-plugin
  	define( 'RNFP_DOING_MU_UPDATE',true );
  	if( file_exists( WPMU_PLUGIN_DIR.'/0-resource-not-found-placeholder.php' ) ){
  		unlink( WPMU_PLUGIN_DIR.'/0-resource-not-found-placeholder.php' );
  	}
  	require EOS_RNFP_PLUGIN_DIR.'/plugin-activation.php';
  	eos_rnfp_update_option( 'eos_rnfp_version',EOS_RNFP_VERSION );
  }
} );

//Update options in case of single or multisite installation.
function eos_rnfp_update_option( $option,$newvalue ){
	if( !is_multisite() ){
		return update_option( $option,$newvalue,false );
	}
	else{
		return update_blog_option( get_current_blog_id(),$option,$newvalue );
	}
}

//Get options in case of single or multisite installation.
function eos_rnfp_get_option( $option ){
  if( !is_multisite() ){
    return get_option( $option );
  }
  else{
    return get_blog_option( get_current_blog_id(),$option );
  }
}
