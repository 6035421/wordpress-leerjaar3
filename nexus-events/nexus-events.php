<?php
/** 
 * Plugin Name: Nexus Gaming Events
 * Description: Events plugin voor Nexus Gaming Community - Beheer en toon gaming events.
 * Version: 1.0
 * Author: Quinten Does
 * License: GPLv2
 */

if (!defined('ABSPATH')) {
    exit;
}

define('NEXUS_EVENTS_VERSION', '1.0.0');
define('NEXUS_EVENTS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('NEXUS_EVENTS_PLUGIN_URL', plugin_dir_url(__FILE__));

class NexusEvents {
    
    public function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_taxonomies'));
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_data'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_shortcode('nexus_events', array($this, 'events_shortcode'));
        add_shortcode('nexus_games', array($this, 'games_shortcode'));
        add_shortcode('nexus_test', array($this, 'test_shortcode'));
        add_filter('template_include', array($this, 'single_event_template'));
        add_action('init', array($this, 'register_gutenberg_block'));
        
        // Admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Admin columns
        add_filter('manage_nexus_event_posts_columns', array($this, 'set_custom_columns'));
        add_action('manage_nexus_event_posts_custom_column', array($this, 'custom_column_data'), 10, 2);
        
        // Flush rewrite rules on plugin activation
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    public function activate() {
        $this->register_post_type();
        $this->register_taxonomies();
        flush_rewrite_rules();
    }
    
    public function deactivate() {
        flush_rewrite_rules();
    }
    
    public function register_post_type() {
        $labels = array(
            'name' => __('Events', 'nexus-events'),
            'singular_name' => __('Event', 'nexus-events'),
            'menu_name' => __('Events', 'nexus-events'),
            'name_admin_bar' => __('Event', 'nexus-events'),
            'add_new' => __('Nieuw Event', 'nexus-events'),
            'add_new_item' => __('Nieuw Event Toevoegen', 'nexus-events'),
            'new_item' => __('Nieuw Event', 'nexus-events'),
            'edit_item' => __('Event Bewerken', 'nexus-events'),
            'view_item' => __('Event Bekijken', 'nexus-events'),
            'all_items' => __('Alle Events', 'nexus-events'),
            'search_items' => __('Events Zoeken', 'nexus-events'),
            'parent_item_colon' => __('Parent Event:', 'nexus-events'),
            'not_found' => __('Geen events gevonden.', 'nexus-events'),
            'not_found_in_trash' => __('Geen events gevonden in prullenbak.', 'nexus-events'),
            'featured_image' => __('Event Afbeelding', 'nexus-events'),
            'set_featured_image' => __('Stel event afbeelding in', 'nexus-events'),
            'remove_featured_image' => __('Verwijder event afbeelding', 'nexus-events'),
            'use_featured_image' => __('Gebruik als event afbeelding', 'nexus-events'),
            'archives' => __('Event Archieven', 'nexus-events'),
            'insert_into_item' => __('Invoegen in event', 'nexus-events'),
            'uploaded_to_this_item' => __('Geüpload naar dit event', 'nexus-events'),
            'filter_items_list' => __('Filter events lijst', 'nexus-events'),
            'items_list_navigation' => __('Events lijst navigatie', 'nexus-events'),
            'items_list' => __('Events lijst', 'nexus-events'),
        );
        
        $args = array(
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'events'),
            'capability_type' => 'post',
            'hierarchical' => false,
            'menu_position' => 25,
            'menu_icon' => 'dashicons-calendar-alt',
            'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'author', 'comments'),
        );
        
        register_post_type('nexus_event', $args);
    }
    
    public function register_taxonomies() {
        // Game taxonomy
        $labels = array(
            'name' => __('Games', 'nexus-events'),
            'singular_name' => __('Game', 'nexus-events'),
            'search_items' => __('Games Zoeken', 'nexus-events'),
            'all_items' => __('Alle Games', 'nexus-events'),
            'parent_item' => __('Parent Game', 'nexus-events'),
            'parent_item_colon' => __('Parent Game:', 'nexus-events'),
            'edit_item' => __('Game Bewerken', 'nexus-events'),
            'update_item' => __('Game Updaten', 'nexus-events'),
            'add_new_item' => __('Nieuwe Game Toevoegen', 'nexus-events'),
            'new_item_name' => __('Nieuwe Game Naam', 'nexus-events'),
            'menu_name' => __('Games', 'nexus-events'),
        );
        
        $args = array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'game'),
        );
        
        register_taxonomy('nexus_game', array('nexus_event'), $args);
    }
    
    public function add_meta_boxes() {
        add_meta_box(
            'nexus_event_details',
            __('Event Details', 'nexus-events'),
            array($this, 'meta_box_callback'),
            'nexus_event',
            'normal',
            'high'
        );
    }
    
    public function meta_box_callback($post) {
        wp_nonce_field('nexus_event_save_meta', 'nexus_event_meta_nonce');
        
        $event_date = get_post_meta($post->ID, '_nexus_event_date', true);
        $event_time = get_post_meta($post->ID, '_nexus_event_time', true);
        $event_host = get_post_meta($post->ID, '_nexus_event_host', true);
        $event_location = get_post_meta($post->ID, '_nexus_event_location', true);
        $event_max_players = get_post_meta($post->ID, '_nexus_event_max_players', true);
        
        echo '<div class="nexus-event-meta">';
        echo '<p><label for="nexus_event_date">' . __('Event Datum:', 'nexus-events') . '</label>';
        echo '<input type="date" id="nexus_event_date" name="nexus_event_date" value="' . esc_attr($event_date) . '" class="widefat"></p>';
        
        echo '<p><label for="nexus_event_time">' . __('Event Tijd:', 'nexus-events') . '</label>';
        echo '<input type="time" id="nexus_event_time" name="nexus_event_time" value="' . esc_attr($event_time) . '" class="widefat"></p>';
        
        echo '<p><label for="nexus_event_host">' . __('Host:', 'nexus-events') . '</label>';
        echo '<input type="text" id="nexus_event_host" name="nexus_event_host" value="' . esc_attr($event_host) . '" class="widefat"></p>';
        
        echo '<p><label for="nexus_event_location">' . __('Locatie:', 'nexus-events') . '</label>';
        echo '<input type="text" id="nexus_event_location" name="nexus_event_location" value="' . esc_attr($event_location) . '" class="widefat"></p>';
        
        echo '<p><label for="nexus_event_max_players">' . __('Max Spelers:', 'nexus-events') . '</label>';
        echo '<input type="number" id="nexus_event_max_players" name="nexus_event_max_players" value="' . esc_attr($event_max_players) . '" class="widefat"></p>';
        echo '</div>';
    }
    
    public function save_meta_data($post_id) {
        if (!isset($_POST['nexus_event_meta_nonce']) || !wp_verify_nonce($_POST['nexus_event_meta_nonce'], 'nexus_event_save_meta')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        if (get_post_type($post_id) !== 'nexus_event') {
            return;
        }
        
        $fields = array('nexus_event_date', 'nexus_event_time', 'nexus_event_host', 'nexus_event_location', 'nexus_event_max_players');
        
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }
    }
    
    public function set_custom_columns($columns) {
        unset($columns['date']);
        $columns['event_date'] = __('Datum', 'nexus-events');
        $columns['event_time'] = __('Tijd', 'nexus-events');
        $columns['event_host'] = __('Host', 'nexus-events');
        $columns['game'] = __('Game', 'nexus-events');
        $columns['date'] = __('Gepost', 'nexus-events');
        return $columns;
    }
    
    public function custom_column_data($column, $post_id) {
        switch ($column) {
            case 'event_date':
                $date = get_post_meta($post_id, '_nexus_event_date', true);
                echo $date ? date('d-m-Y', strtotime($date)) : '-';
                break;
            case 'event_time':
                echo get_post_meta($post_id, '_nexus_event_time', true) ?: '-';
                break;
            case 'event_host':
                echo get_post_meta($post_id, '_nexus_event_host', true) ?: '-';
                break;
            case 'game':
                $games = get_the_terms($post_id, 'nexus_game');
                if ($games && !is_wp_error($games)) {
                    $game_names = array();
                    foreach ($games as $game) {
                        $game_names[] = $game->name;
                    }
                    echo implode(', ', $game_names);
                } else {
                    echo '-';
                }
                break;
        }
    }
    
    public function enqueue_scripts() {
        wp_enqueue_style('nexus-events-frontend', NEXUS_EVENTS_PLUGIN_URL . 'assets/css/frontend.css', array(), NEXUS_EVENTS_VERSION);
        wp_enqueue_script('nexus-events-frontend', NEXUS_EVENTS_PLUGIN_URL . 'assets/js/frontend.js', array('jquery'), NEXUS_EVENTS_VERSION, true);
    }
    
    public function enqueue_admin_scripts($hook) {
        global $post_type;
        
        if ($post_type === 'nexus_event') {
            wp_enqueue_style('nexus-events-admin', NEXUS_EVENTS_PLUGIN_URL . 'assets/css/admin.css', array(), NEXUS_EVENTS_VERSION);
            wp_enqueue_script('nexus-events-admin', NEXUS_EVENTS_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), NEXUS_EVENTS_VERSION, true);
        }
    }
    
    public function events_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 10,
            'category' => '',
            'show_past' => 'false',
            'order' => 'ASC',
        ), $atts, 'nexus_events');
        
        $args = array(
            'post_type' => 'nexus_event',
            'posts_per_page' => intval($atts['limit']),
            'orderby' => 'meta_value',
            'meta_key' => '_nexus_event_date',
            'order' => $atts['order'],
        );
        
        if ($atts['category']) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'nexus_game',
                    'field' => 'slug',
                    'terms' => $atts['category'],
                ),
            );
        }
        
        if ($atts['show_past'] === 'false') {
            $args['meta_query'] = array(
                array(
                    'key' => '_nexus_event_date',
                    'value' => date('Y-m-d'),
                    'compare' => '>=',
                    'type' => 'DATE',
                ),
            );
        }
        
        $query = new WP_Query($args);
        
        ob_start();
        if ($query->have_posts()) {
            echo '<div class="nexus-events-container">';
            while ($query->have_posts()) {
                $query->the_post();
                $this->render_event_card();
            }
            echo '</div>';
        } else {
            echo '<p>' . __('Geen events gevonden.', 'nexus-events') . '</p>';
        }
        wp_reset_postdata();
        
        return ob_get_clean();
    }
    
    public function games_shortcode($atts) {
        $atts = shortcode_atts(array(
            'show_count' => 'true',
            'show_empty' => 'true',
            'columns' => 'auto',
            'orderby' => 'name',
            'order' => 'ASC'
        ), $atts, 'nexus_games');
        
        // Debug: Let's see what we get
        $args = array(
            'taxonomy' => 'nexus_game',
            'hide_empty' => false, // Always show for debugging
            'orderby' => $atts['orderby'],
            'order' => $atts['order']
        );
        
        $games = get_terms($args);
        
        
        if (is_wp_error($games) || empty($games)) {
            return $debug_info . '<p>' . __('Geen games gevonden.', 'nexus-events') . '</p>';
        }
        
        ob_start();
        echo $debug_info;
        echo '<div class="nexus-games-container">';
        
        if ($atts['show_count'] === 'true') {
            echo '<div class="nexus-games-header">';
            // echo '<h3 style="color:black;">' . __('Beschikbare Games', 'nexus-events') . '</h3>';
            echo '<p class="nexus-games-count">' . sprintf(_n('Totaal: %d game', 'Totaal: %d games', count($games), 'nexus-events'), count($games)) . '</p>';
            echo '</div>';
        }
        
        $columns_class = $atts['columns'] === 'auto' ? 'nexus-games-auto-columns' : 'nexus-games-columns-' . intval($atts['columns']);
        echo '<div class="nexus-games-grid ' . esc_attr($columns_class) . '">';
        
        foreach ($games as $game) {
            // echo '<div style="background: #f0f0f0; padding: 10px; margin: 5px; border: 1px solid #ccc;">';
            // echo '<h4>Game: ' . esc_html($game->name) . '</h4>';
            // echo '<p>Slug: ' . esc_html($game->slug) . '</p>';
            // echo '<p>Count: ' . esc_html($game->count) . '</p>';
            // echo '<p>Description: ' . esc_html($game->description) . '</p>';
            // echo '</div>';
            $this->render_game_card($game, $atts);
        }
        
        echo '</div>';
        echo '</div>';
        
        wp_reset_postdata();
        return ob_get_clean();
    }
    
    private function render_game_card($game, $atts) {
        $game_link = get_term_link($game);
        $event_count = $game->count;
        
        echo '<div class="nexus-game-card">';
        echo '<div class="nexus-game-content">';
        echo '<h4 class="nexus-game-title">';
        if ($atts['show_empty'] === 'true' || $event_count > 0) {
            echo '<a href="' . esc_url($game_link) . '">' . esc_html($game->name) . '</a>';
        } else {
            echo esc_html($game->name);
        }
        echo '</h4>';
        
        if ($game->description) {
            echo '<div class="nexus-game-description">' . wp_kses_post(wpautop($game->description)) . '</div>';
        }
        
        echo '<div class="nexus-game-meta">';
        if ($event_count > 0) {
            echo '<div class="nexus-game-events-count">📅 ' . sprintf(_n('%d event', '%d events', $event_count, 'nexus-events'), $event_count) . '</div>';
        } else {
            echo '<div class="nexus-game-events-count">📅 ' . __('Geen events', 'nexus-events') . '</div>';
        }
        echo '</div>';
        
        if ($atts['show_empty'] === 'true' || $event_count > 0) {
            echo '<a href="' . esc_url($game_link) . '" class="nexus-game-link">' . __('Bekijk Events', 'nexus-events') . '</a>';
        }
        
        echo '</div>';
        echo '</div>';
    }
    
    private function render_event_card() {
        $event_date = get_post_meta(get_the_ID(), '_nexus_event_date', true);
        $event_time = get_post_meta(get_the_ID(), '_nexus_event_time', true);
        $event_host = get_post_meta(get_the_ID(), '_nexus_event_host', true);
        $event_location = get_post_meta(get_the_ID(), '_nexus_event_location', true);
        $games = get_the_terms(get_the_ID(), 'nexus_game');
        
        echo '<div class="nexus-event-card">';
        if (has_post_thumbnail()) {
            echo '<div class="nexus-event-image">' . get_the_post_thumbnail(get_the_ID(), 'medium') . '</div>';
        }
        echo '<div class="nexus-event-content">';
        echo '<h3 class="nexus-event-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
        
        if ($event_date) {
            echo '<div class="nexus-event-date">📅 ' . date('d F Y', strtotime($event_date)) . '</div>';
        }
        if ($event_time) {
            echo '<div class="nexus-event-time">🕐 ' . $event_time . '</div>';
        }
        if ($event_host) {
            echo '<div class="nexus-event-host">👤 ' . __('Host:', 'nexus-events') . ' ' . esc_html($event_host) . '</div>';
        }
        if ($event_location) {
            echo '<div class="nexus-event-location">📍 ' . esc_html($event_location) . '</div>';
        }
        if ($games && !is_wp_error($games)) {
            $game_names = array();
            foreach ($games as $game) {
                $game_names[] = $game->name;
            }
            echo '<div class="nexus-event-games">🎮 ' . implode(', ', $game_names) . '</div>';
        }
        
        echo '<div class="nexus-event-excerpt">' . get_the_excerpt() . '</div>';
        echo '<a href="' . get_permalink() . '" class="nexus-event-link">' . __('Bekijk Details', 'nexus-events') . '</a>';
        echo '</div>';
        echo '</div>';
    }
    
    public function single_event_template($template) {
        if (is_singular('nexus_event')) {
            $theme_template = locate_template(array('single-nexus_event.php', 'nexus-events/single-nexus_event.php'));
            if (!$theme_template) {
                $template = NEXUS_EVENTS_PLUGIN_DIR . 'templates/single-nexus_event.php';
            }
        }
        return $template;
    }
    
    public function register_gutenberg_block() {
        if (!function_exists('register_block_type')) {
            return;
        }
        
        wp_register_script(
            'nexus-events-block',
            NEXUS_EVENTS_PLUGIN_URL . 'assets/js/block.js',
            array('wp-blocks', 'wp-element', 'wp-editor'),
            NEXUS_EVENTS_VERSION
        );
        
        register_block_type('nexus-events/events-list', array(
            'editor_script' => 'nexus-events-block',
            'render_callback' => array($this, 'render_gutenberg_block'),
            'attributes' => array(
                'limit' => array(
                    'type' => 'number',
                    'default' => 10,
                ),
                'category' => array(
                    'type' => 'string',
                    'default' => '',
                ),
                'showPast' => array(
                    'type' => 'boolean',
                    'default' => false,
                ),
            ),
        ));
    }
    
    public function render_gutenberg_block($attributes) {
        $atts = array(
            'limit' => isset($attributes['limit']) ? $attributes['limit'] : 10,
            'category' => isset($attributes['category']) ? $attributes['category'] : '',
            'show_past' => isset($attributes['showPast']) && $attributes['showPast'] ? 'true' : 'false',
        );
        
        return $this->events_shortcode($atts);
        return $this->games_shortcode($atts);
    }
    
    public function add_plugin_action_links($links) {
        $settings_link = '<a href="' . admin_url('edit.php?post_type=nexus_event') . '">' . __('Manage Events', 'nexus-events') . '</a>';
        array_unshift($links, $settings_link);
        
        $details_link = '<a href="' . admin_url('admin.php?page=nexus-events-details') . '">' . __('Details', 'nexus-events') . '</a>';
        array_push($links, $details_link);
        
        return $links;
    }
    
    public function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=nexus_event',
            __('Plugin Details', 'nexus-events'),
            __('Details', 'nexus-events'),
            'manage_options',
            'nexus-events-details',
            array($this, 'render_details_page')
        );
    }
    
    public function render_details_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Nexus Events Plugin Details', 'nexus-events'); ?></h1>
            
            <div class="card">
                <h2><?php _e('Plugin Information', 'nexus-events'); ?></h2>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><?php _e('Plugin Name', 'nexus-events'); ?></th>
                        <td>Nexus Gaming Events</td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Version', 'nexus-events'); ?></th>
                        <td><?php echo NEXUS_EVENTS_VERSION; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Author', 'nexus-events'); ?></th>
                        <td>Quinten Does</td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Description', 'nexus-events'); ?></th>
                        <td><?php _e('Events plugin voor Nexus Gaming Community - Beheer en toon gaming events.', 'nexus-events'); ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('License', 'nexus-events'); ?></th>
                        <td>GPLv2</td>
                    </tr>
                </table>
            </div>
            
            <div class="card">
                <h2><?php _e('Features', 'nexus-events'); ?></h2>
                <ul>
                    <li><?php _e('Custom Event Post Type', 'nexus-events'); ?></li>
                    <li><?php _e('Game Taxonomy', 'nexus-events'); ?></li>
                    <li><?php _e('Event Metadata (Date, Time, Host, Location, Max Players)', 'nexus-events'); ?></li>
                    <li><?php _e('Event Shortcode', 'nexus-events'); ?></li>
                    <li><?php _e('Gutenberg Block', 'nexus-events'); ?></li>
                    <li><?php _e('Single Event Details Page', 'nexus-events'); ?></li>
                    <li><?php _e('Responsive Design', 'nexus-events'); ?></li>
                    <li><?php _e('Dark Mode Support', 'nexus-events'); ?></li>
                </ul>
            </div>
            
            <div class="card">
                <h2><?php _e('Usage', 'nexus-events'); ?></h2>
                <p><strong><?php _e('Shortcode:', 'nexus-events'); ?></strong></p>
                <code>[nexus_events]</code>
                <p><strong><?php _e('Gutenberg Block:', 'nexus-events'); ?></strong></p>
                <p><?php _e('Use the "Nexus Events List" block in the Gutenberg editor.', 'nexus-events'); ?></p>
                <p><strong><?php _e('Event Details Page:', 'nexus-events'); ?></strong></p>
                <p><?php _e('Single event pages are automatically created at /events/[event-slug]', 'nexus-events'); ?></p>
            </div>
            
            <div class="card">
                <h2><?php _e('Statistics', 'nexus-events'); ?></h2>
                <?php
                $event_count = wp_count_posts('nexus_event');
                $published_events = $event_count->publish;
                $draft_events = $event_count->draft;
                $games = get_terms(array('taxonomy' => 'nexus_game', 'hide_empty' => false));
                $game_count = is_wp_error($games) ? 0 : count($games);
                ?>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><?php _e('Published Events', 'nexus-events'); ?></th>
                        <td><?php echo $published_events; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Draft Events', 'nexus-events'); ?></th>
                        <td><?php echo $draft_events; ?></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e('Total Games', 'nexus-events'); ?></th>
                        <td><?php echo $game_count; ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <?php
    }
    
    
}

new NexusEvents();
