<?php
/**
 * v_filter
 * This is the filter class for the v_ wordpress theme
 *
 * @class
 *
 * @package v_
 *
 */

// Defining the v_filter class
if(!class_exists('v_filter')) {

  class v_filter {

    /**
     * init
     * Initialization method for this class
     *
     * @init
     */
    public static function init() {

      // Content Filters
      add_filter( 'the_content', array( 'v_filter', 'content_iframes_replace_tag' ));
      add_filter( 'the_content', array( 'v_filter', 'content_images_replace_tag' ));

      // Excerpt filers
      add_filter('excerpt_more', array( 'v_filter', 'excerpt_read_more' ) );
    }

    /**
     * content_iframes_replace_tag description
     * Replace <p> tags surrounding iframes with <div> tags
     * @param  [ string ] $content  [ Wordpress the_content ]
     * @return [ html ]             [ the_content, filtered ]
     */
    public static function content_iframes_replace_tag( $content ) {

      // Defining the args
      $args = array(
        'class'  =>         'iframe',
        'tag'    =>         'iframe'
        );

      // Returning the filter
      return self::content_media_replace_tag( $content, $args );
    }


    /**
     * content_images_replace_tag description
     * Replace <p> tags surrounding images with <div> tags
     * @param  [ string ] $content  [ Wordpress the_content ]
     * @return [ html ]             [ the_content, filtered ]
     */
    public static function content_images_replace_tag( $content ) {

      // Defining the args
      $args = array(
        'class'  =>         'image',
        'tag'    =>         'img'
        );

      // Returning the filter
      return self::content_media_replace_tag( $content, $args );
    }


    /**
     * content_media_replace_tag
     * Wraps media items (images and iFrames) inside the_content() with a div
     * @param  [ string ] $content  [ Wordpress the_content ]
     * @param  [ string ] $args     [ type of media ]
     * @return [ html ]             [ the_content, filtered ]
     */
    public static function content_media_replace_tag( $content, $args ) {

      // Return the $content if $args are not defined
      if( !isset($args) ) {
        return $content;
      }

      // Defining the Media ($1 as default to work with preg_replace)
      $media = '$1';

      // Regex filter to find/locate the <p> tags and the $args['tag']
      $filter = '/<p[^>]*>\\s*?(<a .*?><'.$args['tag'].'.*?><\\/a>|<'.$args['tag'].'.*?>)?\\s*<\/p>/';

      // Adjust the class if the tag is an iFrame
      if( $args['tag'] === 'iframe' ) {

        // Defining the video sites to filter for
        $video_sites = array(
          'kickstarter',
          'revision3',
          'vimeo',
          'youtube'
          );

        // Looping through the video sites
        foreach($video_sites as $site) {

          // If the content contains the $site's key name
          if( strpos($content, $site) ) {
            // Add video and the $site name
            $args['class'] = $args['class'] . ' video ' . $site;
            // Break the foreach loop
            break;
          }
        }
      }

      // Wrapping the <img> with <figure> if tag is img
      // if($args['tag'] === 'img') {
        // $media = '<figure>$1</figure>';
      // }

      // Defining the output
      $output = "\n".
      '<!-- Post Article: Media -->' . "\n" .
      '<div class="entry-media-container '.$args['class'].'">'.$media.'</div>' .
      "\n";

      // Returning the $content with preg_replace
      return preg_replace( $filter, $output, $content );
    }


    /**
     * excerpt_read_more
     * Filter for the WP the_excerpt() method
     * @return [ html ]       [ read more link ]
     */
    public static function excerpt_read_more( $more ) {
      global $post;
      // Defining the $output (read more link)
      $output = '...<br><p><a class="read-more more-link" href="'. get_permalink( $post->ID ) . '"> Read More</a></p>';
      // Returning the $output
      return $output;
    }


  }

  // Initialize
  add_action( 'init' , array( 'v_filter' , 'init' ) );

}