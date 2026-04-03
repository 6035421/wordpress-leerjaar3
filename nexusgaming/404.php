<?php
/**
 * 404 Error Page Template voor Nexus Gaming Theme
 */
get_header(); ?>

<div class="error-404 not-found">
    <header class="page-header">
        <h1 class="page-title"><?php _e('Oeps! Pagina niet gevonden', 'nexusgaming'); ?></h1>
    </header>

    <div class="page-content">
        <p><?php _e('De pagina die u zoekt bestaat niet of is verplaatst.', 'nexusgaming'); ?></p>
        
        <div class="error-404-actions">
            <h3><?php _e('Wat kunt u doen?', 'nexusgaming'); ?></h3>
            <ul>
                <li><?php _e('Controleer de URL op typefouten', 'nexusgaming'); ?></li>
                <li><?php _e('Gebruik de zoekfunctie hieronder', 'nexusgaming'); ?></li>
                <li><?php _e('Keer terug naar de', 'nexusgaming'); ?> <a href="<?php echo esc_url(home_url('/')); ?>"><?php _e('homepage', 'nexusgaming'); ?></a></li>
            </ul>
        </div>

        <div class="search-form">
            <h3><?php _e('Zoeken', 'nexusgaming'); ?></h3>
            <?php get_search_form(); ?>
        </div>

        <div class="recent-posts">
            <h3><?php _e('Recente berichten', 'nexusgaming'); ?></h3>
            <ul>
                <?php
                $recent_posts = wp_get_recent_posts(array(
                    'numberposts' => 5,
                    'post_status' => 'publish'
                ));
                foreach ($recent_posts as $post) :
                    ?>
                    <li>
                        <a href="<?php echo get_permalink($post['ID']); ?>">
                            <?php echo get_the_title($post['ID']); ?>
                        </a>
                        <span class="post-date"><?php echo get_the_date('', $post['ID']); ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="categories">
            <h3><?php _e('Categorieën', 'nexusgaming'); ?></h3>
            <ul>
                <?php
                $categories = get_categories(array(
                    'orderby' => 'name',
                    'order'   => 'ASC'
                ));
                foreach ($categories as $category) :
                    ?>
                    <li>
                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
                            <?php echo esc_html($category->name); ?>
                        </a>
                        <span class="count">(<?php echo $category->count; ?>)</span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>

<?php get_footer(); ?>
