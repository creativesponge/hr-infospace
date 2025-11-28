<?php

/**
 * Customize the output of menus for Startertheme top bar
 *
 */

if (! class_exists('Startertheme_Top_Bar_Walker')) :
	class Startertheme_Top_Bar_Walker extends Walker_Nav_Menu
	{
		function start_lvl(&$output, $depth = 0, $args = array())
		{
			$indent  = str_repeat("\t", $depth);
			$output .= "\n$indent<ul class=\"dropdown menu vertical\" data-toggle>\n";
		}
		function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
		{
			// Start session if not already started
			if (session_status() === PHP_SESSION_NONE) {
				session_start();
			}
			$current_module_id_global = isset($_SESSION['current_module_id']) ? $_SESSION['current_module_id'] : '';

			// Check if the menu item is "News" and user is not logged in
			if ($item->title === 'News' && (!is_user_logged_in() || $current_module_id_global == '')) {
				return;
			}

			$indent = str_repeat("\t", $depth);

			$classes = empty($item->classes) ? array() : (array) $item->classes;
			$classes[] = 'menu-item-' . $item->ID;
			$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));

			$id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);

			$output .= $indent . '<li' . ($id ? ' id="' . esc_attr($id) . '"' : '') . ($class_names ? ' class="' . esc_attr($class_names) . '"' : '') . '>';

			$attributes = '';
			$attributes .= !empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) . '"' : '';
			$attributes .= !empty($item->target)     ? ' target="' . esc_attr($item->target) . '"' : '';
			$attributes .= !empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn) . '"' : '';
			$attributes .= !empty($item->url)        ? ' href="'   . esc_attr($item->url) . '"' : '';

			$before = isset($args->before) ? $args->before : '';
			$after = isset($args->after) ? $args->after : '';
			$link_before = isset($args->link_before) ? $args->link_before : '';
			$link_after = isset($args->link_after) ? $args->link_after : '';

			$item_output = $before . '<a' . $attributes . '>' . $link_before . apply_filters('the_title', $item->title, $item->ID) . $link_after . '</a>' . $after;

			$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
		}
	}
endif;
