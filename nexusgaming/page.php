<?php
/**
 * Page Template voor Nexus Gaming Theme
 */
get_header(); ?>

<?php while (have_posts()) : the_post(); ?>
    <article id="page-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header">
            <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
        </header>

        <?php if (has_post_thumbnail()) : ?>
            <div class="page-thumbnail">
                <?php the_post_thumbnail('large'); ?>
            </div>
        <?php endif; ?>

        <div class="entry-content">
            <?php
            the_content();
            
            wp_link_pages(array(
                'before' => '<div class="page-links">' . __('Pagina\'s:', 'nexusgaming'),
                'after'  => '</div>',
            ));
            ?>
        </div>

        <?php if (get_edit_post_link()) : ?>
            <footer class="entry-footer">
                <a href="<?php echo esc_url(get_edit_post_link()); ?>" class="edit-link">
                    <?php _e('Bewerk pagina', 'nexusgaming'); ?>
                </a>
            </footer>
        <?php endif; ?>
    </article>

    <?php
    // If comments are open or we have at least one comment, load up the comment template.
    if (comments_open() || get_comments_number()) :
        comments_template();
    endif;
    ?>

<?php endwhile; ?>

<?php get_footer(); ?>
