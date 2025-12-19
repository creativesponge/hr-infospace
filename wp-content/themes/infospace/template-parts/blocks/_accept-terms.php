<?php if (is_user_logged_in()) : ?>
    <?php $block_attributes = get_query_var('attributes'); ?>
    <?php $block_content = get_query_var('content'); ?>

    <?php
    echo $block_content; ?>
    <form method="post" action="">
        <input type="hidden" name="accept_terms" value="1" />
        <button type="submit" class="btn btn-primary">I agree</button>
    </form>
<?php endif; ?>