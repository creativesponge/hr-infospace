<?php

/**
 * Customize the output of menus for Startertheme top bar
 *
 */

if (! class_exists('Startertheme_Account_Walker')) :
    class Startertheme_Account_Walker extends Walker_Nav_Menu
    {

        function start_lvl(&$output, $depth = 0, $args = array())
        {
            $indent  = str_repeat("\t", $depth);
            $output .= "\n$indent<ul class=\"dropdown menu vertical\" data-toggle>\n";
            // Add user name as first menu item if user is logged in
            if (is_user_logged_in()) {
                $current_user = wp_get_current_user();

                $first_name = $current_user->first_name;
                $last_name = $current_user->last_name;
                $display_name = trim($first_name . ' ' . $last_name);
                if (empty($display_name)) {
                    $display_name = $current_user->display_name;
                }
                $output .= "\n$indent\t<li class=\"menu-item\"><span class=\"user-greeting\">Hello " . esc_html($display_name) . "</span></li>\n";
            }
        }

        function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
        {
            if (!is_user_logged_in()) {
                $depth = 0;
            }
            global $prefix;

            $indent = ($depth) ? str_repeat("\t", $depth) : '';

            $classes = empty($item->classes) ? array() : (array) $item->classes;
            $classes[] = 'menu-item-' . $item->ID;

            $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
            $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

            $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
            $id = $id ? ' id="' . esc_attr($id) . '"' : '';

            $attributes  = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) . '"' : '';
            $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target) . '"' : '';
            $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn) . '"' : '';
            $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url) . '"' : '';

            // Get the custom image
            $nav_image = get_post_meta($item->ID, $prefix . 'nav_file', true);


            $image_html = '';

            if ($nav_image) {
                // If it's an attachment ID, get the image
                if (is_numeric($nav_image)) {
                    $image_html = wp_get_attachment_image($nav_image, 'thumbnail', false, array('class' => 'menu-item-image'));
                } else {
                    // If it's a URL, create img tag
                    $image_html = '<img src="' . esc_url($nav_image) . '" alt="' . esc_attr($item->title) . '" class="menu-item-image">';
                }
            }

            $item_output = $args->before ?? '';
            $item_output .= '<a' . $attributes . '>';
            $item_output .= ($args->link_before ?? '') . apply_filters('the_title', $item->title, $item->ID) . ($args->link_after ?? '');
            $item_output .= $image_html; // Add the image before the title

            $item_output .= '</a>';
            $item_output .= $args->after ?? '';

            $output .= $indent . '<li' . $id . $class_names . '>';
            $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
        }

        function end_el(&$output, $item, $depth = 0, $args = array())
        {
            $output .= "</li>\n";
        }

        function end_lvl(&$output, $depth = 0, $args = array())
        {
            $indent = str_repeat("\t", $depth);
            global $prefix;
            // Add user greeting at the end if user is logged in
            if (is_user_logged_in()) {


                $account_image_html = wp_get_attachment_image(1605, 'thumbnail', false, array('class' => 'menu-item-image'));
                $output .= "\n$indent\t<li class=\"menu-item\"><a href=\"/accounts/\">Account settings" . $account_image_html . "</a></li>\n";

                if (current_user_can('administrator')) {
                    $admin_image_html = wp_get_attachment_image(1608, 'thumbnail', false, array('class' => 'menu-item-image'));
                    $output .= "\n$indent\t<li class=\"menu-item\"><a href=\"/wp-admin/\">Admin" . $admin_image_html . "</a></li>\n";
                }
                $logout_image_html = wp_get_attachment_image(1607, 'thumbnail', false, array('class' => 'menu-item-image'));
                $output .= "\n$indent\t<li class=\"menu-item\"><a href=\"/wp-login.php?action=logout\">Log out" . $logout_image_html . "</a></li>\n";
    
            } else {
                $login_image_html = wp_get_attachment_image(1607, 'thumbnail', false, array('class' => 'menu-item-image'));
                $output .= "\n$indent\t<li class=\"menu-item is-login\"><a href=\"/wp-login.php\">Log in" . $login_image_html . "</a></li>\n";
            }
            $output .= "$indent</ul>\n";
        }
    }

endif;
