<?php

/**
 * The default template for displaying page content
 *
 *
 */

global $namespace;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="resource-page__header full-width" style="background-color: <?php echo esc_html($moduleColour); ?>;">
        <div class="resource-page__header-inner">
            <h1 class="entry-title"><?php the_title(); ?></h1>

        </div>
    </header>
    <div class="entry-content">
        <div class="resource-page__grid">
            <div class="resource-page__col1">
                <?php the_content();  ?>
                <?php edit_post_link(__('(Edit)', $namespace), '<span class="edit-link">', '</span>'); ?>
            </div>
            <div class="resource-page__col2">
            </div>
        </div>
    </div>
    <footer>
        <?php
        wp_link_pages(
            array(
                'before' => '<nav id="page-nav"><p>' . __('Pages:', $namespace),
                'after'  => '</p></nav>',
            )
        );
        ?>
        <?php $tag = get_the_tags();
        if ($tag) { ?><p><?php the_tags(); ?></p><?php } ?>
    </footer>
</article>