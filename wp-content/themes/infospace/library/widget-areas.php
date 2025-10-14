<?php
/**
 * Register widget areas
 *

 */

if ( ! function_exists( 'startertheme_sidebar_widgets' ) ) :
	function startertheme_sidebar_widgets() {
		global $namespace;

		register_sidebar(
			array(
				'id'            => 'sidebar-widgets',
				'name'          => __( 'Sidebar widgets', $namespace ),
				'description'   => __( 'Drag widgets to this sidebar container.', $namespace ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h6>',
				'after_title'   => '</h6>',
			)
		);

		register_sidebar(
			array(
				'id'            => 'footer-widgets',
				'name'          => __( 'Footer widgets', $namespace ),
				'description'   => __( 'Drag widgets to this footer container', $namespace ),
				'before_widget' => '<section id="%1$s" class="widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h6>',
				'after_title'   => '</h6>',
			)
		);
	}

	add_action( 'widgets_init', 'startertheme_sidebar_widgets' );
endif;
