<?php
	global $settings;
    global  $prefix;
?>
<div class="socials">
    <?php
        /**
         *
         * Social Sharing
         *
         */


        if(!empty($settings[$prefix.'twitter'])) { ?>
            <a href="<?php echo $settings[$prefix.'twitter']?>" target="_blank" rel="noopener" aria-label="Twitter link"><?php get_template_part('template-parts/svgs/_twitter') ?></a>
        <?php }

        if(!empty($settings[$prefix.'instagram'])) { ?>
            <a href="<?php echo $settings[$prefix.'instagram']?>" target="_blank" rel="noopener" aria-label="Instagram link"><?php get_template_part('template-parts/svgs/_instagram') ?></a>
        <?php }

        if(!empty($settings[$prefix.'facebook'])) { ?>
            <a href="<?php echo $settings[$prefix.'facebook']?>" target="_blank" rel="noopener" aria-label="Facebook link"><?php get_template_part('template-parts/svgs/_facebook') ?></a>
        <?php }

        if(!empty($settings[$prefix.'linkedin'])) { ?>
            <a href="<?php echo $settings[$prefix.'linkedin']?>" target="_blank" rel="noopener" aria-label="Linkedin link"><?php get_template_part('template-parts/svgs/_linkedin') ?></a>
        <?php }

		if(!empty($settings[$prefix.'youtube'])) { ?>
			<a href="<?php echo $settings[$prefix.'youtube']?>" target="_blank" rel="noopener" aria-label="YouTube link"><?php get_template_part('template-parts/svgs/_youtube') ?></a>
		<?php }

    ?>
</div>
