<?php
/**
 * Single Post Template voor Nexus Gaming Theme
 */
get_header(); ?>

<div class="single-post-container">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('single-post-article'); ?>>
            <?php if (has_post_thumbnail()) : ?>
                <div class="featured-image-wrapper">
                    <?php the_post_thumbnail('large', array('class' => 'featured-image')); ?>
                    <div class="featured-image-overlay"></div>
                </div>
            <?php endif; ?>
            
            <div class="post-content-wrapper">
                <header class="entry-header">
                    <div class="post-category">
                        <?php if (has_category()) : ?>
                            <?php 
                            $categories = get_the_category();
                            if ($categories) {
                                echo '<span class="category-badge">' . esc_html($categories[0]->name) . '</span>';
                            }
                            ?>
                        <?php endif; ?>
                    </div>
                    
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    
                    <div class="entry-meta">
                        <div class="meta-left">
                            <span class="author-avatar">
                                <?php echo get_avatar(get_the_author_meta('user_email'), 40); ?>
                            </span>
                            <div class="author-info-small">
                                <span class="author-name"><?php the_author(); ?></span>
                                <span class="post-date">
                                    <time class="entry-date published" datetime="<?php echo get_the_date('c'); ?>">
                                        <?php echo get_the_date(); ?>
                                    </time>
                                </span>
                            </div>
                        </div>
                        
                        <div class="meta-right">
                            <span class="reading-time">
                                <?php 
                                $content = get_post_field('post_content', get_the_ID());
                                $word_count = str_word_count(strip_tags($content));
                                $reading_time = ceil($word_count / 200);
                                echo $reading_time . ' min lezen';
                                ?>
                            </span>
                        </div>
                    </div>
                </header>

                <div class="entry-content">
                    <?php
                    the_content();
                    
                    wp_link_pages(array(
                        'before' => '<div class="page-links"><span class="page-links-title">' . __('Pagina\'s:', 'nexusgaming') . '</span>',
                        'after'  => '</div>',
                    ));
                    ?>
                </div>

                <?php if (has_tag()) : ?>
                    <div class="post-tags">
                        <h3>Tags</h3>
                        <div class="tags-list">
                            <?php the_tags('', '', ''); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <footer class="entry-footer">
                    <?php if (get_the_author_meta('description')) : ?>
                        <div class="author-bio-section">
                            <h3>Over de auteur</h3>
                            <div class="author-info">
                                <div class="author-avatar-large">
                                    <?php echo get_avatar(get_the_author_meta('user_email'), 80); ?>
                                </div>
                                <div class="author-description">
                                    <h4 class="author-title"><?php the_author(); ?></h4>
                                    <p class="author-bio"><?php the_author_meta('description'); ?></p>
                                    <div class="author-links">
                                        <a class="author-link" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" rel="author">
                                            <?php _e('Bekijk alle berichten van', 'nexusgaming'); ?> <?php the_author(); ?> &rarr;
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </footer>
            </div>
        </article>

        <nav class="navigation post-navigation" role="navigation">
            <h2 class="screen-reader-text"><?php _e('Berichtnavigatie', 'nexusgaming'); ?></h2>
            <div class="nav-links">
                <div class="nav-previous">
                    <?php previous_post_link('%link', '<span class="nav-label">Vorige</span><span class="nav-title">%title</span>'); ?>
                </div>
                <div class="nav-next">
                    <?php next_post_link('%link', '<span class="nav-label">Volgende</span><span class="nav-title">%title</span>'); ?>
                </div>
            </div>
        </nav>

        <?php
        // If comments are open or we have at least one comment, load up the comment template.
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;
        ?>

    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
