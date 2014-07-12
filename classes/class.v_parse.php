<?php
/**
 * v_parse
 * This is the parse class for the v_ wordpress theme
 *
 * @class
 *
 * @package v_
 *
 */

// Defining the v_parse class
if(!class_exists('v_parse')) {

  class v_parse {

    // fn: Remove <br> and <p> ($content, shortcode)
    // Description: Used to strip unnecessary <p> and <br> tags from the $content
    public static function content($content, $paragraph_tag = false, $br_tag = false ) {
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

}
