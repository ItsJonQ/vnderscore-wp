<?php
/**
 * v_config
 * This is the filter class for the v_ wordpress theme
 *
 * @class
 *
 * @package v_
 *
 */

// Defining the v_config class
if(!class_exists('v_config')) {

  class v_config {

    /**
     * init
     * Initialization method for this class
     *
     * @init
     */
    public static function init() {
    }

    public static function taxonomy_add_featured_image( $taxonomy ) {
      // Return false if taxonomy is not defined or doesn't exist
      if( !$taxonomy || !taxonomy_exists( $taxonomy ) ) {
        return false;
      }

      print_r($taxonomy);

    }

  }

  // Initialize
  add_action( 'init' , array( 'v_config' , 'init' ) );

}