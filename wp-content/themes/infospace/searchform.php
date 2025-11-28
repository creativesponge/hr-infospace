<?php

/**
 * The template for displaying search form
 *
 */

$isModule = $args['show-module'] ?? false;

global $namespace;

$searchTerm = isset($_GET['q']) ? esc_attr($_GET['q']) : '';
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
$current_module_id_global = isset($_SESSION['current_module_id']) ? $_SESSION['current_module_id'] : '';
$moduleMeta = get_current_module_meta($current_module_id_global);
$post_type = get_post_type();
$holdingText = 'Search';
if($post_type == 'resource_page' && $isModule && $current_module_id_global) {
	$title= get_the_title($current_module_id_global);
	$holdingText = 'Search ' . strtolower($title) . '';
} 

?>

<form role="search" method="get" class="searchform" action="<?php echo home_url('/'); ?>">

	<input type="text" class="input-group-field searchform__keyword" value="<?php echo $searchTerm;?>" name="s"  aria-label="Search" placeholder="<?php
																											esc_attr_e($holdingText, $namespace); ?>">
<div class="searchform__datafetch">Search results will appear here</div>
	<button type="submit" class="button--search"><span class="show-for-sr">Search</span>
		<?php get_template_part(
			'template-parts/svgs/_magnifying-glass',
			null,
			array(
				'module_colour' => $moduleMeta['module_color'] ?? null,
			)
		); ?>
	</button>


</form>