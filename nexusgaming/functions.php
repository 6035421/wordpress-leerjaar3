<?php
/**
 * Nexus Gaming Theme Functions
 */

// Laad de stylesheet van het thema
function nexusgaming_enqueue_styles() {
    wp_enqueue_style('nexusgaming-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'nexusgaming_enqueue_styles');

// Voeg theme support toe
function nexusgaming_theme_setup() {
    // Voeg titel tag support toe
    add_theme_support('title-tag');
    
    // Voeg post thumbnails support toe
    add_theme_support('post-thumbnails');
    
    // Voeg custom logo support toe
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // Voeg HTML5 support toe
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Voeg post formats support toe
    add_theme_support('post-formats', array(
        'aside',
        'image',
        'video',
        'quote',
        'link',
    ));
}
add_action('after_setup_theme', 'nexusgaming_theme_setup');

// Registreer navigatie menus
function nexusgaming_register_menus() {
    register_nav_menus(array(
        'primary' => __('Hoofdmenu', 'nexusgaming'),
        'footer' => __('Footermenu', 'nexusgaming'),
    ));
}
add_action('init', 'nexusgaming_register_menus');

// Stel de excerpt lengte in
function nexusgaming_excerpt_length($length) {
    return 40;
}
add_filter('excerpt_length', 'nexusgaming_excerpt_length');

// Verander de excerpt "meer" tekst
function nexusgaming_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'nexusgaming_excerpt_more');

// Custom URL functie voor footer links
function nexusgaming_custom_url($path = '') {
    return home_url('/index.php/' . ltrim($path, '/'));
}

// Fallback menu functie
function nexusgaming_fallback_menu() {
    if (current_user_can('edit_theme_options')) {
        ?>
        <ul id="primary-menu">
            <li><a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>"><?php _e('Voeg een menu toe', 'nexusgaming'); ?></a></li>
        </ul>
        <?php
    } else {
        ?>
        <ul id="primary-menu">
            <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php _e('Home', 'nexusgaming'); ?></a></li>
        </ul>
        <?php
    }
}