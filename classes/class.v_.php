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
  include( 'class.v_config.php' );
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
     * get_controller
     * This method loads and initializes the controller class, and returns the controller object
     * @param  [type] $path       [description]
     * @param  [type] $controller [description]
     * @return [type]             [description]
     */
    public static function get_controller( $options = null ) {

      // Define the controllers directory
      if( !defined( 'CONTROLLERS_DIR' ) ) {
        define( "CONTROLLERS_DIR", get_stylesheet_directory() ."/app/controllers/" );
      }

      // Defining the file path to load the controller
      $path = CONTROLLERS_DIR;
      $origin = array_values( debug_backtrace() )[0]['file'];
      $file_name = basename( $origin );
      $file_dirs = explode( '/', dirname( $origin ) );
      $file_dir = end( $file_dirs );

      $theme_dir = basename( get_template_directory() );

      // Adjust the path if there is a parent directory of the file
      if( $file_dir !== 'templates' &&
          $file_dir !== 'app' &&
          $file_dir !== $theme_dir ) {
        $path = $path . $file_dir . '/';
      }

      // Define the $file name to include
      $file = $path . $file_name;

      // Setting the controller
      $controller = '';

      // Loading the controller
      require_once( $file );

      // Defining the controller class name
      // Source: http://stackoverflow.com/questions/5546120/php-capitalize-after-dash
      $controller = implode('-', array_map('ucfirst', explode('-', $file_name)));
      $controller = str_replace( '-', '', $controller );
      $controller = str_replace( '.php', '', $controller );
      $controller = $controller . 'Controller';

      // Initializing the controller
      $controller_class = new $controller( $options );

      // Return false if $controller is not valid
      if( !$controller_class ) {
        return false;
      }

      // Returning the controller
      return $controller_class;
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

        // Getting pagination
        $paged = ( get_query_var('page') ) ? get_query_var('page') : 1;

        // Defining the default optimized array
        $arguments = array(
            'no_found_rows'          => true,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
            'cache_results'          => false,
            'paged' => $paged
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
     * query_offset
     * Creating and returning a new (optimized) WP_Query (with an offset)
     */
    public static function query_offset( $offset = null, $array = null ) {
      global $wp_query;

      // Defining defaults
      $arguments = null;

      // Return false if
      if( !isset( $wp_query->query ) ) {
        return false;
      } else {
        $arguments = $wp_query->query;
      }

      // Set the offset if it is not set (or is zero)
      if( !$offset ) {
        $offset = 1;
      }

      // Setting the offset
      $arguments["offset"] = $offset;

      // If $array is defined, merge it with the default array
      if(isset($array)) {
          $arguments = array_merge($arguments, $array);
      }

      // Return the optimized wp_query with the offset query
      return self::query( $arguments );

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
  // add_theme_support( 'post-thumbnails' );
}