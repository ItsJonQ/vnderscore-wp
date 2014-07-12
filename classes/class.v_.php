<?php
/**
 * v_
 * This is the main class for the v_ wordpress theme
 *
 * @class
 *
 * @package v_
 *
 */

// Defining the main v_ class
if(!class_exists('v_')) {

  // Including / requiring the other classes
  include( 'class.v_filter.php' );
  // include( 'class.v_header.php' );
  include( 'class.v_image.php' );
  include( 'class.v_parse.php' );
  include( 'class.v_post.php' );


  class v_ {

    /**
     * init
     * Initialization method for this class
     *
     * @init
     */
    public static function init() {
      /**
       * Nothin' yet!
       */
    }

    /**
     * extend
     * Method to extend settings / options (arrays)
     * @param  [ array ] $base        [ the base/original array ]
     * @param  [ array ] $replacement [ the replacement array ]
     * @return [ array ]              [ the extended base array]
     */
    public static function extend( $base, $replacement = null ) {
      // Return false if $base is invalid
      if( !isset($base) || !is_array( $base ) ) {
        return false;
      }

      // Return base if $replacement is not defined or if it's not valid
      if( !$replacement || !is_array( $replacement ) ) {
        return $base;
      }

      // Defining the $output
      $output = array_replace_recursive( $base, $replacement );

      // Returning the $output
      return $output;
    }

    /**
     * query
     * Creating and returning a new (optimized) WP_Query
     */
    public static function query( $array = null ) {

        // Defining the default optimized array
        $arguments = array(
            'no_found_rows'          => true,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
            'cache_results'          => false
        );

        // If $array is defined, merge it with the default array
        if(isset($array)) {
            $arguments = array_merge($arguments, $array);
        }

        // Defining the output with a new WP_Query with $arguments
        $output = new WP_Query( $arguments );

        // Returning the $output
        return $output;

    }

  }

  // Initialize
  // add_action( 'init' , array( 'v_' , 'init' ) );

  // Add Featured Image (post thumbnails)
  add_theme_support( 'post-thumbnails' );
}