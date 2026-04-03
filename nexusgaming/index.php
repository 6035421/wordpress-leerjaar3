<?php
/**
 * Hoofdtemplate (Index) voor Nexus Gaming Theme
 */
get_header(); ?>

<?php if (have_posts()) : ?>
    
    <?php if (is_home() && !is_front_page()) : ?>
        <header class="page-header">
            <h1 class="page-title"><?php single_post_title(); ?></h1>
        </header>
    <?php endif; ?>

    <div class="posts-container">
        <?php while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php
                    if (is_singular()) :
                        the_title('<h1 class="entry-title">', '</h1>');
                    else :
                        the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
                    endif;
                    ?>
                    
                    <div class="entry-meta">
                        <span class="posted-on">
                            <time class="entry-date published" datetime="<?php echo get_the_date('c'); ?>">
                                <?php echo get_the_date(); ?>
                            </time>
                        </span>
                        <span class="byline">
                            <?php _e('door', 'nexusgaming'); ?> <span class="author vcard"><?php the_author(); ?></span>
                        </span>
                        <?php if (has_category()) : ?>
                            <span class="cat-links">
                                <?php _e('in', 'nexusgaming'); ?> <?php the_category(', '); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </header>

                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail">
                        <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                            <?php the_post_thumbnail('medium'); ?>
                        </a>
                    </div>
                <?php endif; ?>

                <div class="entry-content">
                    <?php
                    if (is_singular()) :
                        the_content();
                    else :
                        the_excerpt();
                    endif;
                    ?>
                </div>

                <?php if (!is_singular()) : ?>
                    <footer class="entry-footer">
                        <a href="<?php the_permalink(); ?>" class="read-more">
                            <?php _e('Lees meer', 'nexusgaming'); ?> &rarr;
                        </a>
                    </footer>
                <?php endif; ?>
            </article>
        <?php endwhile; ?>
    </div>

    <?php the_posts_pagination(array(
        'mid_size'  => 2,
        'prev_text' => __('&laquo; Vorige', 'nexusgaming'),
        'next_text' => __('Volgende &raquo;', 'nexusgaming'),
    )); ?>

<?php else : ?>
    
    <article class="no-results">
        <header class="entry-header">
            <h1 class="entry-title"><?php _e('Niets gevonden', 'nexusgaming'); ?></h1>
        </header>
        <div class="entry-content">
            <p><?php _e('Sorry, er zijn geen berichten gevonden die aan uw zoekopdracht voldoen.', 'nexusgaming'); ?></p>
            <?php get_search_form(); ?>
        </div>
    </article>

<?php endif; ?>

<?php get_footer(); ?>   
