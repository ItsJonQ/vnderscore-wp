<?php
/**
 * v_image
 * This is the image class for the v_ wordpress theme
 *
 * @class
 *
 * @package v_
 *
 */

// Defining the v_image class
if(!class_exists('v_image')) {

  class v_image {
    /**
     * $config
     * The stored config + data for this class
     */
    private static $config = array(
      'has_featurette_cover'      => false
      );

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
     * get_thumbnail
     * Method that grabs the thumbnail / featured image from a post
     *
     * @param  [ array ] $options   [ array of options]
     * @return [ html / img]        [ returns an image tag ]
     */
    public static function get_thumbnail( $options = array() ) {

      global $post;

      // Defining the default $settings
      $settings = array(
        'return'    => 'image',
        'size'      => 'thumbnail',
        'alt'       => 'alt',
        'id'        => $post->ID
        );

      // Extending the $settings
      $settings = v_::extend( $settings, $options );

      // Defining the $return from options
      $return = $settings['return'];

      // Defining the size from options
      $size = $settings['size'];

      // Defining the alt
      $alt = $settings['alt'];

      $id = $settings['id'];

      // Getting and defining the $thumb from WP thumbnail
      $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $id ), $size );

      // $thumb array reference
      // $thumb_src = $thumb[0];
      // $thumb_width = $thumb[1];
      // $thumb_height = $thumb[2];

      // Adjust the image "alt" if alt is defined
      if( isset($alt) ) {
        $alt = 'alt="'.$alt.'"';
      }

      // Return false if the thumbnail doesn't exist
      if(!$thumb) return false;

      // Returning a URL
      if( $return === 'url' ) {
        $output = $thumb[0];
      }
      // Returning the $thumb array
      elseif( $return === 'array' ) {
        $output = $thumb;
      }
      // Returning an image (default)
      else {
        $output = '<img itemprop="image" src="'.$thumb[0].'"" '.$alt.' width="'.$thumb[1].'" height="'.$thumb[2].'">';
      }

      // Returning the output
      return $output;

    }

    public static function featurette_cover( $options = array()) {
      global $post;

      // Defining the default $settings
      $settings = array(
        'permalink'     => false,
        'title'         => false,
        'id'            => $post->ID
        );

      // Extending the $settings
      $settings = v_::extend( $settings, $options );

      /**
       * Defining the $featured_image using the get_thumbnail method
       * @param       return      returns the thumbnail url
       * @param       size        full sized image
       * @param       id          the post's id
       */
      $featured_image = self::get_thumbnail( array(
        'return'    => 'url',
        'size'      => 'full',
        'id'        => $id
        ));

      // Return false if post does not have a featured image
      if( !$featured_image ) {
        return false;
      }

      /**
       * Making the cover linkable, if permalink is defined
       */
      $permalink = $settings['permalink'];
      // If $permalink is set
      if( $permalink ) {
        // Create the permalink
        $permalink = '<a href="'.$permalink.'" class="cover-link"></a>';
      } else {
        // Else null
        $permalink = null;
      }

      // Defining the cover
      $output = '
        <!-- Featured Image: Cover -->
        <section class="featurette section cover" style="background-image:url('.$featured_image.')">'.$permalink.'</section>
        ';

      // Updating the classes status for has_featurette_cover
      self::$config['has_featurette_cover'] = true;

      // Echoing the $output
      echo $output;
    }

    /**
     * has_cover
     * Public method to check if a featurette cover was used on the page
     * @return boolean [ true / false ]
     */
    public static function has_cover() {
      // Returning the boolean from $config
      return self::$config['has_featurette_cover'];
    }

    /**
     * has_featurette_cover
     * @class_alias( has_cover )
     * Public method to check if a featurette cover was used on the page
     * @return boolean [ true / false ]
     */
    public static function has_featurette_cover() {
      // Returning the boolean from $config
      return self::has_cover();
    }

  }

  // Initialize
  // add_action( 'init' , array( 'v_image' , 'init' ) );

}