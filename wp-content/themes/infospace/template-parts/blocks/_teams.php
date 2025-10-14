<?php global $prefix; ?>
    <section class="teams">
    <?php
    $counter = 1;
    $terms = get_terms(array(
        'taxonomy' => 'team',
        'meta_key' => $prefix . 'team_order', //name of meta field
        'orderby' => 'meta_value_num',
        'order' => 'ASC',
    ));

    foreach ($terms as $term) {
        $team = get_posts(array(
            'posts_per_page' => -1,
            'post_type' => 'person',
            'team' => $term->name,
            'order' => 'ASC',
            'orderby' => 'menu_order',

        ));

    ?>

        <section class="full-width">

            <div class="team">
                <?php echo "<h2>" . $term->name . "</h2>"; ?>
                <div class="grid-x grid-margin-x">
                    <?php foreach ($team as $person) {
                        $personId = $person->ID;
                        $personMeta = theme_get_meta($personId);
                        $contentText_post = get_post($personId);
                        $contentText = $contentText_post->post_content;
                        $contentText = apply_filters('the_content', $contentText);
                        $contentText = str_replace(']]>', ']]&gt;', $contentText);

                        //print_r($person ); 
                    ?>

                        <article class="cell medium-6 person">

                            <div class="person__container">

                                <div class="person__image">
                                    <?php if (isset($personMeta->_thumbnail_id)) { ?>
                                        <?php echo wp_get_attachment_image($personMeta->_thumbnail_id, 'smallsquare'); ?>
                                    <?php } ?>

                                </div>
                                <div class="person__content">

                                    <h3><?php echo get_the_title($personId); ?></h3>
                                    <?php if (isset($personMeta->person_job_title)) { ?>
                                        <p class="person__job"><?php echo $personMeta->person_job_title;  ?></p>
                                    <?php } ?>
                                    <?php if (!$contentText == "") { ?>
                                        <button class="openButton" aria-selected="false">Find out more</button>
                                    <?php } ?>


                                </div>

                            </div>

                            <div class="person__popup full-width" aria-hidden="true">
                                <div class="person__popup-container">
                                    <div class="person__popup-content">
                                        <h3><?php echo get_the_title($personId); ?></h3>
                                        <?php if (isset($personMeta->person_job_title)) { ?>
                                            <p class="person__job"><?php echo $personMeta->person_job_title;  ?></p>
                                        <?php } ?>
                                        <?php echo $contentText; ?>

                                        <button class="button botton__close">Close</button>
                                    </div>


                                    <div class="person__popup-image">
                                        <?php if (isset($personMeta->_thumbnail_id)) { ?>
                                            <?php echo wp_get_attachment_image($personMeta->_thumbnail_id, 'smallsquare'); ?>
                                        <?php } ?>

                                    </div>

                                </div>
                            </div>

                        </article>
                    <?php } ?>
                </div>
            </div>

        </section>

    <?php
        $counter++;
    } ?>

</section>