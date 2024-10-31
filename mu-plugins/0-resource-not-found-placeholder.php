<?php
/*
  Plugin Name: resource not found placeholder [rnfp]
  Description: mu-plugin automatically installed by resource not found placeholder
  Version: 0.0.4
  Plugin URI: https://josemortellaro.com/
  Author: Jose Mortellaro
  Author URI: https://josemortellaro.com/
  License: GPLv2
*/

defined( 'ABSPATH' ) || exit;

define( 'EOS_RNFP_MU_VERSION','0.0.4' );

if( isset( $_SERVER['REQUEST_URI'] ) ){
  $uri = sanitize_text_field( $_SERVER['REQUEST_URI'] );
  $uriA = explode( '?',$uri );
  $file_extension = pathinfo( $uriA[0],PATHINFO_EXTENSION );
  if( $file_extension && !in_array( strtolower( $file_extension ),array( 'php','txt','xml', 'xsl' ) ) ){
    $content_type = false;
    $content_types = array(
      'jpg' => 'image/jpg',
      'png' => 'image/png',
      'jpeg' => 'image/jpeg',
      'webp' => 'image/webp',
      'gif' => 'image/gif',
      'ico' => 'image/ico',
      'svg' => 'image/svg+xml',
      'css' => 'text/css',
      'csv' => 'text/csv',
      'html' => 'text/html',
      'js' => 'text/javascript',
      'txt' => 'text/plain',
      'mpeg' => 'video/mpeg',
      'mp4' => 'video/mp4'
    );
    if( in_array( $file_extension,array_keys( $content_types ) ) ){
      $content_type = $content_types[sanitize_key( $file_extension )];
    }
    if( function_exists( 'imagecreate' ) && eos_rnfp_is_image( $file_extension ) ){
      $image = imagecreate( 30,30 );
      // Set the background color of image
      $background_color = imagecolorallocate( $image,255,255,255 );
      if( !in_array( $file_extension,array( 'ico' ) ) ){
        // Set the text color of image
        $text_color = imagecolorallocate( $image,0,0,0 );
        $border_color = imagecolorallocate( $image,146,146,146 );
        $text = '404';
        $font = 5;
        $textWidth = imagefontwidth( $font ) * strlen( $text );
        $xLoc = 15 - $textWidth/2;
        // Function to create image which contains string.
        imagestring( $image,$font,$xLoc,15 - $font,$text,$text_color );
        eos_rmfp_draw_border( $image,$border_color,1 );
        if( is_rtl() ){
          imageflip( $image,IMG_FLIP_HORIZONTAL );
        }
      }
      imagepng( $image );
      imagedestroy( $image );
    }
    if( 'favicon.ico' === str_replace( '/','',$uri ) ){
      $favicon_url = get_site_icon_url( 32, includes_url( 'images/w-logo-blue-white-bg.png' ) );
      if( $favicon_url ){
        $favicon_path = str_replace( get_home_url(),ABSPATH,$favicon_url );
        if( file_exists( $favicon_path ) ){
          echo file_get_contents( $favicon_path );
        }
      }
    }
    if( $content_type ){
      header( sprintf( 'Content-Type: %s',$content_type ) );
    }
    exit;
  }
}

//Check if the file extension is related to an image
function eos_rnfp_is_image( $file_extension ){
  return in_array( $file_extension,array(
    'jpg',
    'png',
    'jpeg',
    'webp',
    'gif',
    'svg'
  ) );
}

//Draw a border around the image
function eos_rmfp_draw_border( &$img,&$color,$thickness = 1 ){
  if( !function_exists( 'ImageSX' ) || !function_exists( 'ImageSY' ) ) return false;
  $x1 = 0;
  $y1 = 0;
  $x2 = ImageSX( $img ) - 1;
  $y2 = ImageSY( $img ) - 1;
  for( $i = 0; $i < $thickness;$i++ ){
    ImageRectangle( $img,$x1++,$y1++,$x2--,$y2--,$color );
  }
}
