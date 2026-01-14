<?php if (is_user_logged_in()) : ?>
    <?php $block_attributes = get_query_var('attributes'); ?>
    <?php $block_content = get_query_var('content'); ?>
    <?php $current_user = wp_get_current_user(); ?>
    <section class="account-settings full-width">
        <header class="panel-header full-width">
            <div class="panel-header__inner">
                <div class="panel-header__content">
                    <h1 class="entry-title">Hello <?php echo esc_html($current_user->first_name . ' ' . $current_user->last_name); ?><span>Terms and conditions</span></h1>
                </div>
            </div>
        </header>
        <div class="account-settings__content">
            <?php
            echo $block_content; ?>
            <form method="post" action="">
                <input type="hidden" name="accept_terms" value="1" />
                <button type="submit" class="btn btn-primary">I agree</button>
            </form>
        </div>
    </section>
<?php endif; ?>