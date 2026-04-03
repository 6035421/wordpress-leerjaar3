<?php
/**
 * Single Event Template
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        
        <?php while (have_posts()) : the_post(); ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class('nexus-single-event'); ?>>
                
                <header class="entry-header">
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    
                    <div class="nexus-event-meta-single">
                        <?php
                        $event_date = get_post_meta(get_the_ID(), '_nexus_event_date', true);
                        $event_time = get_post_meta(get_the_ID(), '_nexus_event_time', true);
                        $event_host = get_post_meta(get_the_ID(), '_nexus_event_host', true);
                        $event_location = get_post_meta(get_the_ID(), '_nexus_event_location', true);
                        $event_max_players = get_post_meta(get_the_ID(), '_nexus_event_max_players', true);
                        $games = get_the_terms(get_the_ID(), 'nexus_game');
                        ?>
                        
                        <div class="nexus-event-details-grid">
                            <?php if ($event_date) : ?>
                                <div class="event-detail-item">
                                    <span class="event-detail-label">📅 <?php _e('Datum:', 'nexus-events'); ?></span>
                                    <span class="event-detail-value"><?php echo date('d F Y', strtotime($event_date)); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($event_time) : ?>
                                <div class="event-detail-item">
                                    <span class="event-detail-label">🕐 <?php _e('Tijd:', 'nexus-events'); ?></span>
                                    <span class="event-detail-value"><?php echo esc_html($event_time); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($event_host) : ?>
                                <div class="event-detail-item">
                                    <span class="event-detail-label">👤 <?php _e('Host:', 'nexus-events'); ?></span>
                                    <span class="event-detail-value"><?php echo esc_html($event_host); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($event_location) : ?>
                                <div class="event-detail-item">
                                    <span class="event-detail-label">📍 <?php _e('Locatie:', 'nexus-events'); ?></span>
                                    <span class="event-detail-value"><?php echo esc_html($event_location); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($event_max_players) : ?>
                                <div class="event-detail-item">
                                    <span class="event-detail-label">👥 <?php _e('Max Spelers:', 'nexus-events'); ?></span>
                                    <span class="event-detail-value"><?php echo esc_html($event_max_players); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($games && !is_wp_error($games)) : ?>
                                <div class="event-detail-item">
                                    <span class="event-detail-label">🎮 <?php _e('Game(s):', 'nexus-events'); ?></span>
                                    <span class="event-detail-value">
                                        <?php 
                                        $game_names = array();
                                        foreach ($games as $game) {
                                            $game_names[] = $game->name;
                                        }
                                        echo implode(', ', $game_names);
                                        ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </header>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="nexus-event-featured-image">
                        <?php the_post_thumbnail('large'); ?>
                    </div>
                <?php endif; ?>
                
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
                
                <footer class="entry-footer">
                    <div class="nexus-event-actions">
                        <button class="nexus-share-button" onclick="nexusShareEvent()">
                            <?php _e('Delen', 'nexus-events'); ?>
                        </button>
                        
                        <?php if (comments_open() || get_comments_number()) : ?>
                            <a href="#comments" class="nexus-comments-link">
                                <?php 
                                $comments_count = get_comments_number();
                                if ($comments_count == 0) {
                                    _e('Reageer', 'nexus-events');
                                } else {
                                    printf(_n('%d Reactie', '%d Reacties', $comments_count, 'nexus-events'), $comments_count);
                                }
                                ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="nexus-event-navigation">
                        <?php
                        $prev_post = get_previous_post();
                        $next_post = get_next_post();
                        
                        if ($prev_post) : ?>
                            <div class="nav-prev">
                                <a href="<?php echo get_permalink($prev_post); ?>" class="nav-link">
                                    ← <?php echo get_the_title($prev_post); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="nav-center">
                            <a href="<?php echo get_post_type_archive_link('nexus_event'); ?>" class="nav-link">
                                <?php _e('← Terug naar Events', 'nexus-events'); ?>
                            </a>
                        </div>
                        
                        <?php if ($next_post) : ?>
                            <div class="nav-next">
                                <a href="<?php echo get_permalink($next_post); ?>" class="nav-link">
                                    <?php echo get_the_title($next_post); ?> →
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </footer>
                
                <?php if (comments_open() || get_comments_number()) : ?>
                    <div id="comments" class="comments-area">
                        <?php comments_template(); ?>
                    </div>
                <?php endif; ?>
                
            </article>
            
        <?php endwhile; ?>
        
    </main>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>

<script>
function nexusShareEvent() {
    const url = window.location.href;
    const title = document.title;
    
    if (navigator.share) {
        navigator.share({
            title: title,
            url: url
        }).catch(err => console.log('Error sharing:', err));
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(url).then(() => {
            alert('<?php _e('Link gekopieerd naar klembord!', 'nexus-events'); ?>');
        });
    }
}
</script>
