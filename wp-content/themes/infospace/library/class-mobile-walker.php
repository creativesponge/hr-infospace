<?php
/**
 * Customize the output of menus for Startertheme mobile walker
 *
 */

if ( ! class_exists( 'Startertheme_Mobile_Walker' ) ) :
	class Startertheme_Mobile_Walker extends Walker_Nav_Menu {
		function start_lvl( &$output, $depth = 0, $args = array() ) {
			$indent  = str_repeat( "\t", $depth );
			$output .= "\n$indent<ul class=\"vertical nested menu active\">\n";
		}
	}
endif;
