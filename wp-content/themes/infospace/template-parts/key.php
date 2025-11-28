<?php 
$moduleColour = $args['module_colour'] ?? '#000000';
$linkout_svg = $args['linkout_svg'] ?? '';
$resource_svg = $args['resource_svg'] ?? '';
?><h3>Key</h3>
<ul class="search-results__key-list" style="color : <?php echo esc_html($moduleColour); ?>;">
    <li><span><?php echo $linkout_svg; ?></span> External link</li>
    <li><span><?php echo $resource_svg; ?></span> InfoSpace page</li>
    <li><span><?php echo get_file_svg_from_filename('.doc', $moduleColour); ?></span>Word document</li>
    <li><span><?php echo get_file_svg_from_filename('.pdf', $moduleColour); ?></span>PDF document</li>
    <li><span><?php echo get_file_svg_from_filename('.xls', $moduleColour); ?></span>Excel document</li>
    <li><span><?php echo get_file_svg_from_filename('.ppt', $moduleColour); ?></span>PowerPoint document</li>
</ul>