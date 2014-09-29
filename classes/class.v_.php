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
     * get_template
     * This gets and returns the template file using locate_template. This allows for $variables to be passed into the template
     */
    public static function get_template( $path = null , $params = null) {

      // Return false if $path is not defined
      if( !$path ) return false;

      // Extracting the parameters
      if( $params ) {
        extract( $params );
      }

      // Defining the output with locate_template
      $output = include( locate_template( $path . '.php' ) );

      // Returning the output
      return $output;

    }

    /**
     * insert_terms
     * This inserts terms for taxomies in bulk
     */
    public static function insert_terms() {

      // Get all arguments as array
      $terms_length = func_num_args();
      $terms = func_get_args();

      // Return false if $terms is not defined or invalid
      if( !$terms || $terms_length == 0 ) {
        return false;
      }

      /**
       * Each $terms item should follow the following format
       * array( 'Term Name', 'Taxonomy', 'Slug' )
       */
      for( $i = 0; $i < $terms_length; $i++ ) {

        // Defining the term
        $term = $terms[$i];

        // Defining the $term variables
        $name = $term[0];
        $taxonomy = $term[1];
        $slug = null;

        if( isset( $term[2] ) ) {
          $slug = $term[2];
        }

        // Skip interation if any of the above variables are not defined
        if( !$name || !$taxonomy || term_exists( $name, $taxonomy ) ) {
          continue;
        }

        // Insert the term
        if( $slug ) {
          $slug = array( 'slug' => $slug );
        }

        wp_insert_term( $name, $taxonomy, $slug);

      }

      return true;
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

    /**
     * require_all
     * Requiring all the files of a particular directory
     */
    public static function require_all( $dir = false ) {
      // Return false if $dir is not defined
      if( $dir == false ) {
        return false;
      }

      // Defining the directory
      $directory = get_stylesheet_directory() . '/' . $dir;
      $files = glob( $directory . "/*" );

      // Return turn false if directory is empty
      if($files <= 0) {
        return $false;
      }

      // Looping through each file
      foreach($files as $file) {
        // Requiring the file
        require_once( $file );
      }

      // Returning true
      return true;
    }
  }

  // Initialize
  // add_action( 'init' , array( 'v_' , 'init' ) );

  // Add Featured Image (post thumbnails)
  add_theme_support( 'post-thumbnails' );
}