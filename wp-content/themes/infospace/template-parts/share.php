<?php

/**
 *
 * Social Sharing
 *
 */ 
$permalink = get_permalink();
$title = urlencode(get_the_title());
$rawTitle = get_the_title();
?>
<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $permalink ; ?>" target="_blank" rel="noopener" aria-label="Facebook link"><?php get_template_part('template-parts/svgs/_facebook') ?></a>
<a href="https://twitter.com/intent/tweet?url=<?php echo $permalink ; ?>&text=<?php echo $rawTitle; ?>" target="_blank" rel="noopener" aria-label="Twitter link"><?php get_template_part('template-parts/svgs/_twitter') ?></a>
<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $permalink ; ?>" target="_blank" rel="noopener" aria-label="Linkedin link"><?php get_template_part('template-parts/svgs/_linkedin') ?></a>
