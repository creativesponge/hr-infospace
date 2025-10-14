<?php $block_attributes = get_query_var('attributes'); ?>
<?php $block_content = get_query_var('content');

$mapLong = isset($block_attributes['mapLong']) ? $block_attributes['mapLong'] : '';
$mapLat = isset($block_attributes['mapLat']) ? $block_attributes['mapLat'] : '';
$googleKey = isset($block_attributes['googleKey']) ? $block_attributes['googleKey'] : '';

if ($mapLong && $mapLat && $googleKey) {
wp_enqueue_script('gmaps', 'https://maps.googleapis.com/maps/api/js?key='.$googleKey, [], 'h_');
?>
<section class="google-map">
	<div class="map" data-longitude="<?php echo $mapLong ?>" data-latitude="<?php echo $mapLat ?>"></div>
</section>
<?php } ?>
