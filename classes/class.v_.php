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

  /**
   * Filter
   * @class   v_filter
   */
  include( 'class.v_filter.php' );

  /**
   * Header
   * @class   v_header
   */
  include( 'class.v_header.php' );

  /**
   * Image
   * @class   v_image
   */
  include( 'class.v_image.php' );

  /**
   * Posts
   * @class   v_post
   */
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
     * parse
     * Used to strip unnecessary <p> and <br> tags from the $content
     */
    public static function parse( $content, $paragraph_tag = false, $br_tag = false ) {
        $content = preg_replace( '#^<\/p>|^<br \/>|<p>$#', '', $content );

        if ( $br_tag ) {
            $content = preg_replace( '#<br \/>#', '', $content );
        }

        if ( $paragraph_tag ) {
            $content = preg_replace( '#<p>|</p>#', '', $content );
        }

        return do_shortcode( shortcode_unautop( trim( $content ) ) );
    }

  }

  // Initialize
  // add_action( 'init' , array( 'v_' , 'init' ) );

  // Add Featured Image (post thumbnails)
  add_theme_support( 'post-thumbnails' );
}